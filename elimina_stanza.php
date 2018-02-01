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
                $res = $dbAccess->deleteRoom($_GET['id'], $_GET['func']);
                if ($res==true){
                        $_SESSION['statussuccess']=true;
                        $_SESSION['statusmessage']=getMessage("18");
                }else{
                        $_SESSION['statussuccess']=false;
                        $_SESSION['statusmessage']=getMessage("231");
                }
                $dbAccess->closeDBConnection();
                header("Location: searchEditRoom.php");
        }
?>
