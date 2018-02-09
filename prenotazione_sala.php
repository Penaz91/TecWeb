<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedUserAndRedirect("prenotazione_sala.php");
        $content = file_get_contents(__("struttura.html"));

        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR . "style_prenotazioni.css", $content);
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
                $oldnome = "";
                $errors = "";
                for ($i=0; $i<count($res["Nome"]); $i++){
                        if ($oldnome == "" || $oldnome != $res["Nome"][$i]){
                                if ($oldnome != ""){
                                        $sale = $sale . "</optgroup>";
                                }
                                if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                                        $sale = $sale . "<optgroup label='" . $res['EngNome'][$i] . "'>";
                                }else{
                                        $sale = $sale . "<optgroup label='" . $res['Nome'][$i] . "'>";
                                }
                        }
                        if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                                if (isset($_POST['Sale']) && $res["EngNome"][$i]  . " - " . $res["EngFunc"][$i] == $_POST["Sale"]){
                                        $sale = $sale .  "<option value='". $res["EngNome"][$i] . " - " . $res["EngFunc"][$i] . "' selected='selected'>" . $res["EngNome"][$i] . " - " . $res["EngFunc"][$i] ."</option>";
                                }else{
                                        $sale = $sale .  "<option value='". $res["EngNome"][$i] . " - " . $res["EngFunc"][$i] ."'>" . $res["EngNome"][$i] . " - " . $res["EngFunc"][$i] ."</option>";
                                }
                                $oldnome = $res["Nome"][$i];
                        }else{
                                if (isset($_POST['Sale']) && $res["Nome"][$i]  . " - " . $res["Funzione"][$i] == $_POST["Sale"]){
                                        $sale = $sale .  "<option value='". $res["Nome"][$i] . " - " . $res["Funzione"][$i] . "' selected='selected'>" . $res["Nome"][$i] . " - " . $res["Funzione"][$i] ."</option>";
                                }else{
                                        $sale = $sale .  "<option value='". $res["Nome"][$i] . " - " . $res["Funzione"][$i] ."'>" . $res["Nome"][$i] . " - " . $res["Funzione"][$i] ."</option>";
                                }
                                $oldnome = $res["Nome"][$i];
                        }
                }
                $sale = $sale . "</optgroup>";
                $content = str_replace("<!--LISTASALE-->", $sale, $content);
                if (isset($_POST['submit2'])){
                        $timeOk = checkTimeInput($_POST['Ora']);
                        $durOk = checkDurationInput($_POST['Durata']);
                        if($timeOk && $durOk){
                                $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                $data = $data->format("Ymd");
                                preg_match("/^(?<Sale>[\w,\d,\s]*) - (?<Servizio>[\w,\d,\s]*)$/", $_POST['Sale'], $match);

                                if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                                        $room = $dbAccess->sec2prim($match['Sale'], $match['Servizio']);
                                        $result = $dbAccess->newBooking($_SESSION['username'], $room['Nome'][0], $room['Funzione'][0], $data, $_POST['Ora'], $_POST['Durata']);
                                }else{
                                        $result = $dbAccess->newBooking($_SESSION['username'], $match['Sale'], $match['Servizio'], $data, $_POST['Ora'], $_POST['Durata']);
                                }
                                if ($result != ""){
                                        $errors = $errors . getMessage($result) . "<br />";
                                }else{
                                        $_SESSION['success']=true;
                                }
                        }else{
                                if (!$timeOk){
                                        $errors = $errors . '<a href="#Ora" title="' . getMessage("115") . '">' . $_SESSION["timeerrors"] . "</a>";
                                }
                                if (!$durOk){
                                        $errors = $errors . '<a href="#Durata" title="' . getMessage("114") . '">' . $_SESSION["durationerrors"] . "</a>";
                                }
                        }
                }
                if (isset($_POST['submit'])|| isset($_POST['submit2'])){
                        if (empty($_POST['Data'])){
                                $err = '<div class="statusfailed"><a href="#Data" title="' . getMessage("116") . '">' . getMessage("202") . "</a></div>";
                                $content = str_replace("<!--STATO-->", $err, $content);
                        }else{
                                if (checkDateInput($_POST['Data'])){
                                        $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                        $data = $data->format("Ymd");
                                        preg_match("/^(?<Sale>[\w,\d,\s]*) - (?<Servizio>[\w,\d,\s]*)$/", $_POST['Sale'], $match);
                                        if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                                                $room = $dbAccess->sec2prim($match['Sale'], $match['Servizio']);
                                                $res = $dbAccess->checkBookings($room['Nome'][0], $data);
                                        }else{
                                                $res = $dbAccess->checkBookings($match['Sale'], $data);
                                        }
                                        $resp = "";
                                        for ($i = 0; $i < count($res["Time"]); $i++){
                                                $resp = $resp . "<tr>";
                                                $resp = $resp . "<td scope='row' class='booktime'>". $res["Time"][$i] . ":00</td><td class='bookstatus";
                                                if ($res["Status"][$i] == "Occupato"){
                                                        $resp = $resp . "unavailable'>" . getMessage("1000") . "</td>";
                                                }else if ($res["Status"][$i] == "Disponibile"){
                                                        $resp = $resp . "available'>" . getMessage("1001") . "</td>";
                                                }else{
                                                        $resp = $resp . "yourbooking'>" . getMessage("1002") . "</td>";
                                                }
                                                $resp = $resp . "</tr>";
                                        }
                                        $table = file_get_contents(__("roomBookTable.html"));
                                        $table = str_replace("<!--RISULTATIRICERCA-->", $resp, $table);
                                        $content = str_replace("<!--RISULTATIVERIFICA-->", $table, $content);
                                        $form = file_get_contents(__("form_prenotazione2.html"));
                                        $content = str_replace("<!--ALTROFORM-->", $form, $content);
                                }else{
                                        $errors = $errors . '<a href="#Data" title="' . getMessage("116") . '">' . $_SESSION["dateerrors"] . "</a>";
                                }
                        }
                }
                if (!empty($errors)){
                        $content = str_replace("<!--STATO-->", "<div class='statusfailed'>" . $errors . "</div>", $content);
                }
                if (isset($_SESSION['success']) && $_SESSION['success']==true){
                        $content = str_replace("<!--STATO-->", "<div class='statussuccess'>" . getMessage("11") ."</div>", $content);
                        unset($_SESSION['success']);
                }
                $dbAccess->closeDBConnection();
        }
        $data = "";
        $ora = "";
        $dur = "";
        $herr = false;
        $durerr = false;
        $derr = false;
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        if (isset($_SESSION['dateerrors'])){
                $derr = $_SESSION['dateerrors'];
                unset($_SESSION['dateerrors']);
        }
        if (isset($_POST['Data'])){
                $data = $_POST['Data'];
        }
        prefillAndHighlight("Data", $derr, $xml, $data);
        if (isset($_POST['submit2'])){
                if (isset($_SESSION['timeerrors'])){
                        $herr = $_SESSION['timeerrors'];
                        unset($_SESSION['timeerrors']);
                }
                if (isset($_SESSION['durationerrors'])){
                        $durerr = $_SESSION['durationerrors'];
                        unset($_SESSION['durationerrors']);
                }
                if (isset($_POST['Ora'])){
                        $ora = $_POST['Ora'];
                }
                if (isset($_POST['Durata'])){
                        $dur = $_POST['Durata'];
                }
                prefillAndHighlight("Ora", $herr, $xml, $ora);
                prefillAndHighlight("Durata", $durerr, $xml, $dur);
        }
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
