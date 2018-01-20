<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));
        addMobileStylesheet("CSS" . DIRECTORY_SEPARATOR . __("style_mobile_admin.css"), $content);
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setLoadScript($content, "");
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                setTitle($content, "Search or Edit Instrumentation");
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Search or Edit Instrumentation", "");
        }else{
                setTitle($content, "Ricerca e Modifica Strumentazione");
                addBreadcrumb($content, "Pannello Amministrazione", "admin.php");
                addBreadcrumb($content, "Ricerca e Modifica Strumentazione", "");
        }
        $admpanel = file_get_contents(__("struttura_searchEditInstruments.html"));
        $torepl = "<!--RISULTATI-->";
        $cerca = "";
        if (!isset($_POST['submit'])){
                $repl = "Inserisci un termine da cercare e clicca sul bottone \"Cerca\"";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = array();
                        if ($_POST['tipo']=='Nome'){
                                $result = $dbAccess->searchInstrumentByName($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='Costo'){
                                $result = $dbAccess->searchInstrumentByCost($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='CostoOltre'){
                                $result = $dbAccess->searchInstrumentByCostMinimum($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='CostoMeno'){
                                $result = $dbAccess->searchInstrumentByCostMaximum($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='Disp'){
                                $result = $dbAccess->searchInstrumentByStock($_POST['cerca']);
                        }
                        $repl = file_get_contents(__("instrumentsearchtable.html"));
                        $tablecontent = "";
                        $ressize = count($result['Nom']);
                        if ($ressize==0){
                                $repl = "Il nostro personale Indiana Jones non ha trovato nulla";
                        }else{
                                if ($ressize == 1){
                                        $repl = "Il nostro personale Indiana Jones ha trovato un Risultato" . $repl;
                                }else{
                                        $repl = "Il nostro personale Indiana Jones ha trovato $ressize Risultati" . $repl;
                                }
                                for ($i = 0; $i < $ressize; $i++) {
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope='row'>" . $result['Nom'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Cost'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Desc'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Img'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Qty'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>";
                                        $tablecontent = $tablecontent . "<a href='modificaStrumentazione.php?id=" . $result['Nom'][$i] . "'>Modifica Strumentazione</a><br />";
                                        $tablecontent = $tablecontent . "<a href='elimina_strumentazione.php?id=" . $result['Nom'][$i] . "'>Elimina Strumentazione</a>";
                                        $tablecontent = $tablecontent . "</td>";
                                        $tablecontent = $tablecontent . "</tr>";
                                }
                        }
                        $repl = str_replace("<!--RISULTATIRICERCA-->", $tablecontent, $repl);
                        $dbAccess->closeDBConnection();
                }
        }
        $admpanel = str_replace("<!--VALORECERCA-->", $cerca, $admpanel);
        $status = "";
        if(isset($_SESSION['statussuccess'])){
                if ($_SESSION['statussuccess']==true){
                        $status = "<div id='statussuccess'>" . $_SESSION['statusmessage'] . "</div>";
                }else{
                        $status = "<div id='statusfailed'>" . $_SESSION['statusmessage'] . "</div>";
                }
                unset($_SESSION['statussuccess']);
                unset($_SESSION['statusmessage']);
        }
        $admpanel = str_replace("<!--STATUS-->", $status, $admpanel);
        $admpanel = str_replace($torepl, $repl, $admpanel);
        setContentFromString($content, $admpanel);
        echo ($content);

?>
