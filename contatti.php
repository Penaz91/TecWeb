<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Contact Us");
                addBreadcrumb($content, "Contact Us", "");
        }else{
                setTitle($content, "Contatti");
                addBreadcrumb($content, "Contatti", "");
        }
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR ."style_contatti.css", $content);
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 4);
        setLangArea($content, "contatti.php");

        setContentFromFile($content, __("contenuto_contatti.html"));
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
