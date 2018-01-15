<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;


        session_start();
        $content = file_get_contents(__("struttura.html"));

        if ($_SESSION['language']=='en'){
                //Lingua Inglese
                setTitle($content, "Booked Rooms - Admin Panel");
        }else{
                //Italiano
                setTitle($content, "Prenotazioni Sale - Pannello Amministratore");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if ($_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Booked Rooms", "");
        }else{
                addBreadcrumb($content, "Pannello di Amministrazione", "admin.php");
                addBreadcrumb($content, "Prenotazioni Sale", "");
        }
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setContentFromFile($content, __("contenuto_ricercaPrenotazioni.html"));
        if (isset($_POST['submit'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        if ($_POST['tipo']=='nominativo'){
                                $results = $dbAccess->checkBookingsByName($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='data'){
                                $validdate = checkDateInput($_POST['cerca']);
                                if ($validdate==false){
                                        $content = str_replace("<!--STATUS-->", "<div id='statusfailed'>" . $_SESSION['dateerrors'] . '</div>', $content);
                                        unset($_SESSION['dateerrors']);
                                }else{
                                        $data = DateTime::createFromFormat("d/m/Y", $_POST['cerca']);
                                        $data = $data->format("Ymd");
                                        $results = $dbAccess->checkBookingsByDate($data);
                                }
                        }
                        if ($_POST['tipo']=='sala'){
                                $results = $dbAccess->checkBookingsByRoom($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='funzione'){
                                $results = $dbAccess->checkBookingsByService($_POST['cerca']);
                        }
                        $ressize = count($results['Nom']);
                        if (empty($results) || $ressize==0){
                                $tabella = "Il nostro personale Indiana Jones non ha trovato nulla";
                        }else{
                                $tabella = file_get_contents(__("contenuto_risultatiprenotazioni.html"));
                                if ($ressize == 1){
                                        $tablecontent = "Il nostro personale Indiana Jones ha trovato un Risultato" . $tablecontent;
                                }else{
                                        $tablecontent = "Il nostro personale Indiana Jones ha trovato $ressize Risultati" . $tablecontent;
                                }
                                for ($i=0; $i<$ressize; $i++){
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope='row'>" . $results['Nom'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Room'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Func'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Data'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Ora'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Dur'][$i] . ($results['Ora']==1 ? " Ora" : " Ore") .'</td>';
                                        $tablecontent = $tablecontent . "<td>Nessuna Disponibile al momento</td>";
                                        $tablecontent = $tablecontent . "</tr>";
                                }
                                $tabella = str_replace("<!--RISULTATI-->", $tablecontent, $tabella);
                        }
                }
        }
        $content = str_replace("<!--RISULTATIRICERCA-->", $tabella, $content);
        echo($content);
?>
