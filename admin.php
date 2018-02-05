<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        if (isset($_POST['editUser'])){
                header("Location: searchEditUser.php");
        }
        if (isset($_POST['editRoomBooking'])){
                header("Location: roomBookSearch.php");
        }
        if (isset($_POST['editInstrumentBooking'])){
                header("Location: instrumentBookSearch.php");
        }
        if (isset($_POST['editRooms'])){
                header("Location: searchEditRoom.php");
        }
        if (isset($_POST['newRoom'])){
                header("Location: aggiungiSala.php");
        }
        if (isset($_POST['addInstruments'])){
                header("Location: aggiungiStrumentazione.php");
        }
        if (isset($_POST['editInstruments'])){
                header("Location: searchEditInstruments.php");
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
        $statusline = "";
        if (isset($_SESSION['statussuccess'])){
                if ($_SESSION['statussuccess']==true){
                        $statusline="<div class='statussuccess'>" . $_SESSION['statusmessage'] . "</div>";
                }else{
                        $statusline="<div class='statusfailed'>" . $_SESSION['statusmessage'] . "</div>";
                }
                        unset($_SESSION['statussuccess']);
                        unset($_SESSION['statusmessage']);
        }
        $replaced = str_replace("<!--STATUS-->", $statusline, $replaced);
        echo ($replaced);
?>
