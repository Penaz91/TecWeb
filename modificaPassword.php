<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUser();

        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $logged = $dbAccess->checkLogin($_SESSION['username'], $_POST['MOldPass']);
                if ($logged){
                        if ($_POST['MNewPass1'] == $_POST['MNewPass2']){
                                $dbAccess->editPassword($_SESSION['username'], $_POST['MNewPass1']);
                                $_SESSION['status']=0;
                                $_SESSION['statusmessage']="Password cambiata con successo";
                        }else{
                                $_SESSION['status']=1;
                                $_SESSION['statusmessage']="Le due nuove password non corrispondono";
                        }
                }else{
                        $_SESSION['status']=1;
                        $_SESSION['statusmessage']="La password originale inserita non è corretta";
                }
                header("Location: userpanel.php");
        }
?>
