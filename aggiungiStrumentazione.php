<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Add Instrumentation");
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Add Instrumentation", "");
        }else{
                setTitle($content, "Aggiungi Nuova Strumentazione");
                addBreadcrumb($content, "Pannello Amministrativo", "admin.php");
                addBreadcrumb($content, "Aggiungi Strumentazione", "");
        }
        setUserStatus($content);
        setupMenu($content, 0);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setContentFromFile($content, __("struttura_aggiungiStrumentazione.html"));
        $nome = "";
        $costo = "";
        $desc = "";
        $disp = "";
        $imgname = "";
        $imgalt = "";
        if (isset($_POST['inserimento'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $moneyCheck = checkMoneyInput($_POST['Costo']);
                        $dispCheck = checkQtyInput($_POST['Disp']);
                        $formatCheck = checkFileFormatInput($_POST['imgname']);
                        if ($moneyCheck && $dispCheck && $formatCheck){
                                $result = $dbAccess->insertInstrument($_POST['NomeS'], $_POST['Costo'], $_POST['Desc'], $_POST['Disp'], $_POST['imgname'], $_POST['imgalt']);
                        }else{
                                $result = "Vi sono errori nei campi inseriti";
                        }
                        if ($result == ""){
                                $status = "<div class='statussuccess'>Inserimento avvenuto con successo</div>";
                        }else{
                                $status = "<div class='statusfailed'>Inserimento fallito: " . $result;
                                if (isset($_SESSION['qtyErrors'])){
                                        $status = $status . "<br />" . $_SESSION['qtyErrors'];
                                        unset($_SESSION['qtyErrors']);
                                }
                                if (isset($_SESSION['moneyErrors'])){
                                        $status = $status . "<br />" . $_SESSION['moneyErrors'];
                                        unset($_SESSION['moneyErrors']);
                                }
                                if (isset($_SESSION['formatErrors'])){
                                        $status = $status . "<br />" . $_SESSION['formatErrors'];
                                        unset($_SESSION['formatErrors']);
                                }
                                $status = $status  . "</div>";
                                $nome = $_POST['NomeS'];
                                $costo = $_POST['Costo'];
                                $desc = $_POST['Desc'];
                                $disp = $_POST['Disp'];
                                $imgname = $_POST['imgname'];
                                $imgalt = $_POST['imgalt'];
                        }
                        $content = str_replace("<!--STATUS-->", $status, $content);
                        $dbAccess->closeDBConnection();
                }
        }
        $content = str_replace("<!--VALORENOME-->", $nome, $content);
        $content = str_replace("<!--VALORECOSTO-->", $costo, $content);
        $content = str_replace("<!--VALOREDESC-->", $desc, $content);
        $content = str_replace("<!--VALOREDISP-->", $disp, $content);
        $content = str_replace("<!--VALOREIMG-->", $imgname, $content);
        $content = str_replace("<!--VALOREALT-->", $imgalt, $content);
        echo($content);
?>
