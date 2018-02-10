<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));

        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                setTitle($content, "Edit a Room - Admin Panel");
        }else{
                setTitle($content, "Modifica Una Sala - Pannello Amministrazione");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                addBreadcrumb($content, "Admin Panel", "admin.php");
                addBreadcrumb($content, "Edit A Room", "");
        }else{
                addBreadcrumb($content, "Pannello di Amministrazione", "admin.php");
                addBreadcrumb($content, "Modifica una Sala", "");
        }
        setUserStatus($content);
        setupMenu($content, -1);
        setAdminArea($content);
        setLangArea($content, $_SERVER['PHP_SELF']);
        setContentFromFile($content, __("contenuto_modificasala.html"));
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        if ($dbconn == false){
                die ("Errore nella connessione al database");
        }else{
                if (isset($_POST['modifica'])){
                        $content = str_replace("<!--VALORENOME-->", $_POST['Nome'], $content);
                        $content = str_replace("<!--VALORENOME_EN-->", $_POST['EngNome'], $content);
                        $content = str_replace("<!--VALOREFUNZIONE-->", $_POST['Funzione'], $content);
                        $content = str_replace("<!--VALOREFUNZIONE_EN-->", $_POST['EngFunc'], $content);
                        $content = str_replace("<!--VALOREPREZZO-->", $_POST['PrezzoOrario'], $content);
                        if (checkMoneyInput($_POST['PrezzoOrario'])){
                                $result = $dbAccess->editRoom($_SESSION['roomid'], $_SESSION['roomfunc'], $_POST['Nome'], $_POST['Funzione'], $_POST['PrezzoOrario'], $_POST['EngNome'], $_POST['EngFunc']);
                        }else{
                                $_SESSION['statussuccess'] = false;
                                $_SESSION['statusmessage'] = getMessage("218");
                                $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . "<br /><a href='#PrezzoOrario' title='" . getMessage("111") . "'>" . $_SESSION['moneyErrors'] . "</a>";
                        }
                        if ($result==""){
                                $_SESSION['statussuccess'] = true;
                                $_SESSION['statusmessage'] = getMessage("14");
                        }else{
                                $_SESSION['statussuccess'] = false;
                                $_SESSION['statusmessage'] = getMessage($result);
                        }
                        unset($_SESSION['roomid']);
                        unset($_SESSION['roomfunc']);
                        header("Location: searchEditRoom.php");
                        exit();
                }else{
                        if (isset($_SESSION['language']) && $_SESSION['language']=="en"){
                                $room = $dbAccess->sec2prim($_GET['id'], $_GET['func']);
                                $_SESSION['roomid']=$room['Nome'][0];
                                $_SESSION['roomfunc']=$room['Funzione'][0];
                        }else{
                                $_SESSION['roomid']=$_GET['id'];
                                $_SESSION['roomfunc']=$_GET['func'];
                        }
                        $roomdata = $dbAccess->doRoomSearchExact($_SESSION['roomid'], $_SESSION['roomfunc']);
                        $content = str_replace("<!--VALORENOME-->", $roomdata['Room'][0], $content);
                        $content = str_replace("<!--VALORENOME_EN-->", $roomdata['EngRoom'][0], $content);
                        $content = str_replace("<!--VALOREFUNZIONE-->", $roomdata["Func"][0], $content);
                        $content = str_replace("<!--VALOREFUNZIONE_EN-->", $roomdata['EngFunc'][0], $content);
                        $content = str_replace("<!--VALOREPREZZO-->", $roomdata['Price'][0], $content);
                }
                $dbAccess->closeDBConnection();
        }
        $xml = new DOMDocument();
        $xml->loadHTML($content);
        setHTMLNameSpaces($xml);
        $content = $xml->saveXML($xml->documentElement);
        addXHTMLdtd($content);
        echo($content);
?>
