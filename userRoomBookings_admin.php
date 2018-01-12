<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;

        session_start();
        checkLoggedAdmin();
        $content = file_get_contents("struttura.html");

        setTitle($content, "Prenotazioni di ".$_GET['id']);
        addMobileStylesheet("CSS/style_mobile_admin.css", $content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Amministratore", "admin.php");
        addBreadcrumb($content, "Ricerca e Modifica Utente", "searchEditUser.php");
        addBreadcrumb($content, "Prenotazioni Sale", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, -1);
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $result = $dbAccess->checkUserBookings($_GET['id']);
                $table = file_get_contents("roomSearchTable.html");
                for ($i = 0; $i < count($result['Room']); $i++){
                        $rows = $rows . "<tr>";
                        $rows = $rows . "<td scope=\"row\">" . $result['Room'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Service'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Date'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Time'][$i] . "</td>";
                        $rows = $rows . "<td>" . $result['Duration'][$i] . " Ore </td>";
                        $rows = $rows . "<td> <a href='elimina_prenotazione.php?id=" . $_GET['id'] . "&sala=" . $result['Room'][$i] . "&servizio=" . $result['Service'][$i] . "&data=" . $result['Date'][$i] . "&ora=" . $result['Time'][$i] . "'>Elimina Prenotazione</a></td>";
                        $rows = $rows . "</tr>";
                }
                $table = str_replace("<!--RISULTATIRICERCA-->", $rows, $table);
        }
        setContentFromString($content, $table);
        echo($content);
?>
