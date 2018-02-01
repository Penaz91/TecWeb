<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUserAndRedirect("userRoomBookings.php");
        $content = file_get_contents("struttura.html");

        setTitle($content, "Le mie prenotazioni di Sale");
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Utente", "userpanel.php");
        addBreadcrumb($content, "Le mie Prenotazioni di sale", "");
        addMobileStylesheet("CSS" . DIRECTORY_SEPARATOR . __("style_mobile_admin.css"), $content);
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);

        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $result = $dbAccess->checkUserBookings($_SESSION['username']);
                $table = file_get_contents("roomSearchTable.html");
                $rows = "";
                for ($i = 0; $i < count($result['Room']); $i++){
                        $rows = $rows . "<tr>";
                        $rows = $rows . "<td scope=\"row\">" . $result['Room'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Service'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Date'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Time'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Duration'][$i] . " Ore </td>";
                        $rows = $rows . "<td> <a href='elimina_prenotazione.php?id=" . $_SESSION['username'] . "&amp;sala=" . $result['Room'][$i] . "&amp;servizio=" . $result['Service'][$i] . "&amp;data=" . $result['Date'][$i] . "&amp;ora=" . $result['Time'][$i] . "'>" . getMessage("412") . "</a></td>";
                        $rows = $rows . "</tr>";
                        $dbAccess->closeDBConnection();
                }
                $table = str_replace("<!--RISULTATIRICERCA-->", $rows, $table);
        }
        setContentFromString($content, $table);
        echo($content);
?>
