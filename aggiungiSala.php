<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Add a Room - Admin Panel");
        }else{
                setTitle($content, "Aggiungi Una Sala - Pannello Amministrazione");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Add A Room", "");
        }else{
                addBreadcrumb($content, "Pannello di Amministrazione", "admin.php");
                addBreadcrumb($content, "Aggiungi una Sala", "");
        }
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);

        setContentFromFile($content, __("contenuto_aggiungisala.html"));
        if (isset($_POST['inserimento'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        if (checkMoneyInput($_POST['PrezzoOrario'])){
                                $result = $dbAccess->addRoom($_POST['Nome'], $_POST['Funzione'], $_POST['PrezzoOrario']);
                        }else{
                                $result = false;
                        }
                        if ($result==True){
                                $status = "<div class='statussuccess'>" . getMessage("12") . "</div>";
                        }else{
                                $status = "<div class='statusfailed'>" . getMessage("216");
                                if (isset($_SESSION['moneyErrors'])){
                                        $status = $status . "<br />" . $_SESSION['moneyErrors'];
                                        unset($_SESSION['moneyErrors']);
                                }
                                $status = $status . "</div>";
                        }
                        $dbAccess->closeDBConnection();
                }
                $content = str_replace("<!--STATUS-->", $status, $content);
        }
        echo($content);
?>
