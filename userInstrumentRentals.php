<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUser();
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Your Rentals");
        }else{
                setTitle($content, "I Tuoi Noleggi");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "User Panel", "userpanel.php");
                addBreadcrumb($content, "Your Rentals", "");
        }else{
                addBreadcrumb($content, "Pannello Utente", "userpanel.php");
                addBreadcrumb($content, "Le tue Prenotazioni", "");
        }
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setLoadScript($content, "");
        setContentFromFile($content, __("contenuto_prenotazioniUtente.html"));
        echo($content);
?>
