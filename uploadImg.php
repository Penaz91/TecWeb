<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Upload an instrumentation picture");
        }else{
                setTitle($content, "Carica un'immagine di strumentazione");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Upload a picture", "");
        }else{
                addBreadcrumb($content, "Pannello Amministrativo", "admin.php");
                addBreadcrumb($content, "Carica un'immagine", "");
        }
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setContentFromFile($content, __("contenuto_upload.html"));
        $status = "";
        if (isset($_SESSION['statussuccess'])){
                if ($_SESSION['statussuccess']){
                        $status = "<div class='statussuccess'>";
                }else{
                        $status = "<div class='statusfailed'>";
                }
                $status = $status . $_SESSION['statusmessage'] . "</div>";
                unset($_SESSION['statussuccess']);
                unset($_SESSION['statusmessage']);
        }
        $content = str_replace("<!--STATUS-->", $status, $content);
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
