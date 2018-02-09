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
                setTitle($content, "Edit Instrumentation");
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Edit Instrumentation", "");
        }else{
                setTitle($content, "Modifica Strumentazione");
                addBreadcrumb($content, "Pannello Amministrazione", "admin.php");
                addBreadcrumb($content, "Modifica Strumentazione", "");
        }
        $struct = file_get_contents(__("contenuto_modificaStrumenti.html"));
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                if (isset($_POST['modifica'])){
                        $hasErrors = false;
                        $CostOk = checkMoneyInput($_POST['Costo']);
                        $DispOk = checkQtyInput($_POST['Disp']);
                        if ($DispOk && $CostOk){
                                $qresult = $dbAccess->editInstrument($_SESSION['instid'], $_POST['Nome'], $_POST['Costo'], $_POST['Desc'], $_POST['Disp'], $_POST['imgname'], $_POST['imgalt'], $_POST['EngDesc'], $_POST['EngAlt']);
                        }else{
                                $hasErrors = true;
                        }
                        if ($qresult!=""){
                                $_SESSION['statussuccess'] = false;
                                $_SESSION['statusmessage'] = getMessage($qresult);
                        }
                        if (!$hasErrors){
                                $_SESSION['statussuccess'] = true;
                                $_SESSION['statusmessage'] = getMessage("15");
                        }else{
                                $_SESSION['statussuccess'] = false;
                                $_SESSION['statusmessage'] = getMessage("219");
                                if (isset($_SESSION['moneyErrors'])){
                                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br /><a href='#Costo' title='" . getMessage("111") . "'>" . $_SESSION['moneyErrors'] . "</a>";
                                        unset($_SESSION['moneyErrors']);
                                }
                                if (isset($_SESSION['qtyErrors'])){
                                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br /><a href='#Disp' title='" . getMessage("110") . "'>" . $_SESSION['qtyErrors'] . "</a>";
                                        unset($_SESSION["qtyErrors"]);
                                }
                        }
                        header("Location: searchEditInstruments.php");
                        exit();
                }else{
                        if (isset($_GET['id'])){
                                $_SESSION['instid'] = $_GET['id'];
                        }
                        $result = $dbAccess->searchInstrumentByNameExact($_GET['id']);
                        $struct = str_replace("<!--VALORENOME-->", $result['Nom'][0], $struct);
                        $struct = str_replace("<!--VALORECOSTO-->", $result['Cost'][0], $struct);
                        $struct = str_replace("<!--VALOREDESC-->", $result['Desc'][0], $struct);
                        $struct = str_replace("<!--VALOREDESC_EN-->", $result['EngDesc'][0], $struct);
                        $struct = str_replace("<!--VALOREDISP-->", $result['Qty'][0], $struct);
                        $struct = str_replace("<!--VALOREIMG-->", $result['Img'][0], $struct);
                        $struct = str_replace("<!--VALOREALT-->", $result['ImgAlt'][0], $struct);
                        $struct = str_replace("<!--VALOREENGALT-->", $result['EngAlt'][0], $struct);
                }
                $dbAccess->closeDBConnection();
        }
        setContentFromString($content, $struct);
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo ($content);

?>
