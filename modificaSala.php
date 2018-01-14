<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;

        session_start();
        checkLoggedAdmin();
        $content = file_get_contents(__("struttura.html"));

        if ($_SESSION['language']=='en'){
                setTitle($content, "Edit a Room - Admin Panel");
        }else{
                setTitle($content, "Modifica Una Sala - Pannello Amministrazione");
        }
        initBreadcrumbs($content, "Home", "index.php");
        if ($_SESSION['language']=='en'){
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
                                $status = "<div id='statussuccess'>Sala Modificata correttamente</div>";
                        }else{
                                $status = "<div id='statusfailed'>Si è verificato un errore durante la Modifica della sala</div>";
                        }
                        unset($_SESSION['roomid']);
                        unset($_SESSION['roomfunc']);
                }else{
                        $content = str_replace("<!--VALORENOME-->", $_GET['id'], $content);
                        $content = str_replace("<!--VALOREFUNZIONE-->", $_GET['func'], $content);
                        $content = str_replace("<!--VALOREPREZZO-->", $_GET['pr'], $content);
                        $_SESSION['roomid']=$_GET['id'];
                        $_SESSION['roomfunc']=$_GET['func'];
                }
                $content = str_replace("<!--STATUS-->", $status, $content);
        }
        echo($content);
?>
