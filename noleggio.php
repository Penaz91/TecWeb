<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("struttura.html"));

        initBreadcrumbs($content, "Home", "index.php");
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR . "style_noleggio.css", $content);
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Instrumentation Rental");
                addBreadcrumb($content, "Instrumentation Rental", "");
        }else{
                setTitle($content, "Noleggio Strumentazione");
                addBreadcrumb($content, "Noleggio Strumentazione", "");
        }
        setUserStatus($content);
        setupMenu($content, 2);
        setAdminArea($content);
        setLangArea($content, "noleggio.php");
        setLoadScript($content, "");
        $struct = file_get_contents(__("contenuto_noleggio.html"));
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $instr = $dbAccess->getInstrumentationList();
                $rescount = count($instr['Nome']);
                $cont = "";
                for ($i = 0; $i < $rescount; $i++){
                        $temp = file_get_contents("riquadro_strumento.html");
                        $temp = str_replace("<!--NOME-->", $instr['Nome'][$i], $temp);
                        $temp = str_replace("<!--DESCRIZIONE-->", $instr['Descr'][$i], $temp);
                        $temp = str_replace("<!--STOCK-->", $instr['Qty'][$i], $temp);
                        $temp = str_replace("<!--IMMAGINE-->", $instr['Img'][$i], $temp);
                        $temp = str_replace("<!--PREZZO-->", $instr['Costo'][$i], $temp);
                        $cont = $cont . $temp;
                }
                $struct = str_replace("<!--ELENCO-->", $cont, $struct);
                $dbAccess->closeDBConnection();
        }
        setContentFromString($content, $struct);
        echo($content);
?>
