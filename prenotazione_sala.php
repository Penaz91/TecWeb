<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        //use DBAccess;

        session_start();
        checkLoggedUserAndRedirect("prenotazione_sala.php");
        $content = file_get_contents(__("struttura.html"));

        addScreenStylesheet("CSS/style_prenotazioni.css", $content);
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Rent a Room", "");
                setTitle($content, "Rent a Room");
        }else{
                addBreadcrumb($content, "Prenotazione Sale", "");
                setTitle($content, "Prenotazione Sale");
        }
        setUserStatus($content);
        setupMenu($content, 3);
        setAdminArea($content);
        setLangArea($content, "prenotazione_sala.php");
        setContentFromFile($content, __("contenuto_prenotazioni.html"));
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $res = $dbAccess->getRoomList();
                $sale = "";
                for ($i=0; $i<count($res["Nome"]); $i++){
                        if (isset($_POST['Sale']) && $res["Nome"][$i]  . " - " . $res["Funzione"][$i] == $_POST["Sale"]){
                                $sale = $sale .  "<option value='". $res["Nome"][$i] . " - " . $res["Funzione"][$i] . "' selected='selected'>" . $res["Nome"][$i] . " - " . $res["Funzione"][$i] ."</option>";
                        }else{
                                $sale = $sale .  "<option value='". $res["Nome"][$i] . " - " . $res["Funzione"][$i] ."'>" . $res["Nome"][$i] . " - " . $res["Funzione"][$i] ."</option>";
                        }
                }
                if (isset($_POST['Data'])){
                        $content = str_replace("<!--VALOREDATA-->", $_POST["Data"], $content);
                }else{
                        $content = str_replace("<!--VALOREDATA-->", "", $content);
                }
                $content = str_replace("<!--LISTASALE-->", $sale, $content);
                if (isset($_POST['submit2'])){
                        $timeOk = checkTimeInput($_POST['Ora']);
                        $durOk = checkDurationInput($_POST['Durata']);
                        if($timeOk && $durOk){
                                $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                $data = $data->format("Ymd");
                                preg_match("/^(?<Sale>[\w,\d,\s]*) - (?<Servizio>[\w,\d,\s]*)$/", $_POST['Sale'], $match);
                                $result = $dbAccess->newBooking($_SESSION['username'], $match['Sale'], $match['Servizio'], $data, $_POST['Ora'], $_POST['Durata']);
                                if ($result != ""){
                                        $errors = $errors . $result . "<br />";
                                }else{
                                        $_SESSION['success']=true;
                                }
                        }else{
                                $errors = $errors . $_SESSION['timeerrors'];
                                $errors = $errors . $_SESSION['durationerrors'];
                                unset($_SESSION['timeerrors']);
                                unset($_SESSION['durationerrors']);
                        }
                }
                if (isset($_POST['submit'])|| isset($_POST['submit2'])){
                        if (empty($_POST['Data'])){
                                $err = "<div id='statusfailed'>Inserire qualcosa nel campo data</div>";
                                $content = str_replace("<!--STATO-->", $err, $content);
                        }else{
                                if (checkDateInput($_POST['Data'])){
                                        $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                        $data = $data->format("Ymd");
                                        preg_match("/^(?<Sale>[\w,\d,\s]*) - (?<Servizio>[\w,\d,\s]*)$/", $_POST['Sale'], $match);
                                        $res = $dbAccess->checkBookings($match['Sale'], $data);
                                        $resp = "";
                                        for ($i = 0; $i < count($res["Time"]); $i++){
                                                $resp = $resp . "<tr>";
                                                $resp = $resp . "<td scope='row' class='booktime'>". $res["Time"][$i] . ":00</td><td class='bookstatus";
                                                if ($res["Status"][$i] == "Occupato"){
                                                        $resp = $resp . "unavailable'>" . $res["Status"][$i] . "</td>";
                                                }else if ($res["Status"][$i] == "Disponibile"){
                                                        $resp = $resp . "available'>" . $res["Status"][$i] . "</td>";
                                                }else{
                                                        $resp = $resp . "yourbooking'>" . $res["Status"][$i] . "</td>";
                                                }
                                                $resp = $resp . "</tr>";
                                        }
                                        $table = file_get_contents("roomBookTable.html");
                                        $table = str_replace("<!--RISULTATIRICERCA-->", $resp, $table);
                                        $content = str_replace("<!--RISULTATIVERIFICA-->", $table, $content);
                                        $form = file_get_contents("form_prenotazione2.html");
                                        if (isset($_POST['Ora'])){
                                                $form = str_replace("<!--VALOREORA-->", $_POST["Ora"], $form);
                                        }else{
                                                $form = str_replace("<!--VALOREORA-->", "", $form);
                                        }
                                        if (isset($_POST['Durata'])){
                                                $form = str_replace("<!--VALOREDURATA-->", $_POST["Durata"], $form);
                                        }else{
                                                $form = str_replace("<!--VALOREDURATA-->", "", $form);
                                        }
                                        $content = str_replace("<!--ALTROFORM-->", $form, $content);
                                }else{
                                        $errors = $errors . $_SESSION['dateerrors'];
                                        unset($_SESSION['dateerrors']);
                                }
                        }
                }
                if (!empty($errors)){
                        $content = str_replace("<!--STATO-->", "<div id='statusfailed'>" . $errors . "</div>", $content);
                }
                if (isset($_SESSION['success']) && $_SESSION['success']==true){
                        $content = str_replace("<!--STATO-->", "<div id='statussuccess'>Prenotazione inserita con successo</div>", $content);
                        unset($_SESSION['success']);
                }
        }
        echo($content);
?>
