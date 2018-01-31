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
        fullSetupMenu($content, 2, true);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);

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
        if (isset($_POST['dataInizio'])){
                $content = str_replace("<!--VALOREDI-->", $_POST['dataInizio'], $content);
        }else{
                $content = str_replace("<!--VALOREDI-->", "", $content);
        }
        if (isset($_POST['dataFine'])){
                $content = str_replace("<!--VALOREDF-->", $_POST['dataFine'], $content);
        }else{
                $content = str_replace("<!--VALOREDF-->", "", $content);
        }
        if (isset($_POST['qty'])){
                $content = str_replace("<!--VALOREQTY-->", $_POST['qty'], $content);
        }else{
                $content = str_replace("<!--VALOREQTY-->", "", $content);
        }
        if (isset($_POST['verifica'])){
                $errori = "Ci sono errori nei dati inseriti:";
                $diOK = checkDateInput($_POST['dataInizio']);
                $diErr = "Data Inizio Noleggio: ";
                $dfErr = "Data Fine Noleggio: ";
                if (!$diOK){
                        $diErr = $diErr . $_SESSION['dateerrors'];
                        unset($_SESSION['dateerrors']);
                        $errori = $errori . "<br/>" . '<a href="#dataInizio" class="errorlink" title="Vai al campo Data Inizio (Sorgente dell\'errore)">' . $diErr . '</a>';
                }
                $dfOK = checkDateInput($_POST['dataFine']);
                if (!$dfOK){
                        $dfErr = $dfErr . $_SESSION['dateerrors'];
                        unset($_SESSION['dateerrors']);
                        $errori = $errori . "<br/>" . '<a href="#dataFine" class="errorlink" title="Vai al campo Data Fine (Sorgente dell\'errore)">' . $dfErr . '</a>';
                }
                $qtyOK = checkQtyInput($_POST['qty']);
                if (!$qtyOK){
                        $errori = $errori . "<br/>" . '<a href="#qty" class="errorlink" title="Vai al campo Quantità (Sorgente dell\'errore)">' . $_SESSION['qtyErrors'] . "</a>";
                        unset($_SESSION['qtyErrors']);
                }
                if ($diOK && $dfOK && $qtyOK && $datesOk){
                        $isoDI = convertDateToISO($_POST['dataInizio']);
                        $isoDF = convertDateToISO($_POST['dataFine']);
                        $datesOk = checkDateOrder($isoDI, $isoDF);
                        if (!$datesOk){
                                $errori = $errori . '<br/><a href="#dataInizio" class="errorlink" title="Vai al campo Data Inizio (Possibile sorgente dell\'errore)">La data d\'inizio noleggio riporta un valore uguale o successivo a quella di fine noleggio.</a>';
                        }
                        $result = $dbAccess->checkAvailability($_POST['strum'], $isoDI, $isoDF);
                        if ($_POST['qty'] <= $result){
                                $form2 = file_get_contents(__("verificaDisp_parte2.html"));
                                $content = str_replace("<!--ALTRAFORM-->", $form2, $content);
                        }else{
                                $errori = '<div class="statusfailed"><a href="#qty" class="errorlink" title="Vai al campo quantità (Sorgente dell\'errore)">Non ci sono abbastanza pezzi disponibili. Sono disponibili solo '. $result . ' pezzi.</a></div>';
                                $content = str_replace("<!--STATUS-->", $errori, $content);
                        }
                }else{
                        $errori = "<div class='statusfailed'>" . $errori . "</div>";
                        $content = str_replace("<!--STATUS-->", $errori, $content);
                }
        }
        if (isset($_POST['noleggia'])){
                $result = $dbAccess->newRental($_SESSION['username'], $_POST['strum'], convertDateToISO($_POST['dataInizio']), convertDateToISO($_POST['dataFine']), $_POST['qty']);
                if ($result==""){
                        $status = "<div class='statussuccess'>Noleggio avvenuto correttamente</div>";
                }else{
                        $errori = "<div class='statusfailed'>" . $result . "</div>";
                        $content = str_replace("<!--STATUS-->", $errori, $content);

                }
        }
        $dbAccess->closeDBConnection();
        $content = str_replace("<!--ELENCOSTRUMENTI-->", $instrlist, $content);
        echo($content);
?>
