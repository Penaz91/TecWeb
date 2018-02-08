<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        $content = file_get_contents(__("struttura_50x.html"));
        addScreenStylesheet("CSS" . DIRECTORY_SEPARATOR ."style_login.css", $content);
        addMobileStyleSheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile.css", $content);
        echo($content);

?>
