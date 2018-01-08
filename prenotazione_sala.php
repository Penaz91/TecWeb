<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;

        session_start();
        checkLoggedUserAndRedirect("prenotazione_sala.php");
        $content = file_get_contents("struttura.html");

        setTitle($content, "Prenotazione Sale");
        initBreadcrumbs($content, "Home", "index.php");
        addBreadcrumb($content, "Prenotazione Sale", "");
        setUserStatus($content);
        setAdminArea($content);
        setupMenu($content, 3);
        setContentFromFile($content, "contenuto_prenotazioni.html");
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                $res = $dbAccess->getRoomList();
                for ($i=0; $i<count($res["Nome"]); $i++){
                        $sale = $sale .  "<option value='". $res["Nome"][$i] . "'>" . $res["Nome"][$i] . "</option>";
                }
                for ($i=0; $i<count($res["Funzione"]); $i++){
                        $funz = $funz .  "<option value='". $res["Funzione"][$i] . "'>" . $res["Funzione"][$i] . "</option>";
                }
                $content = str_replace("<!--LISTASALE-->", $sale, $content);
                $content = str_replace("<!--LISTASERVIZI-->", $funz, $content);
                $content = str_replace("<!--VALOREDATA-->", $_POST["Data"], $content);
                if (isset($_POST['submit'])){
                        if (empty($_POST['Data'])){
                                $err = "<div id='statusfailed'>Inserire qualcosa nel campo data</div>";
                                $content = str_replace("<!--STATO-->", $err, $content);
                        }else{
                                if (checkDateInput($_POST['Data'])){
                                        $data = DateTime::createFromFormat("d/m/Y", $_POST['Data']);
                                        $data = $data->format("Ymd");
                                        $res = $dbAccess->checkBookings("1", $data);
                                        for ($i = 0; $i < count($res["Time"]); $i++){
                                                $resp = $resp . "<span class='booktime'>Ore " . $res["Time"][$i] . ":00</span><span class='bookstatus";
                                                if ($res["Status"][$i] == "Occupato"){
                                                        $resp = $resp . "unavailable'>" . $res["Status"][$i] . "</span><br />";
                                                }else if ($res["Status"][$i] == "Disponibile"){
                                                        $resp = $resp . "available'>" . $res["Status"][$i] . "</span><br />";
                                                }else{
                                                        $resp = $resp . "yourbooking'>" . $res["Status"][$i] . "</span><br />";
                                                }
                                        }
                                        $content = str_replace("<!--RISULTATIVERIFICA-->", $resp, $content);
                                }else{
                                        $content = str_replace("<!--STATO-->", "<div id='statusfailed'>" . $_SESSION['dateerrors'] . "</div>", $content);
                                        unset($_SESSION['dateerrors']);
                                }
                        }
                }
                if (isset($_POST['submit2'])){
                        echo "Prenotazione effettuata";
                }
        }
        echo($content);
?>
