<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;


        session_start();
        checkLoggedUser();

        if (isset($_POST['deletebtn'])){
                $dbAccess = new DBAccess();
                $dbconn = $dbAccess->openDBConnection();
                if ($dbconn == false){
                        die ("Errore nella connessione al database");
                }else{
                        $dbAccess->deleteUser($_SESSION['username']);
                        header("Location: logout.php");
                }
        }
        if (isset($_POST['abortbtn'])){
                header("Location: index.php");
        }
        // Fai la domanda
        $content = file_get_contents("strutturaEliminazione.html");
        echo($content);
?>
