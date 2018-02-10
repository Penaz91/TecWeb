<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUserAndRedirect("userRoomBookings.php");
        $content = file_get_contents("struttura.html");

        if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                setTitle($content, "My Bookings");
                initBreadcrumbs($content, "Home", "index.php");
                addBreadcrumb($content, "User Panel", "userpanel.php");
                addBreadcrumb($content, "My Bookings", "");
        }else{
                setTitle($content, "Le mie prenotazioni di Sale");
                initBreadcrumbs($content, "Home", "index.php");
                addBreadcrumb($content, "Pannello Utente", "userpanel.php");
                addBreadcrumb($content, "Le mie Prenotazioni di sale", "");
        }
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
                $table = "";
                $rows = "";
                if (count($result['Room']) == 0){
                        $table = "<p>" . getMessage("404") . "</p><p><a href='prenotazione_sala.php' title='" . getMessage("117") . "'>" . getMessage("405") . "</a>";
                }else{
                        $table = file_get_contents(__("roomSearchTable.html"));
                        $rows = "";
                        for ($i = 0; $i < count($result['Room']); $i++){
                                $rows = $rows . "<tr>";
                                if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                                        $temp = $dbAccess->prim2sec($result['Room'][$i], $result['Service'][$i]);
                                        $rows = $rows . "<td scope=\"row\">" . $temp['name'][0] . "</td>";
                                        $rows = $rows . "<td>" . $temp['func'][0] . "</td>";
                                }else{
                                        $rows = $rows . "<td scope=\"row\">" . $result['Room'][$i] . "</td>";
                                        $rows = $rows . "<td>" . $result['Service'][$i] . "</td>";
                                }
                                $rows = $rows . "<td>" . $result['Date'][$i] . "</td>";
                                $rows = $rows . "<td>" . $result['Time'][$i] . "</td>";
                                $rows = $rows . "<td>" . $result['Duration'][$i] . ($result['Duration'][$i]==1 ? getMessage("702") : getMessage("700")) ."</td>";
                                $rows = $rows . "<td> <a href='elimina_prenotazione.php?id=" . $_SESSION['username'] . "&amp;sala=" . $result['Room'][$i] . "&amp;servizio=" . $result['Service'][$i] . "&amp;data=" . $result['Date'][$i] . "&amp;ora=" . $result['Time'][$i] . "'>" . getMessage("413") . "</a></td>";
                                $rows = $rows . "</tr>";
                                $dbAccess->closeDBConnection();
                        }
                }
                $table = str_replace("<!--RISULTATIRICERCA-->", $rows, $table);
        }
        setContentFromString($content, $table);
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
