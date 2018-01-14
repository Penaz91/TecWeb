<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;


        session_start();
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));
        addMobileStylesheet("CSS/" . __("style_mobile_admin.css"), $content);
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        initBreadcrumbs($content, "Home", "index.php");
        if ($_SESSION['language']=="en"){
                setTitle($content, "Search or Edit a Room");
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Search or Edit a Room", "");
        }else{
                setTitle($content, "Ricerca e Modifica Sala");
                addBreadcrumb($content, "Pannello Amministrazione", "admin.php");
                addBreadcrumb($content, "Ricerca e Modifica Sala", "");
        }
        $admpanel = file_get_contents(__("struttura_searchEditRoom.html"));
        $torepl = "<!--RISULTATI-->";
        if (!isset($_POST['SRoom'])){
                $repl = "Inserisci un termine da cercare e clicca sul bottone \"Cerca\"";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $results = $dbAccess->doRoomSearch($_POST['SRoom']);
                        $repl = file_get_contents(__("roomsearchtable_admin.html"));
                        $tablecontent = "";
                        $ressize = count($results['Room']);
                        if ($ressize==0){
                                $repl = "Il nostro personale Indiana Jones non ha trovato nulla";
                        }else{
                                if ($ressize == 1){
                                        $repl = "Il nostro personale Indiana Jones ha trovato un Risultato" . $repl;
                                }else{
                                        $repl = "Il nostro personale Indiana Jones ha trovato $ressize Risultati" . $repl;
                                }
                                for ($i = 0; $i < $ressize; $i++) {
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope='row'>" . $results["Room"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Func"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Price"][$i] . "&euro;</td>";
                                        $tablecontent = $tablecontent . "<td>";
                                        $tablecontent = $tablecontent . "<a href='elimina_stanza.php?id=" . $results['Room'][$i] . "&func=". $results['Func'] ."'>Elimina Sala</a><br />";
                                        $tablecontent = $tablecontent . "<a href='editRoom.php?id=" . $results['Room'][$i] . "&func=" . $results['Func'] . "'>Modifica Sala</a><br />";
                                        $tablecontent = $tablecontent . "</td>";
                                        $tablecontent = $tablecontent . "</tr>";
                                }
                        }
                        $repl = str_replace("<!--RISULTATIRICERCA-->", $tablecontent, $repl);
                }
        }
        $admpanel = str_replace($torepl, $repl, $admpanel);
        setContentFromString($content, $admpanel);
        echo ($content);

?>
