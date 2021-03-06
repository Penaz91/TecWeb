<?php
	require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
	require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUser();
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                if ($_GET['c'] != $_SESSION['username']){
                        header("Location: accesso_negato.html");
                        exit();
                }else{
                        $result = $dbAccess->deleteRental($_GET['c'], $_GET['s'], $_GET['di'], $_GET['df']);
                        $dbAccess->closeDBConnection();
                        if ($result){
                                $_SESSION['statusmessage']=getMessage("22");
                                $_SESSION['statussuccess']=true;
                        }else{
                                $_SESSION['statusmessage']=getMessage("238");
                                $_SESSION['statussuccess']=false;
                        }
                        header("Location: userpanel.php");
                }
        }
?>
