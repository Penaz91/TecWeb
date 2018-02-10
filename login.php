<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("strutturalogin.html"));
        addMobileStyleSheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile.css", $content);
        if (isset($_SESSION['loginstatus'])){
                $repl = "<div id='errorlist'>";
                $torepl = "<!--STATUS LOGIN-->";
                if ($_SESSION['loginstatus']=='noUser'){
                        $repl = $repl . "<a href='#username' title='" . getMessage("104") . "'>" . getMessage("234") . "</a>";
                }
                if ($_SESSION['loginstatus']=='wrongPass'){
                        $repl = $repl . "<a href='#password' title='" . getMessage("105") . "'>" . getMessage("235") . "</a>";
                }
                $repl = $repl . "</div>";
                $replaced = str_replace($torepl, $repl, $content);
                echo($replaced);
        }else{
                echo($content);
        }
?>
