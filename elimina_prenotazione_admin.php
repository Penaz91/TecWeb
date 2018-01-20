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
                $dbAccess->deleteBooking($_GET['id'], $_GET['sala'], $_GET['servizio'], $_GET['data'], $_GET['ora']);
                $dbAccess->closeDBConnection();
                header("Location: admin.php");
        }
?>
