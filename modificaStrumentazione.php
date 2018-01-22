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
                        //FIXME: Controllo di formato
                        $qresult = $dbAccess->editInstrument($_SESSION['instid'], $_POST['Nome'], $_POST['Costo'], $_POST['Desc'], $_POST['Disp'], $_POST['imgname']);
                        if ($qresult == true){
                                $_SESSION['statussuccess'] = true;
                                $_SESSION['statusmessage'] = "Strumentazione modificata con successo";
                        }else{
                                $_SESSION['statussuccess'] = false;
                                $_SESSION['statusmessage'] = "Impossibile Modificare la Strumentazione";
                        }
                        header("Location: searchEditInstruments.php");
                        exit();
                }else{
                        if (isset($_GET['id'])){
                                $_SESSION['instid'] = $_GET['id'];
                        }
                        $result = $dbAccess->searchInstrumentByNameExact($_GET['id']);
                        $struct = str_replace("<!--VALORENOME-->", $result['Nom'], $struct);
                        $struct = str_replace("<!--VALORECOSTO-->", $result['Cost'], $struct);
                        $struct = str_replace("<!--VALOREDESC-->", $result['Desc'], $struct);
                        $struct = str_replace("<!--VALOREDISP-->", $result['Qty'], $struct);
                        $struct = str_replace("<!--VALOREIMG-->", $result['Img'], $struct);
                }
                $dbAccess->closeDBConnection();
        }
        setContentFromString($content, $struct);
        echo ($content);

?>
