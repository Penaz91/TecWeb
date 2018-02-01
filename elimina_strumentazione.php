<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $res = $dbAccess->deleteInstrument($_GET['id']);
                if ($res==true){
                        $_SESSION['statussuccess']=true;
                        $_SESSION['statusmessage']=getMessage("19");
                }else{
                        $_SESSION['statussuccess']=false;
                        $_SESSION['statusmessage']=getMessage("232");
                }
                $dbAccess->closeDBConnection();
                header("Location: searchEditInstruments.php");
        }
?>
