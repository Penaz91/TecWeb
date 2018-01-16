<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        //use DBAccess;

        session_start();
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $uservalid= $dbAccess->checkUser($_POST['username']);
                if ($uservalid == false){
                        $_SESSION['loginstatus']='noUser';
                        header("Location: login.php");
                        //Sana
                }else{
                        $logged = $dbAccess->checkLogin($_POST['username'], $_POST['password']);
                        if ($logged == 0){
                                $_SESSION['loginstatus']='wrongPass';
                                header("Location: login.php");
                        }else{
                                session_start();
                                if ($logged==2){
                                        $_SESSION['admin']=true;
                                }else{
                                        $_SESSION['admin']=false;
                                }
                                $_SESSION['username']=$_POST['username'];
                                unset($_SESSION['loginstatus']);
                                if (isset($_SESSION['referral'])){
                                        header("Location: " . $_SESSION['referral']);
                                        unset($_SESSION['referral']);
                                }else{
                                        header("Location: index.php");
                                }
                        }
                $dbAccess->closeDBConnection();
                }
        }
?>
