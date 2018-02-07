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
                header("Location: userInstrumentRentals.php");
        }
        $content = file_get_contents("struttura.html");
        setTitle($content, "Pannello Utente");
        setUserStatusFull($content, true);
        setupMenu($content, -1);
        setAdminArea($content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Utente", "");
        setLangArea($content, $_SERVER['PHP_SELF']);

        $userpanel = file_get_contents("struttura_userpanel.html");
        $torepl = "<!--USER-->";
        $userpanel = str_replace($torepl, $_SESSION['username'], $userpanel);
        $torepl = "<!--CONTENUTO-->";
        $replaced = str_replace($torepl, $userpanel, $content);
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
        $xml = new DOMDocument();
        $xml->loadHTML($replaced);
        setHTMLNameSpaces($xml);
        $replaced = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($replaced);

        echo ($replaced);
?>
