<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $errmsgs = array();
        $content = file_get_contents("strutturaregistrazione.html");
        addMobileStyleSheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile.css", $content);

        if (isset($_SESSION['reload'])){
                unset( $_SESSION['reload'] );
                $xml = new DOMDocument();
                $xml->loadHTML($content);
                $userErr = false;
                if (isset($_SESSION['RuserErr'])){
                        $userErr = $_SESSION['RuserErr'];
                }
                prefillAndHighlight("Rusername", $userErr, $xml, $_SESSION['Rusername']);
                $errorMail = false;
                if (isset($_SESSION['RemailErr'])){
                        $errorMail = $_SESSION['RemailErr'];
                }
                if (isset($_SESSION['RemailErr2'])){
                        $errorMail = $errorMail || $_SESSION['RemailErr2'];
                }
                prefillAndHighlight("Remail", $errorMail, $xml, $_SESSION['Remail']);
                prefillAndHighlight("Rtel", $_SESSION['RtelErr'], $xml, $_SESSION['Rtel']);
                $errorPass = false;
                if (isset($_SESSION["RpassErr"])){
                        $errorPass = $_SESSION['RpassErr'];
                }
                if (isset($_SESSION['RpassErr2'])){
                        $errorPass = $errorPass || $_SESSION['RpassErr2'];
                }
                prefillAndHighlight("Rpwd", $errorPass, $xml, "");
                prefillAndHighlight("Rpwd2", $errorPass, $xml, "");
                setHTMLNameSpaces($xml);
                $content = $xml->saveXML($xml->documentElement);
                addXHTMLdtd($content);
                if (isset($_SESSION['RuserErr']) && $_SESSION['RuserErr']){
                        $errmsgs[] = "<a href=\"#Rusername\" title=\"" . getMessage("108") . "\">" . getMessage("226") . "</a>";
                }
                if (isset($_SESSION['RemailErr']) && $_SESSION['RemailErr']){
                        $errmsgs[] = "<a href=\"#Remail\" title=\"" . getMessage("106") . "\">" . getMessage("227") . "</a>";
                }
                if (isset($_SESSION['RemailErr2']) && $_SESSION['RemailErr2']){
                        $errmsgs[] = "<a href=\"#Remail\" title=\"" . getMessage("106") . "\">" . getMessage("221") . "</a>";
                }
                if (isset($_SESSION['RpassErr']) && $_SESSION['RpassErr']){
                        $errmsgs[] = "<a href=\"#Rpwd\" title=\"" . getMessage("105") . "\">" .getMessage("228") . "</a>";
                }
                if (isset($_SESSION['RpassErr2']) && $_SESSION['RpassErr2']){
                        $errmsgs[] = "<a href=\"#Rpwd\" title=\"" . getMessage("105") . "\">" .getMessage("229") . "</a>";
                }

                if (isset($_SESSION['RtelErr']) && $_SESSION['RtelErr']){
                        $errmsgs[] = "<a href=\"#Rtel\" title=\"" . getMessage("105") . "\">" .getMessage("222") . "</a>";
                }
        }else{
                $xml = new DOMDocument();
                $xml->loadHTML($content);
                setHTMLNameSpaces($xml);
                $content = $xml->saveXML($xml->documentElement);
                addXHTMLdtd($content);
        }
        if (!(empty($errmsgs))){
                $repl="<div id='errorlist'>";
                foreach ($errmsgs as $error):
                        $repl = $repl . $error . "<br/>";
                endforeach;
                $repl= $repl . "</div>";
                $content=str_replace("<!--STATOREGISTRAZIONE-->", $repl, $content);
        }
        echo($content);
?>
