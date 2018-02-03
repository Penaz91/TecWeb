<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $result = $dbAccess->setAdmin($_GET['id'], $_GET['admin']);
                $dbAccess->closeDBConnection();
                if ($result){
                        $_SESSION['statusmessage']=getMessage("20");
                        $_SESSION['statussuccess']=true;
                }else{
                        $_SESSION['statusmessage']=getMessage("236");
                        $_SESSION['statussuccess']=false;
                }
                header("Location: admin.php");
        }

?>
