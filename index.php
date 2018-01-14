<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        $content = file_get_contents(__("struttura.html"));

        setTitle($content, "Home");
        addScreenStylesheet("CSS/style_home.css", $content);
        initBreadcrumbs($content, "Home", "");
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, "index.php");
        setContentFromFile($content, __("contenuto_home.html"));
        echo($content);
?>
