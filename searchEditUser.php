<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;


        session_start();
        checkLoggedAdmin();

        $content = file_get_contents("struttura.html");
        setTitle($content, "Ricerca e Modifica Utente");
        addMobileStylesheet("CSS/style_mobile_admin.css", $content);
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Pannello Amministrazione", "admin.php");
        addBreadcrumb($content, "Ricerca e Modifica Utente", "");
        $admpanel = file_get_contents("struttura_searchEditUser.html");
        $torepl = "<!--RISULTATI-->";
        if (!isset($_POST['SUserName'])){
                $repl = "Inserisci un termine da cercare e clicca sul bottone \"Cerca\"";
        }else{
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $results = $dbAccess->doUserSearch($_POST['SUserName']);
                        $repl = file_get_contents("usersearchtable.html");
                        $tablecontent = "";
                        $ressize = count($results['User']);
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
                                        $tablecontent = $tablecontent . "<td scope=\"row\">" . $results["User"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Mail"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . $results["Tel"][$i] . "</td>";
                                        $tablecontent = $tablecontent . "<td>" . ($results["Amm"][$i]==1 ? 'Si' : 'No') . "</td>";
                                        $tablecontent = $tablecontent . "<td>";
                                        $tablecontent = $tablecontent . "<a href='elimina_account_admin.php?id=" . $results['User'][$i] . "'>Elimina utente</a><br />";
                                        if ($results['Amm'][$i]==0){
                                                $tablecontent = $tablecontent . "<a href='convertiAdmin.php?id=" . $results['User'][$i] . "&admin=1'>Rendi Amministratore</a><br />";
                                        }else{
                                                $tablecontent = $tablecontent . "<a href='convertiAdmin.php?id=" . $results['User'][$i] . "&admin=0'>Rimuovi Permessi di Amministratore</a><br />";
                                        }
                                        $tablecontent = $tablecontent . "<a href='userRoomBookings_admin.php?id=" . $results['User'][$i] . "'>Visualizza Prenotazioni Sale</a><br />";
                                        $tablecontent = $tablecontent . "<a href='cercaPrenotazioniStrumentazione.php?id=" . $results['User'][$i] . "'>Visualizza Prenotazioni Strumentazione</a><br />";
                                        //$tablecontent = $tablecontent . "<td>Azioni Che<br/>Non So<br/>Come Fare</td>";
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
