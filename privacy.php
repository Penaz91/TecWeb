<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents("struttura.html");

        setTitle($content, "Home");
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR . "style_privacy.css", $content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Privacy Policy", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, -1);
        setContentFromFile($content, __("contenuto_privacy.html"));
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
