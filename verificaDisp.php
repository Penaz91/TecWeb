<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        checkLoggedUserAndRedirect($_SERVER['PHP_SELF']);
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Instrumentation Rental", "noleggio.php");
                addBreadcrumb($content, "Check Availability", "");
                setTitle($content, "Check Instrumentation Availability");
        }else{
                addBreadcrumb($content, "Noleggio Strumentazione", "noleggio.php");
                addBreadcrumb($content, "Verifica Disponibilità", "");
                setTitle($content, "Verifica Disponibilità Strumentazione");
        }
        setUserStatus($content);
        setupMenu($content, 2);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setLoadScript($content, "");
        setContentFromFile($content, __("contenuto_verificaDisp.html"));
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        $instrlist = "";
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $instr = $dbAccess->getInstrumentationList();
                $instrcount = count($instr['Nome']);
                for ($i=0; $i < $instrcount; $i++){
                        if (isset($_POST['strum']) && $instr['Nome'][$i]==$_POST['strum']){
                                $instrlist = $instrlist . "<option selected='selected' value='" . $instr['Nome'][$i] . "'>" . $instr['Nome'][$i] . "</option>";
                        }else{
                                $instrlist = $instrlist . "<option value='" . $instr['Nome'][$i] . "'>" . $instr['Nome'][$i] . "</option>";
                        }
                }
        }
        if (isset($_POST['verifica'])){
                $result = $dbAccess->checkAvailability($_POST['strum'], $_POST['dataInizio'], $_POST['dataFine']);
        }
        $content = str_replace("<!--ELENCOSTRUMENTI-->", $instrlist, $content);
        echo($content);
?>
