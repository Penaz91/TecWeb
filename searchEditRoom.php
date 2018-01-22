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
        setLoadScript($content, "setRoomSearchPH_Admin()");
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
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
        $status = "";
        if(isset($_SESSION['statussuccess'])){
                if ($_SESSION['statussuccess']==true){
                        $status = "<div id='statussuccess'>" . $_SESSION['statusmessage'] . "</div>";
                }else{
                        $status = "<div id='statusfailed'>" . $_SESSION['statusmessage'] . "</div>";
                }
                unset($_SESSION['statussuccess']);
                unset($_SESSION['statusmessage']);
        }
        $admpanel = str_replace("<!--STATUS-->", $status, $admpanel);
        if (!isset($_POST['SRoom'])){
                $repl = "Inserisci un termine da cercare e clicca sul bottone \"Cerca\"";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        if ($_POST['searchtype']=="Sala"){
                                $results = $dbAccess->doRoomSearch($_POST['SRoom']);
                        }
                        if($_POST['searchtype']=="funz"){
                                $results = $dbAccess->doRoomSearchFunc($_POST['SRoom']);
                        }
                        if($_POST['searchtype']=="Costo"){
                                //FIXME: Richiede controllo di formato
                                $results = $dbAccess->doRoomSearchCost($_POST['SRoom']);
                        }
                        if($_POST['searchtype']=="CostoMin"){
                                //FIXME: Richiede controllo di formato
                                $results = $dbAccess->doRoomSearchMinCost($_POST['SRoom']);
                        }
                        if($_POST['searchtype']=="CostoMax"){
                                //FIXME: Richiede controllo di formato
                                $results = $dbAccess->doRoomSearchMaxCost($_POST['SRoom']);
                        }
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
                                        $tablecontent = $tablecontent . "<a href='elimina_stanza.php?id=" . $results['Room'][$i] . "&func=". $results['Func'][$i] ."'>Elimina Sala</a><br />";
                                        $tablecontent = $tablecontent . "<a href='modificaSala.php?id=" . $results['Room'][$i] . "&func=" . $results['Func'][$i] . "&pr=" . $results['Price'][$i] . "'>Modifica Sala</a><br />";
                                        $tablecontent = $tablecontent . "</td>";
                                        $tablecontent = $tablecontent . "</tr>";
                                }
                        }
                        $repl = str_replace("<!--RISULTATIRICERCA-->", $tablecontent, $repl);
                        $dbAccess->closeDBConnection();
                }
        }
        $admpanel = str_replace($torepl, $repl, $admpanel);
        setContentFromString($content, $admpanel);
        echo ($content);

?>
