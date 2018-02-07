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
        $diOk = true;
        $dfOk = true;
        $qtyOK = true;

        if (isset($_POST['verifica'])){
                $errori = getMessage("212");
                $diOK = checkDateInput($_POST['dataInizio']);
                $diErr = getMessage("200");
                $dfErr = getMessage("201");
                $datesOk = false;
                if (!$diOK){
                        $diErr = $diErr . $_SESSION['dateerrors'];
                        unset($_SESSION['dateerrors']);
                        $errori = $errori . "<br/>" . '<a href="#dataInizio" class="errorlink" title="'. getMessage("100") . '">' . $diErr . '</a>';
                }
                $dfOK = checkDateInput($_POST['dataFine']);
                if (!$dfOK){
                        $dfErr = $dfErr . $_SESSION['dateerrors'];
                        unset($_SESSION['dateerrors']);
                        $errori = $errori . "<br/>" . '<a href="#dataFine" class="errorlink" title="' . getMessage("101") . '">' . $dfErr . '</a>';
                }
                $qtyOK = checkQtyInput($_POST['qty']);
                if (!$qtyOK){
                        $errori = $errori . "<br/>" . '<a href="#qty" class="errorlink" title="' . getMessage("102") . '">' . $_SESSION['qtyErrors'] . "</a>";
                        unset($_SESSION['qtyErrors']);
                }
                if ($diOK && $dfOK){
                        $isoDI = convertDateToISO($_POST['dataInizio']);
                        $isoDF = convertDateToISO($_POST['dataFine']);
                        $datesOk = checkDateOrder($isoDI, $isoDF);
                }
                if (!$datesOk){
                        $errori = $errori . '<br/><a href="#dataInizio" class="errorlink" title="' . getMessage("103") . '">' . getMessage("213") . '</a>';
                }
                if ($diOK && $dfOK && $qtyOK && $datesOk){
                        $result = $dbAccess->checkAvailability($_POST['strum'], $isoDI, $isoDF);
                        if ($_POST['qty'] <= $result){
                                $form2 = file_get_contents(__("verificaDisp_parte2.html"));
                                $content = str_replace("<!--ALTRAFORM-->", $form2, $content);
                        }else{
                                $errori = $errori . '<br /><a href="#qty" class="errorlink" title="' . getMessage("102") . '">'. getMessage("214") . $result . getMessage("215") .'</a>';
                                $content = str_replace("<!--STATUS-->", "<div class='statusfailed'>" . $errori . "</div>", $content);
                        }
                }else{
                        $errori = "<div class='statusfailed'>" . $errori . "</div>";
                        $content = str_replace("<!--STATUS-->", $errori, $content);
                }
        }
        if (isset($_POST['noleggia'])){
                $result = $dbAccess->newRental($_SESSION['username'], $_POST['strum'], convertDateToISO($_POST['dataInizio']), convertDateToISO($_POST['dataFine']), $_POST['qty']);
                if ($result==""){
                        $status = "<div class='statussuccess'>" . getMessage("10") . "</div>";
						$content = str_replace("<!--STATUS-->", $status, $content);
                }else{
                        $errori = "<div class='statusfailed'>" . getMessage($result) . "</div>";
                        $content = str_replace("<!--STATUS-->", $errori, $content);

                }
        }
        $di = "";
        $df = "";
        $qty = "";
        if (isset($_POST['dataInizio'])){
                $di = $_POST['dataInizio'];
        }
        if (isset($_POST['dataFine'])){
                $df = $_POST['dataFine'];
        }
        if (isset($_POST['qty'])){
                $qty = $_POST['qty'];
        }
        $dbAccess->closeDBConnection();
        $content = str_replace("<!--ELENCOSTRUMENTI-->", $instrlist, $content);
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        prefillAndHighlight("dataInizio", !$diOk, $xml, $di);
        prefillAndHighlight("dataFine", !$dfOk, $xml, $df);
        prefillAndHighlight("qty", !$qtyOK, $xml, $qty);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
