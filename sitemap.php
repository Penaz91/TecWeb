<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                //Lingua Inglese
                setTitle($content, "Site Map");
        }else{
                //Italiano
                setTitle($content, "Mappa del Sito");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Site Map", "");
        }else{
                addBreadcrumb($content, "Mappa del Sito", "");
        }
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        $currcontent = file_get_contents(__("sitemap_main.html"));
        if (isset($_SESSION['username'])){
                $currcontent = str_replace("<!--USERPANELLIST-->", file_get_contents(__("sitemap_userpanel.html")), $currcontent);
        }
        if (isset($_SESSION['admin']) && $_SESSION['admin']==true){
                $currcontent = str_replace("<!--ADMINPANELLIST-->", file_get_contents(__("sitemap_admin.html")), $currcontent);
        }
        setContentFromString($content, $currcontent);
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
