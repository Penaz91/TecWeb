<?php
        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents("strutturalogin.html");
        if (isset($_SESSION['loginstatus'])){
                $repl = "<div id='errorlist'>";
                $torepl = "<!--STATUS LOGIN-->";
                if ($_SESSION['loginstatus']=='noUser'){
                        $repl = $repl . 'Utente Non Trovato, riprova.';
                }
                if ($_SESSION['loginstatus']=='wrongPass'){
                        $repl = $repl . 'Password Errata.';
                }
                $repl = $repl . "</div>";
                $replaced = str_replace($torepl, $repl, $content);
                echo($replaced);
        }else{
                echo($content);
        }
?>
