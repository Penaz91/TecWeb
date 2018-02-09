<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();

        $content = file_get_contents(__("struttura.html"));
        setTitle($content, "Ricerca e Modifica Utente");
        addMobileStylesheet("CSS". DIRECTORY_SEPARATOR . __("style_mobile_admin.css"), $content);
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);

        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Amministrazione", "admin.php");
        addBreadcrumb($content, "Ricerca e Modifica Utente", "");
        $admpanel = file_get_contents("struttura_searchEditUser.html");
        $torepl = "<!--RISULTATI-->";
        if (!isset($_POST['SUserName'])){
                $repl = "";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $results = $dbAccess->doUserSearch($_POST['SUserName']);
                        $repl = file_get_contents(__("usersearchtable.html"));
                        $tablecontent = "";
                        $ressize = count($results['User']);
                        if ($ressize==0){
                                $repl = getMessage("400");
                        }else{
                                if ($ressize == 1){
                                        $repl = getMessage("401") . $repl;
                                }else{
                                        $repl = getMessage("402") . $repl . getMessage("403");
                                }
                                for ($i = 0; $i < $ressize; $i++) {
                                        $tablecontent = $tablecontent . "<tr>";
                                        $tablecontent = $tablecontent . "<td scope=\"row\">" . $results["User"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Mail"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Tel"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . ($results["Amm"][$i]==1 ? getMessage("1011") : getMessage("1012")) . "</td>";
                                        $tablecontent = $tablecontent . "<td>";
                                        $tablecontent = $tablecontent . "<a href='elimina_account_admin.php?id=" . $results['User'][$i] . "'>" . getMessage("416") . "</a><br />";
                                        if ($results['Amm'][$i]==0){
                                                $tablecontent = $tablecontent . "<a href='convertiAdmin.php?id=" . $results['User'][$i] . "&amp;admin=1'>" . getMessage("417") . "</a><br />";
                                        }else{
                                                $tablecontent = $tablecontent . "<a href='convertiAdmin.php?id=" . $results['User'][$i] . "&amp;admin=0'>" . getMessage("418") . "</a><br />";
                                        }
                                        $tablecontent = $tablecontent . "<a href='userRoomBookings_admin.php?id=" . $results['User'][$i] . "'>" . getMessage("419") . "</a><br />";
                                        $tablecontent = $tablecontent . "<a href='userInstrumentRentals_admin.php?id=" . $results['User'][$i] . "'>" . getMessage("420") . "</a><br />";
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
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo ($content);

?>
