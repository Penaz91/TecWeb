<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        //use DBAccess;

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUser();


        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $res = $dbAccess->editUserData($_SESSION['username'], $_POST['Mmail'], $_POST['Mtel']);
                if ($res){
                        $_SESSION['status']=0;
                        $_SESSION['statusmessage'] = "Modifica dei dati avvenuta correttamente!";
                }else{
                        $_SESSION['status']=1;
                        $_SESSION['statusmessage'] = "Modifica dei dati fallita, riprova più tardi o contatta un amministratore";
                }
                $dbAccess->closeDBConnection();
                header("Location: userpanel.php");
        }
?>
