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
        if (!isset($_POST['SRoom'])){
                $repl = "";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $_SESSION['statusmessage'] = getMessage("233") . "<br />";
                        if ($_POST['searchtype']=="Sala"){
                                $results = $dbAccess->doRoomSearch($_POST['SRoom']);
                        }
                        if($_POST['searchtype']=="funz"){
                                $results = $dbAccess->doRoomSearchFunc($_POST['SRoom']);
                        }
                        if($_POST['searchtype']=="Costo"){
                                if (checkMoneyInput($_POST['SRoom'])){
                                        $results = $dbAccess->doRoomSearchCost($_POST['SRoom']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . $_SESSION['moneyErrors'];
                                        unset($_SESSION['moneyErrors']);
                                }
                        }
                        if($_POST['searchtype']=="CostoMin"){
                                if (checkMoneyInput($_POST['SRoom'])){
                                        $results = $dbAccess->doRoomSearchMinCost($_POST['SRoom']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . $_SESSION['moneyErrors'];
                                        unset($_SESSION['moneyErrors']);
                                }
                        }
                        if($_POST['searchtype']=="CostoMax"){
                                if (checkMoneyInput($_POST['SRoom'])){
                                        $results = $dbAccess->doRoomSearchMaxCost($_POST['SRoom']);
                                }else{
                                        $_SESSION['statussuccess'] = false;
                                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . $_SESSION['moneyErrors'];
                                        unset($_SESSION['moneyErrors']);
                                }
                        }
                        $repl = file_get_contents(__("roomsearchtable_admin.html"));
                        $tablecontent = "";
                        $ressize = count($results['Room']);
                        if ($ressize==0){
                                $repl = getMessage("400");
                        }else{
                                if ($ressize == 1){
                                        $repl = getMessage("401") . $repl;
                                }else{
                                        $repl = getMessage("402") . $ressize . getMessage("403") . $repl;
                                }
                                for ($i = 0; $i < $ressize; $i++) {
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope='row'>" . $results["Room"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Func"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Price"][$i] . "&euro;</td>";
                                        $tablecontent = $tablecontent . "<td>";
                                        $tablecontent = $tablecontent . "<a href='elimina_stanza.php?id=" . $results['Room'][$i] . "&amp;func=". $results['Func'][$i] ."'>" . getMessage("414") . "</a><br />";
                                        $tablecontent = $tablecontent . "<a href='modificaSala.php?id=" . $results['Room'][$i] . "&amp;func=" . $results['Func'][$i] . "&amp;pr=" . $results['Price'][$i] . "'>" . getMessage("415") . "</a><br />";
                                        $tablecontent = $tablecontent . "</td>";
                                        $tablecontent = $tablecontent . "</tr>";
                                }
                        }
                        $repl = str_replace("<!--RISULTATIRICERCA-->", $tablecontent, $repl);
                        $dbAccess->closeDBConnection();
                }
        }
        $status = "";
        if(isset($_SESSION['statussuccess'])){
                if ($_SESSION['statussuccess']==true){
                        $status = "<div class='statussuccess'>" . $_SESSION['statusmessage'] . "</div>";
                }else{
                        $status = "<div class='statusfailed'>" . $_SESSION['statusmessage'] . "</div>";
                }
                unset($_SESSION['statussuccess']);
                unset($_SESSION['statusmessage']);
        }
        $admpanel = str_replace("<!--STATUS-->", $status, $admpanel);
        $admpanel = str_replace($torepl, $repl, $admpanel);
        setContentFromString($content, $admpanel);
        echo ($content);

?>
