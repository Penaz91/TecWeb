<?php
        session_start();
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
                $replaced = str_replace($torepl, $repl, $content);
                $repl = $repl . "</div>";
                echo($replaced);
        }else{
                echo($content);
        }
?>
