<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        session_start();
        checkLoggedAdmin();
        if (isset($_POST['editUser'])){
                header("Location: searchEditUser.php");
        }
        if (isset($_POST['editRoomBooking'])){
                header("Location: roomBookSearch.php");
        }
        if (isset($_POST['editInstrumentBooking'])){
                header("Location: searchEditInstruments.php");
        }
        if (isset($_POST['editRooms'])){
                header("Location: editRooms_admin.php");
        }
        if (isset($_POST['newRoom'])){
                header("Location: aggiungiSala.php");
        }
        if (isset($_POST['editInstruments'])){
                header("Location: editInstruments_admin.php");
        }
        $content = file_get_contents("struttura.html");
        setTitle($content, "Pannello Amministrazione");
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Amministrazione", "");
        $admpanel = file_get_contents("struttura_adminpanel.html");
        $torepl = "<!--USER-->";
        $admpanel = str_replace($torepl, $_SESSION['username'], $admpanel);
        $torepl = "<!--CONTENUTO-->";
        $replaced = str_replace($torepl, $admpanel, $content);
        echo ($replaced);
?>
