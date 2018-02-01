<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $errmsgs = array();
        $content = file_get_contents("strutturaregistrazione.html");
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
                        $errmsgs[] = getMessage("226");
                }
                if ($_SESSION['RemailErr']){
                        $errmsgs[] = getMessage("227");
                }
                if ($_SESSION['RemailErr2']){
                        $errmsgs[] = getMessage("221");
                }
                if ($_SESSION['RpassErr']){
                        $errmsgs[] = getMessage("228");
                }
                if ($_SESSION['RpassErr2']){
                        $errmsgs[] = getMessage("229");
                }

                if ($_SESSION['RtelErr']){
                        $errmsgs[] =getMessage("222");
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
