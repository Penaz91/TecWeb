<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        //use DBAccess;

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                checkLoggedUserAndRedirect("userpanel_account.php");
                $content = file_get_contents("struttura.html");
                setTitle($content, "Modifica/Elimina Account");
                setUserStatus($content);
                setupMenu($content, -1);
                initBreadcrumbs($content, "Home", "index.php");
                addBreadcrumb($content, "Pannello Utente", "userpanel.php");
                addBreadcrumb($content, "Modifica/Elimina Account", "");
                setAdminArea($content);
                $struct = file_get_contents("struttura_userpanel_account.html");
                $torepl = "<!--USER-->";
                $repl = str_replace($torepl, $_SESSION['username'], $struct);
                $torepl = "<!--VALOREMAIL-->";
                $repl = str_replace($torepl, $dbAccess->getMail($_SESSION['username']),$repl);
                $torepl = "<!--VALORENTEL-->";
                $repl = str_replace($torepl, $dbAccess->getTelefono($_SESSION['username']),$repl);
                $torepl = "<!--STATO-->";
                if (isset($_SESSION['status'])){
                        if ($_SESSION['status']==0){
                                $replacer = '<div id="statussuccess">';
                        }else{
                                $replacer = '<div id="statusfailed">';
                        }
                                $replacer = $replacer . $_SESSION['statusmessage'];
                                $replacer = $replacer . "</div>";
                                unset($_SESSION['status']);
                                unset($_SESSION['statusmessage']);
                }else{
                        $replacer = "";
                }
                $repl = str_replace($torepl, $replacer,$repl);
                $torepl = "<!--CONTENUTO-->";
                $replaced = str_replace($torepl, $repl, $content);
                echo ($replaced);
                $dbAccess->closeDBConnection();
        }
?>
