<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $content = file_get_contents(__("registrazione_ok.html"));
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR ."style_login.css", $content);
        addMobileStyleSheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile.css", $content);
        echo($content);

?>
