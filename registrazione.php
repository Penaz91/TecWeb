<?php
        session_start();
        $errmsgs = array();
        $content = file_get_contents("strutturaregistrazione.html");
        //Probabilmente da esternalizzare su file
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
                        $errmsgs[] = "Lo username definito esiste già.";
                }
                if ($_SESSION['RemailErr']){
                        $errmsgs[] = "La email definita è già registrata";
                }
                if ($_SESSION['RemailErr2']){
                        $errmsgs[] = "La stringa inserita non è un indirizzo email";
                }
                if ($_SESSION['RpassErr']){
                        $errmsgs[] = "Le due password non corrispondono";
                }
                if ($_SESSION['RpassErr2']){
                        $errmsgs[] = "I campi password sono vuoti";
                }

                if ($_SESSION['RtelErr']){
                        $errmsgs[] ="La stringa inserita non è un numero di telefono";
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
