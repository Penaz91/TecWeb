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
        setLoadScript($content, "setInstrumentAddPH()");
        setContentFromFile($content, __("struttura_aggiungiStrumentazione.html"));
        $nome = "";
        $costo = "";
        $desc = "";
        $disp = "";
        $imgname = "";
        if (isset($_POST['inserimento'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $result = $dbAccess->insertInstrument($_POST['Nome'], $_POST['Costo'], $_POST['Desc'], $_POST['Disp'], $_POST['imgname']);
                        if ($result == ""){
                                $status = "<div id='statussuccess'>Inserimento avvenuto con successo</div>";
                        }else{
                                $status = "<div id='statusfailed'>Inserimento fallito: " . $result  . "</div>";
                                $nome = $_POST['Nome'];
                                $costo = $_POST['Costo'];
                                $desc = $_POST['Desc'];
                                $disp = $_POST['Disp'];
                                $imgname = $_POST['imgname'];
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
        echo($content);
?>
