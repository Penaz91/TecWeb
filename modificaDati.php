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
                $mailOk = checkMailInput($_POST['Mmail']);
                $telOk = checkTelInput($_POST['Mtel']);
                $hasErrors = false;
                if ($telOk && $mailOk){
                        $res = $dbAccess->editUserData($_SESSION['username'], $_POST['Mmail'], $_POST['Mtel']);
                }else{
                        $hasErrors = true;
                }
                if ($res && !$hasErrors){
                        $_SESSION['status']=0;
                        $_SESSION['statusmessage'] = "Modifica dei dati avvenuta correttamente!";
                }else{
                        $_SESSION['status']=1;
                        $_SESSION['statusmessage'] = "Modifica dei dati fallita:";
                        if (isset($_SESSION['RemailErr2'])){
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br />L'indirizzo email non è nel formato corretto";
                                unset($_SESSION['RemailErr2']);
                        }
                        if (isset($_SESSION['RtelErr'])){
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br />Il numero telefonico non è nel formato corretto";
                                unset($_SESSION['RtelErr']);
                        }
                        if (!$hasErrors){
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br />Contattare l'amministratore";
                        }
                }
                $dbAccess->closeDBConnection();
                header("Location: userpanel_account.php");
        }
?>
