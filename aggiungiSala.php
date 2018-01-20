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
        setLoadScript($content, "setAddRoomPH()");
        setContentFromFile($content, __("contenuto_aggiungisala.html"));
        if (isset($_POST['inserimento'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = $dbAccess->addRoom($_POST['Nome'], $_POST['Funzione'], $_POST['PrezzoOrario']);
                        if ($result==True){
                                $status = "<div id='statussuccess'>Sala Aggiunta correttamente</div>";
                        }else{
                                $status = "<div id='statusfailed'>Si è verificato un errore durante l'aggiunta della sala, Forse esiste già?</div>";
                        }
                        $dbAccess->closeDBConnection();
                }
                $content = str_replace("<!--STATUS-->", $status, $content);
        }
        echo($content);
?>
