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
                $repl = "";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = array("Nom" => array());
                        $_SESSION['statusmessage'] = getMessage("230") . "<br />";
                        if ($_POST['tipo']=='Nome'){
                                $result = $dbAccess->searchInstrumentByName($_POST['cerca']);
                        }
                        if ($_POST['tipo']=='Costo'){
                                if (checkMoneyInput($_POST['cerca'])){
                                        $result = $dbAccess->searchInstrumentByCost($_POST['cerca']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION["statusmessage"] = $_SESSION["statusmessage"] . '<a href="#cerca" title="' . getMessage("109") . '">' . $_SESSION["moneyErrors"] . "</a>";
                                }
                        }
                        if ($_POST['tipo']=='CostoOltre'){
                                if (checkMoneyInput($_POST['cerca'])){
                                        $result = $dbAccess->searchInstrumentByCostMinimum($_POST['cerca']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION["statusmessage"] = $_SESSION["statusmessage"] . '<a href="#cerca" title="' . getMessage("109") . '">' . $_SESSION["moneyErrors"] . "</a>";
                                }
                        }
                        if ($_POST['tipo']=='CostoMeno'){
                                if (checkMoneyInput($_POST['cerca'])){
                                        $result = $dbAccess->searchInstrumentByCostMaximum($_POST['cerca']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION["statusmessage"] = $_SESSION["statusmessage"] . '<a href="#cerca" title="' . getMessage("109") . '">' . $_SESSION["moneyErrors"] . "</a>";
                                }
                        }
                        if ($_POST['tipo']=='Disp'){
                                if (checkQtyInput($_POST['cerca'])){
                                        $result = $dbAccess->searchInstrumentByStock($_POST['cerca']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION["statusmessage"] = $_SESSION["statusmessage"] . '<a href="#cerca" title="' . getMessage("109") . '">' . $_SESSION["qtyErrors"] . "</a>";
                                }
                        }
                        $repl = file_get_contents(__("instrumentsearchtable.html"));
                        $tablecontent = "";
                        $ressize = count($result['Nom']);
                        if ($ressize==0){
                                $repl = getMessage("400");
                        }else{
                                if ($ressize == 1){
                                        $repl = getMessage("401") . $repl;
                                }else{
                                        $repl = getMessage("402") . $ressize . getMessage("403") . $repl;
                                }
                                for ($i = 0; $i < $ressize; $i++) {
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope='row'>" . $result['Nom'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Cost'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Desc'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Img'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['ImgAlt'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $result['Qty'][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>";
                                        $tablecontent = $tablecontent . "<a href='modificaStrumentazione.php?id=" . $result['Nom'][$i] . "'>" . getMessage("410") . "</a><br />";
                                        $tablecontent = $tablecontent . "<a href='elimina_strumentazione.php?id=" . $result['Nom'][$i] . "'>" . getMessage("411") ."</a>";
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
                        $status = "<div class='statussuccess'>" . $_SESSION['statusmessage'] . "</div>";
                }else{
                        $status = "<div class='statusfailed'>" . $_SESSION['statusmessage'] . "</div>";
                }
                unset($_SESSION['statussuccess']);
                unset($_SESSION['statusmessage']);
        }
        $admpanel = str_replace("<!--STATUS-->", $status, $admpanel);
        $admpanel = str_replace($torepl, $repl, $admpanel);
        setContentFromString($content, $admpanel);
        $cerca = "";
        $tsearch = "";
        $err = false;
        if (isset($_POST['cerca'])){
                $cerca = $_POST['cerca'];
        }
        if (isset($_POST['tipo'])){
                $tsearch= $_POST['tipo'];
        }
        if (isset($_SESSION['moneyErrors'])){
                $err = $err || $_SESSION['moneyErrors'];
                unset($_SESSION['moneyErrors']);
        }
        if (isset($_SESSION['qtyErrors'])){
                $err = $err || $_SESSION['qtyErrors'];
                unset($_SESSION['qtyErrors']);
        }
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        prefillAndHighlight("cerca", $err, $xml, $cerca);
        preSelect("tipo", $tsearch, $xml);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo ($content);
?>
