<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $errmsgs = array();
        $content = file_get_contents("strutturaregistrazione.html");
        addMobileStyleSheet("CSS" . DIRECTORY_SEPARATOR . "style_mobile.css", $content);
        function prefillAndHighlight($Rfield, $RfieldErr, $cont, $toReplace){
                if ( $RfieldErr == true ){
                        $toSet = $toReplace . ' value="' . $Rfield . '" class="wrong"';
                }else{
                        $toSet = $toReplace . ' value="' . $Rfield . '"';
                }
                return str_replace($toReplace, $toSet, $cont);
        }

        if (isset($_SESSION['reload'])){
                unset( $_SESSION['reload'] );
                $content = prefillAndHighlight($_SESSION['Rusername'], $_SESSION['RuserErr'], $content, 'id="Rusername"');
                $errorMail = $_SESSION['RemailErr'] || $_SESSION['RemailErr2'];
                $content = prefillAndHighlight($_SESSION['Remail'], $errorMail, $content, 'id="Remail"');
                $content = prefillAndHighlight($_SESSION['Rtel'], $_SESSION['RtelErr'], $content, 'id="Rtel"');
                $errorPass = $_SESSION['RpassErr'] || $_SESSION['RpassErr2'];
                $content = prefillAndHighlight("", $errorPass, $content, 'id="Rpwd"');
                $content = prefillAndHighlight("", $errorPass, $content, 'id="Rpwd2"');
                if ($_SESSION['RuserErr']){
                        $errmsgs[] = "<a href='#Rusername' title='" . getMessage("108") . "'>" . getMessage("226") . "<a/>";
                }
                if ($_SESSION['RemailErr']){
                        $errmsgs[] = "<a href='#Remail' title='" . getMessage("106") . "'>" . getMessage("227") . "</a>";
                }
                if ($_SESSION['RemailErr2']){
                        $errmsgs[] = "<a href='#Remail' title='" . getMessage("106") . "'>" . getMessage("221") . "</a>";
                }
                if ($_SESSION['RpassErr']){
                        $errmsgs[] = "<a href='#Rpwd' title='" . getMessage("105") . "'>" .getMessage("228");
                }
                if ($_SESSION['RpassErr2']){
                        $errmsgs[] = "<a href='#Rpwd' title='" . getMessage("105") . "'>" .getMessage("229");
                }

                if ($_SESSION['RtelErr']){
                        $errmsgs[] = "<a href='#Rtel' title='" . getMessage("105") . "'>" .getMessage("222");
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
