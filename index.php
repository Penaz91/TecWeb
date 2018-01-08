<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        $content = file_get_contents("struttura.html");

        setTitle($content, "Home");
        initBreadcrumbs($content, "Home", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 0);
        setContentFromFile($content, "contenuto_home.html");
        echo($content);
?>
