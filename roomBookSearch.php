<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        $content = file_get_contents(__("struttura.html"));

        if ($_SESSION['language']=='en'){
                //Lingua Inglese
                setTitle($content, "Booked Rooms - Admin Panel");
        }else{
                //Italiano
                setTitle($content, "Prenotazioni Sale - Pannello Amministratore");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if ($_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Booked Rooms", "");
        }else{
                addBreadcrumb($content, "Pannello di Amministrazione", "admin.php");
                addBreadcrumb($content, "Prenotazioni Sale", "");
        }
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setContentFromFile($content, __("contenuto_ricercaPrenotazioni.html"));
        echo($content);
?>
