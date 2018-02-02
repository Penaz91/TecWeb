<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $errmsgs = array();
        $content = file_get_contents("strutturaregistrazione.html");
        addMobileStyleSheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile.css", $content);

        function prefillAndHighlight($fieldID, $FieldErrBool, &$xml, $fieldValue){
                $field = $xml -> getElementById($fieldID);
                $field->setAttribute("value", $fieldValue);
                $fieldAttrs = explode(" ", $field->getAttribute("class"));
                if (isset($FieldErrBool) && $FieldErrBool == true){
                        $fieldAttrs[] = "wrong";
                }
                $newAttrs = "";
                for ($i = 0; $i < count($fieldAttrs); $i++) {
                        if ($i==0){
                                $newAttrs = $fieldAttrs[$i];
                        }else{
                                $newAttrs = $newAttrs . " " . $fieldAttrs[$i];
                        }
                }
                $field->setAttribute("class", $newAttrs);
        }

        if (isset($_SESSION['reload'])){
                unset( $_SESSION['reload'] );
                $xml = new DOMDocument();
                $xml->loadHTML($content);
                prefillAndHighlight("Rusername", $_SESSION['RuserErr'], $xml, $_SESSION['Rusername']);
                $errorMail = $_SESSION['RemailErr'] || $_SESSION['RemailErr2'];
                prefillAndHighlight("Remail", $errorMail, $xml, $_SESSION['Remail']);
                prefillAndHighlight("Rtel", $_SESSION['RtelErr'], $xml, $_SESSION['Rtel']);
                $errorPass = $_SESSION['RpassErr'] || $_SESSION['RpassErr2'];
                prefillAndHighlight("Rpwd", $errorPass, $xml, "");
                prefillAndHighlight("Rpwd2", $errorPass, $xml, "");
                $content = $xml->saveXML($xml->documentElement);
                addXHTMLdtd($content);
                if ($_SESSION['RuserErr']){
                        $errmsgs[] = "<a href=\"#Rusername\" title=\"" . getMessage("108") . "\">" . getMessage("226") . "</a>";
                }
                if ($_SESSION['RemailErr']){
                        $errmsgs[] = "<a href=\"#Remail\" title=\"" . getMessage("106") . "\">" . getMessage("227") . "</a>";
                }
                if ($_SESSION['RemailErr2']){
                        $errmsgs[] = "<a href=\"#Remail\" title=\"" . getMessage("106") . "\">" . getMessage("221") . "</a>";
                }
                if ($_SESSION['RpassErr']){
                        $errmsgs[] = "<a href=\"#Rpwd\" title=\"" . getMessage("105") . "\">" .getMessage("228") . "</a>";
                }
                if ($_SESSION['RpassErr2']){
                        $errmsgs[] = "<a href=\"#Rpwd\" title=\"" . getMessage("105") . "\">" .getMessage("229") . "</a>";
                }

                if ($_SESSION['RtelErr']){
                        $errmsgs[] = "<a href=\"#Rtel\" title=\"" . getMessage("105") . "\">" .getMessage("222") . "</a>";
                }
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
