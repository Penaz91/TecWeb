<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Instrumentation Rental", "noleggio.php");
                addBreadcrumb($content, "Check Availability", "");
                setTitle($content, "Check Instrumentation Availability");
        }else{
                addBreadcrumb($content, "Noleggio Strumentazione", "noleggio.php");
                addBreadcrumb($content, "Verifica Disponibilità", "");
                setTitle($content, "Verifica Disponibilità Strumentazione");
        }
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setLoadScript($content, "");
        setContentFromFile($content, __("contenuto_verificaDisp.html"));
        echo($content);
?>
