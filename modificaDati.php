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
                $res = false;
                if ($telOk && $mailOk){
                        $res = $dbAccess->editUserData($_SESSION['username'], $_POST['Mmail'], $_POST['Mtel']);
                }else{
                        $hasErrors = true;
                }
                if ($res && !$hasErrors){
                        $_SESSION['status']=0;
                        $_SESSION['statusmessage'] = getMessage("16");
                }else{
                        $_SESSION['status']=1;
                        $_SESSION['statusmessage'] = getMessage("220");
                        if (isset($_SESSION['RemailErr2'])){
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br /><a href='#Mmail' title='" . getMessage("106") . "'>" . getMessage("221") . "</a>";
                                unset($_SESSION['RemailErr2']);
                        }
                        if (isset($_SESSION['RtelErr'])){
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br /><a href='#Mtel' title='" . getMessage("107") . "'>" . getMessage("222") . "</a>";
                                unset($_SESSION['RtelErr']);
                        }
                        if (!$hasErrors){
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br />" . getMessage("223");
                        }
                }
                $dbAccess->closeDBConnection();
                header("Location: userpanel_account.php");
        }
?>
