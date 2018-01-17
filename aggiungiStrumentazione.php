<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Add Instrumentation");
        }else{
                setTitle($content, "Aggiungi Nuova Strumentazione");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Add Instrumentation", "");
        }else{
                addBreadcrumb($content, "Pannello Amministrativo", "admin.php");
                addBreadcrumb($content, "Aggiungi Strumentazione", "");
        }
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setLoadScript($content, "setInstrumentAddPH()");
        setContentFromFile($content, __("struttura_aggiungiStrumentazione.html"));
        echo($content);
?>
