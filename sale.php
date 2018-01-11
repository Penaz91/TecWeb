<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        $content = file_get_contents("struttura.html");

        setTitle($content, "Cosa Offriamo");
        addScreenStylesheet("CSS/style_offerta.css", $content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Sale", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 1);
        setContentFromFile($content, "contenuto_cosaoffriamo.html");
        echo($content);
?>
