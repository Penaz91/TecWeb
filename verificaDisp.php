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
                $dbAccess->closeDBConnection();
                }
        }
        if (isset($_POST['verifica'])){
                //FIXME: Richiede controllo di formato
                $errori = "Ci sono errori nei dati inseriti:";
                $diOK = checkDateInput($_POST['dataInizio']);
                if (!$diOK){
                        $diErr = $diErr . $_SESSION['dateerrors'];
                        $errori = $errori . "<br/>" . $diErr;
                }
                $dfOK = checkDateInput($_POST['dataFine']);
                if (!$dfOK){
                        $dfErr = $dfErr . $_SESSION['dateerrors'];
                        $errori = $errori . "<br/>" . $dfErr;
                }
                $qtyOK = checkQtyInput($_POST['qty']);
                if (!$qtyOK){
                        $errori = $errori . "<br/>" . $_SESSION['qtyErrors'];
                }
                $diErr = "Data Inizio Noleggio: ";
                $dfErr = "Data Fine Noleggio: ";
                if ($diOK && $dfOK && $qtyOK){
                        $result = $dbAccess->checkAvailability($_POST['strum'], convertDateToISO($_POST['dataInizio']), convertDateToISO($_POST['dataFine']));
                }else{
                        //TODO Piazzare errori nello status
                }
        }
        $content = str_replace("<!--ELENCOSTRUMENTI-->", $instrlist, $content);
        echo($content);
?>
