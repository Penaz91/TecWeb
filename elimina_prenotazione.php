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
                if ($_GET['id'] != $_SESSION['username']){
                        header("Location: accesso_negato.html");
                        exit();
                }else{
                        $result = $dbAccess->deleteBooking($_GET['id'], $_GET['sala'], $_GET['servizio'], $_GET['data'], $_GET['ora']);
                        $dbAccess->closeDBConnection();
                        if ($result){
                                $_SESSION['statusmessage']=getMessage("23");
                                $_SESSION['statussuccess']=true;
                        }else{
                                $_SESSION['statusmessage']=getMessage("239");
                                $_SESSION['statussuccess']=false;
                        }
                        header("Location: userpanel.php");
                }
        }
?>
