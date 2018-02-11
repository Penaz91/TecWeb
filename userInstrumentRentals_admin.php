<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));

        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, $_GET['id'] . "'s Rentals");
                addBreadcrumb($content, "User Panel", "userpanel.php");
                addBreadcrumb($content, "Search or Edit an user", "searchEditUser.php");
                addBreadcrumb($content, "Rentals", "");
        }else{
                setTitle($content, "I noleggi di " . $_GET['id']);
                addBreadcrumb($content, "Pannello Utente", "userpanel.php");
                addBreadcrumb($content, "Ricerca e Modifica Utente", "searchEditUser.php");
                addBreadcrumb($content, "Noleggi", "");
        }
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);

        if (isset($_GET['id'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                $table = file_get_contents(__("tabella_ricercaNoleggi.html"));
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = $dbAccess->searchInstrumentationBookByName($_GET['id']); //garantito dal controllo accesso
                        $resultcount = count($result['Cliente']);
                        $table = file_get_contents(__("tabella_ricercaNoleggi.html"));
                        $tabcontent = "";
                        for ($i=0; $i<$resultcount; $i++){
                                $tabcontent = $tabcontent . "<tr>";
                                $tabcontent = $tabcontent . "<td scope='row'>" . $result['Cliente'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td scope='row'>" . $result['Strum'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['DataInizio'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['DataFine'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['Qty'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['Durata'][$i] . ($result['Durata'][$i] == 1 ? getMessage("703") : getMessage("701")) . "</td>" ;
                                $tabcontent = $tabcontent . "<td><a href='eliminaNoleggio_admin.php?c=" . $result['Cliente'][$i] . "&amp;s=" . $result['Strum'][$i] . "&amp;di=" . $result['DataInizio'][$i] . "&amp;df=" . $result['DataFine'][$i] . "'>" . getMessage("412") . "</a></td>";
                                $tabcontent = $tabcontent . "</tr>";
                        }
                        $table = str_replace("<!--RISULTATORICERCA-->", $tabcontent, $table);
                        setContentFromString($content, $table);
                        $dbAccess->closeDBConnection();
                }
        }
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
