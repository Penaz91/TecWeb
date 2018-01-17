<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        //use DBAccess;

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
                        $_SESSION['statusmessage']="Stanza Eliminata con successo";
                }else{
                        $_SESSION['statussuccess']=false;
                        $_SESSION['statusmessage']="Impossibile Eliminare la stanza";
                }
                header("Location: searchEditRoom.php");
        }
?>
