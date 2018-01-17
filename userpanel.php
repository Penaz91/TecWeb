<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUserAndRedirect("userpanel.php");
        if (isset($_POST['editUser'])){
                header("Location: userpanel_account.php");
        }
        if (isset($_POST['editRoom'])){
                header("Location: userRoomBookings.php");
        }
        if (isset($_POST['editInstruments'])){
                //header("Location: searchEditInstruments.php");
        }
        $content = file_get_contents("struttura.html");
        setTitle($content, "Pannello Utente");
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Utente", "");
        setLoadScript($content, "");
        $userpanel = file_get_contents("struttura_userpanel.html");
        $torepl = "<!--USER-->";
        $userpanel = str_replace($torepl, $_SESSION['username'], $userpanel);
        $torepl = "<!--CONTENUTO-->";
        $replaced = str_replace($torepl, $userpanel, $content);
        echo ($replaced);
?>
