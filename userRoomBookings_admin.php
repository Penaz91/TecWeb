<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents("struttura.html");

        setTitle($content, "Prenotazioni di ".$_GET['id']);
        addMobileStylesheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile_admin.css", $content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Amministratore", "admin.php");
        addBreadcrumb($content, "Ricerca e Modifica Utente", "searchEditUser.php");
        addBreadcrumb($content, "Prenotazioni Sale", "");
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);

        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $result = $dbAccess->checkUserBookings($_GET['id']);
                $table = file_get_contents("roomSearchTable.html");
                $rows = "";
                for ($i = 0; $i < count($result['Room']); $i++){
                        $rows = $rows . "<tr>";
                        $rows = $rows . "<td scope=\"row\">" . $result['Room'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Service'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Date'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Time'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Duration'][$i] . " Ore </td>";
                        $rows = $rows . "<td> <a href='elimina_prenotazione_admin.php?id=" . $_GET['id'] . "&amp;sala=" . $result['Room'][$i] . "&amp;servizio=" . $result['Service'][$i] . "&amp;data=" . $result['Date'][$i] . "&amp;ora=" . $result['Time'][$i] . "'>" . getMessage("413") . "</a></td>";
                        $rows = $rows . "</tr>";
                }
                $table = str_replace("<!--RISULTATIRICERCA-->", $rows, $table);
                $dbAccess->closeDBConnection();
        }
        setContentFromString($content, $table);
        echo($content);
?>
