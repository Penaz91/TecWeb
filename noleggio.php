<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        $ITEMS_PER_PAGE = 5;
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

        $struct = file_get_contents(__("contenuto_noleggio.html"));
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $instr = $dbAccess->getInstrumentationList();
                $rescount = count($instr['Nome']);
                $cont = "";
                $tabindex = 20;
                $totpg = ceil($rescount / $ITEMS_PER_PAGE);
                if (isset($_GET['p'])){
                        $pageid=$_GET['p'];
                }else{
                        $pageid=1;
                }
                $nextpagelink = "";
                $prevpagelink = "";
                if ($pageid > 1){
                        $prevpagelink = "<a href='noleggio.php?p=" . ($pageid-1) . "' title='" . getMessage("119") . "' tabindex='48'>" . getMessage("1003") . "</a>";
                }
                if ($pageid < $totpg){
                        $nextpagelink = "<a href='noleggio.php?p=" . ($pageid+1) . "' title='" . getMessage("120") . "' tabindex='48'>" . getMessage("1004") . "</a>";
                }
                $pagesection = $prevpagelink . " | <span class='pagenumber'>" . getMessage("1005") . $pageid . getMessage("1006") . $totpg . " </span> | " . $nextpagelink;
                $begincount = $ITEMS_PER_PAGE * ($pageid - 1);
                $endcount = $pageid * $ITEMS_PER_PAGE;
                if ($rescount < $endcount){
                        $endcount = $rescount;
                }
                if ($begincount+1 == $endcount){
                        $resSection = getMessage("1010") . ($begincount + 1) . getMessage("1009") . $rescount;
                }else{
                        $resSection = getMessage("1007") . ($begincount + 1) . getMessage("1008") . ($endcount) . getMessage("1009") . $rescount;
                }
                for ($i = $begincount; $i < $rescount && $i < $endcount ; $i++){
                        $temp = file_get_contents("riquadro_strumento.html");
                        $temp = str_replace("<!--NOME-->", $instr['Nome'][$i], $temp);
                        $temp = str_replace("<!--DESCRIZIONE-->", $instr['Descr'][$i], $temp);
                        $temp = str_replace("<!--STOCK-->", $instr['Qty'][$i], $temp);
                        $temp = str_replace("<!--IMMAGINE-->", $instr['Img'][$i], $temp);
                        $temp = str_replace("<!--PREZZO-->", $instr['Costo'][$i], $temp);
                        $temp = str_replace("<!--ALT-->", $instr['ImgAlt'][$i], $temp);
                        $temp = str_replace("<!--TABINDEX-->", $tabindex, $temp);
                        $tabindex++;
                        $temp = str_replace("<!--TABIDX2-->", $tabindex, $temp);
                        $tabindex++;
                        $cont = $cont . $temp;
                }
                $struct = str_replace("<!--ELENCO-->", $cont, $struct);
                $struct = str_replace("<!--PAGINE-->", $pagesection, $struct);
                $struct = str_replace("<!--RES-->", $resSection, $struct);
                $dbAccess->closeDBConnection();
        }
        setContentFromString($content, $struct);
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
