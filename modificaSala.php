<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        //use DBAccess;

        session_start();
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
                        $content = str_replace("<!--VALOREFUNZIONE-->", $_POST['Funzione'], $content);
                        $content = str_replace("<!--VALOREPREZZO-->", $_POST['PrezzoOrario'], $content);
                        $result = $dbAccess->editRoom($_SESSION['roomid'], $_SESSION['roomfunc'], $_POST['Nome'], $_POST['Funzione'], $_POST['PrezzoOrario']);
                        if ($result==True){
                                $_SESSION['statussuccess'] = true;
                                $_SESSION['statusmessage'] = "Sala Modificata correttamente";
                        }else{
                                $_SESSION['statussuccess'] = false;
                                $_SESSION['statusmessage'] = "Si è verificato un errore durante la Modifica della sala";
                        }
                        unset($_SESSION['roomid']);
                        unset($_SESSION['roomfunc']);
                        header("Location: searchEditRoom.php");
                        exit();
                }else{
                        $content = str_replace("<!--VALORENOME-->", $_GET['id'], $content);
                        $content = str_replace("<!--VALOREFUNZIONE-->", $_GET['func'], $content);
                        $content = str_replace("<!--VALOREPREZZO-->", $_GET['pr'], $content);
                        $_SESSION['roomid']=$_GET['id'];
                        $_SESSION['roomfunc']=$_GET['func'];
                }
        }
        echo($content);
?>
