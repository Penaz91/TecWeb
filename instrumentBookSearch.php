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
        setLoadScript($content, "");
        setContentFromFile($content, __("contenuto_ricercaNoleggi.html"));
        if (isset($_POST['submit'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = array();
                        if ($_POST['tipo']=="nominativo"){
                                $result = $dbAccess->searchInstrumentationBookByName($_POST['cerca']);
                        }
                        if ($_POST['tipo']=="strum"){
                                $result = $dbAccess->searchInstrumentationBookByInstrument($_POST['cerca']);
                        }
                        if ($_POST['tipo']=="dataInizio"){
                                //FIXME: Richiede controllo di formato
                                $result = $dbAccess->searchInstrumentationBookBeganAfter($_POST['cerca']);
                        }
                        if ($_POST['tipo']=="dataFine"){
                                //FIXME: Richiede controllo di formato
                                $result = $dbAccess->searchInstrumentationBookEndedBefore($_POST['cerca']);
                        }
                        if ($_POST['tipo']=="durata"){
                                $result = $dbAccess->searchInstrumentationBookByDuration($_POST['cerca']);
                        }
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
                                $tabcontent = $tabcontent . "<td>" . $result['Durata'][$i] . "</td>" ;
                                $tabcontent = $tabcontent . "<td>Niente</td>";
                                $tabcontent = $tabcontent . "</tr>";
                        }
                        $table = str_replace("<!--RISULTATORICERCA-->", $tabcontent, $table);
                        $content = str_replace("<!--RISULTATIRICERCA-->", $table, $content);
                }
        }
        echo($content);
?>
