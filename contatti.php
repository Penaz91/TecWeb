<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        $content = file_get_contents(__("struttura.html"));

        initBreadcrumbs($content, "Home", "index.php");
        if ($_SESSION['language']=='en'){
                setTitle($content, "Contact Us");
                addBreadcrumb($content, "Contact Us", "");
        }else{
                setTitle($content, "Contatti");
                addBreadcrumb($content, "Contatti", "");
        }
        addScreenStylesheet("CSS/style_contatti.css", $content);
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 4);
        setLangArea($content, "contatti.php");
        setContentFromFile($content, "contenuto_contatti.html");
        echo($content);
?>
