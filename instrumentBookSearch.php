<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Search Or Edit an Instrumentation Rental");
        }else{
                setTitle($content, "Ricerca/Modifica Noleggio di Strumentazione");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Search Or Edit an Instrumentation Rental", "");
        }else{
                addBreadcrumb($content, "Pannello Amministrazione", "admin.php");
                addBreadcrumb($content, "Cerca/Modifica Noleggio di Strumentazione", "");
        }
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);

        setContentFromFile($content, __("contenuto_ricercaNoleggi.html"));
        if (isset($_POST['submit'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = array();
                        $errors = "<div class='statusfailed'>" . getMessage("233") . "<br />";
                        $hasErrors = false;
                        if ($_POST['tipo']=="nominativo"){
                                $result = $dbAccess->searchInstrumentationBookByName($_POST['cerca']);
                        }
                        if ($_POST['tipo']=="strum"){
                                $result = $dbAccess->searchInstrumentationBookByInstrument($_POST['cerca']);
                        }
                        if ($_POST['tipo']=="dataInizio"){
                                if (checkDateInput($_POST['cerca'])){
                                        $data = convertDateToISO($_POST['cerca']);
                                        $result = $dbAccess->searchInstrumentationBookBeganAfter($data);
                                }else{
                                        $hasErrors = true;
                                        $errors = $errors . $_SESSION['dateerrors'] . "</div>";
                                        unset($_SESSION['dateerrors']);
                                }
                        }
                        if ($_POST['tipo']=="dataFine"){
                                if (checkDateInput($_POST['cerca'])){
                                        $data = convertDateToISO($_POST['cerca']);
                                        $result = $dbAccess->searchInstrumentationBookEndedBefore($data);
                                }else{
                                        $hasErrors = true;
                                        $errors = $errors . $_SESSION['dateerrors'] . "</div>";
                                        unset($_SESSION['dateerrors']);
                                }
                        }
                        if ($_POST['tipo']=="durata"){
                                if (checkDurationInput($_POST['cerca'])){
                                        $result = $dbAccess->searchInstrumentationBookByDuration($_POST['cerca']);
                                }else{
                                        $hasErrors = true;
                                        $errors = $errors . $_SESSION['durationerrors'] . "</div>";
                                        unset($_SESSION['durationerrors']);
                                }
                        }
                        if ($hasErrors){
                                $content = str_replace("<!--STATUS-->", $errors, $content);
                        }
                        $resultcount = count($result['Cliente']);
                        if ($resultcount == 0){
                                $resrow = getMessage("400");
                        }
                        if ($resultcount == 1){
                                $resrow = getMessage("401");
                        }
                        if ($resultcount >= 2){
                                $resrow = getMessage("402") . $resultcount . getMessage("403");
                        }
                        $table = $resrow . file_get_contents(__("tabella_ricercaNoleggi.html"));
                        $tabcontent = "";
                        for ($i=0; $i<$resultcount; $i++){
                                $tabcontent = $tabcontent . "<tr>";
                                $tabcontent = $tabcontent . "<td scope='row'>" . $result['Cliente'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td scope='row'>" . $result['Strum'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['DataInizio'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['DataFine'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['Qty'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>" . $result['Durata'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td><a href='eliminaNoleggio_admin.php?c=" . $result['Cliente'][$i] . "&amp;amp;s=" . $result['Strum'][$i] . "&amp;di=" . $result['DataInizio'][$i] . "&amp;df=" . $result['DataFine'][$i] . "'>" . getMessage("412") ."</a></td>";
                                $tabcontent = $tabcontent . "</tr>";
                        }
                        $table = str_replace("<!--RISULTATORICERCA-->", $tabcontent, $table);
                        $content = str_replace("<!--RISULTATIRICERCA-->", $table, $content);
                        $dbAccess->closeDBConnection();
                }
        }
        echo($content);
?>
