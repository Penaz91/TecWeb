<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Booked Rooms - Admin Panel");
        }else{
                setTitle($content, "Prenotazioni Sale - Pannello Amministratore");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
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

        addMobileStylesheet("CSS" . DIRECTORY_SEPARATOR . __("style_mobile_admin.css"), $content);
        setContentFromFile($content, __("contenuto_ricercaPrenotazioni.html"));
        $tabella = "";
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
                                        $content = str_replace("<!--STATUS-->", "<div class='statusfailed'><a href='#cerca' title='" . getMessage("109") . "'>" . $_SESSION['dateerrors'] . '</a></div>', $content);
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
                        $tablecontent = "";
                        if (empty($results) || $ressize==0){
                                $tabella = "<p>" . getMessage("400") . "</p>";
                        }else{
                                $tabella = file_get_contents(__("contenuto_risultatiprenotazioni.html"));
                                if ($ressize == 1){
                                        $tabella = "<p>" . getMessage("401") . "</p>" . $tabella;
                                }else{
                                        $tabella = "<p>" . getMessage("402") . $ressize . getMessage("403") . "</p>" . $tabella;
                                }
                                for ($i=0; $i<$ressize; $i++){
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope='row'>" . $results['Nom'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Room'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Func'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Data'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Ora'][$i] . '</td>';
                                        $tablecontent = $tablecontent . "<td>" . $results['Dur'][$i] . ($results['Ora']==1 ? " Ora" : " Ore") .'</td>';
                                        $tablecontent = $tablecontent . "<td> <a href='elimina_prenotazione_admin.php?id=" . $results['Nom'][$i] . "&amp;sala=" . $results['Room'][$i] . "&amp;servizio=" . $results['Func'][$i] . "&amp;data=" . $results['Data'][$i] . "&amp;ora=" . $results['Ora'][$i] . "'>" . getMessage("413") ."</a></td>";
                                        $tablecontent = $tablecontent . "</tr>";
                                }
                                $tabella = str_replace("<!--RISULTATI-->", $tablecontent, $tabella);
                        }
                $dbAccess->closeDBConnection();
                }
        }
        $content = str_replace("<!--RISULTATIRICERCA-->", $tabella, $content);
        echo($content);
?>
