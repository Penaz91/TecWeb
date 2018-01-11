<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        $content = file_get_contents("struttura.html");

        setTitle($content, "Contatti");
        addScreenStylesheet("CSS/style_contatti.css", $content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Contatti", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 4);
        setContentFromFile($content, "contenuto_contatti.html");
        echo($content);
?>
