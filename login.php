<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents("strutturalogin.html");
        if (isset($_SESSION['loginstatus'])){
                $repl = "<div id='errorlist'>";
                $torepl = "<!--STATUS LOGIN-->";
                if ($_SESSION['loginstatus']=='noUser'){
                        $repl = $repl . getMessage("234");
                }
                if ($_SESSION['loginstatus']=='wrongPass'){
                        $repl = $repl . getMessage("235");
                }
                $repl = $repl . "</div>";
                $replaced = str_replace($torepl, $repl, $content);
                echo($replaced);
        }else{
                echo($content);
        }
?>
