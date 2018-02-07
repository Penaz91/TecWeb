<?php
        REQUIRE_ONCE __DIR__ . DIRECTORY_SEPARATOR . "messages.php";

        const DATE_REGEX = "/^(?<d>\d{2})\/(?<m>\d{2})\/(?<Y>\d{4})$/";
        const PHONE_REGEX = "/^\d{6,11}$/";
        const MAIL_REGEX = "/^([\w\+\-]+\.?[\w\+\-\.]*)\@([\w\+\-]+)\.([\w\+\-\.]+)$/";
        const DIGITS_REGEX = "/^\d+$/";
        const FILEFORMAT_REGEX = "/^(?<name>[\w,\d]+).(?<ext>[\w,\d]+)$/";
        const TIME_REGEX = "/^(?<hour>\d\d):(0{2})$/";
        const DURATION_REGEX = "/^(?<dur>\d+)$/";

        function setTitle(&$content, $title){
                $content = str_replace("<!--TITLE-->", $title, $content);
        }

        function initBreadcrumbs(&$content, $breadcrumb, $link){
                if (empty($link)){
                        $markup=$breadcrumb;
                }else{
                        $markup = "<a href='$link'>$breadcrumb</a><!--ADDBREADCRUMB-->";
                }
                $content = str_replace("<!--ADDBREADCRUMB-->", $markup, $content);
        }

        function addBreadcrumb(&$content, $breadcrumb, $link){
                if (empty($link)){
                        $markup=$breadcrumb;
                }else{
                        $markup = "<a href='$link'>$breadcrumb</a><!--ADDBREADCRUMB-->";
                }
                if (empty($breadcrumb)){
                        $content = str_replace("<!--ADDBREADCRUMB-->", "", $content);
                }else{
                        $content = str_replace("<!--ADDBREADCRUMB-->", " >> " . $markup, $content);
                }
        }

        function setUserStatusFull(&$content, $panelSelected){
                $_SESSION['tabindex'] = 4;
                if (empty($_SESSION['username'])){
                        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                                $repl = "<li class='specialbtn'><a href='login.php'tabindex='4'><span xml:lang='en'>Sign in</span> | Sign Up</a></li>";
                        }else{
                                $repl = "<li class='specialbtn'><a href='login.php' tabindex='4'><span xml:lang='en'>Login</span> | Registrazione</a></li>";
                        }
                        $_SESSION['tabindex'] = 5;
                }else{
                        $uname = $_SESSION['username'];
                        if ($panelSelected){
                                if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                                        $repl = "<li class='specialbtn'><span class='lefticon' id='userlogged'>$uname</span><p class='panelselected'>User panel</p><a href='logout.php' tabindex='5'><span xml:lang='en'>Logout</span></a></li>";
                                }else{
                                        $repl = "<li class='specialbtn'><span class='lefticon' id='userlogged'>$uname</span><p class='panelselected'>Pannello Utente</p><a href='logout.php' tabindex='5'><span xml:lang='en'>Logout</span></a></li>";
                                }
                                $_SESSION['tabindex'] = 5;
                        }else{
                                if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                                        $repl = "<li class='specialbtn'><span class='lefticon' id='userlogged'>$uname</span><a href='userpanel.php' tabindex='4'>User panel</a><a href='logout.php' tabindex='5'><span xml:lang='en'>Logout</span></a></li>";
                                }else{
                                        $repl = "<li class='specialbtn'><span class='lefticon' id='userlogged'>$uname</span><a href='userpanel.php' tabindex='4'>Pannello Utente</a><a href='logout.php' tabindex='5'><span xml:lang='en'>Logout</span></a></li>";
                                }
                                $_SESSION['tabindex'] = 6;
                        }
                }
                $content = str_replace("<!--STATOUTENTE-->", $repl, $content);
        }

        function setUserStatus(&$content){
                setUserStatusFull($content, false);
        }

        function setAdminAreaFull(&$content, $selected){
                $repl = "";
                if (isset($_SESSION['admin']) && $_SESSION['admin']==1){
                        if ($selected){
                                if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                                        $repl="<li class='selectedspecial' id='admin'><p>Admin Area</p></li>";
                                }else{
                                        $repl="<li class='selectedspecial' id='admin'><p>Area Amministrazione</p></li>";
                                }
                        }else{
                                if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                                        $repl="<li class='specialbtn' id='admin'><a href='admin.php' tabindex='" . $_SESSION['tabindex'] . "'>Admin Area</a></li>";
                                }else{
                                        $repl="<li class='specialbtn' id='admin'><a href='admin.php' tabindex='" . $_SESSION['tabindex'] . "'>Area Amministrazione</a></li>";
                                }
                                $_SESSION['tabindex']++;
                        }
                }
                $content = str_replace("<!--ADMINAREA-->", $repl, $content);
        }

        function setAdminArea(&$content){
                setAdminAreaFull($content, false);
        }

        function setLangArea(&$content, $ref){
                $repl = "";
                        if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                                $repl="<li class='specialbtn'><a class='langbtnit' href='toItalian.php?ref=" . $ref . "' tabindex='" . $_SESSION['tabindex'] . "'><span xml:lang='it'>Versione Italiana</span></a></li>";
                        }else{
                                $repl="<li class='specialbtn'><a class='langbtnen' href='toEnglish.php?ref=" . $ref . "' tabindex='" . $_SESSION['tabindex'] . "'><span xml:lang='en'>English Version</span></a></li>";
                        }
                $content = str_replace("<!--LANGAREA-->", $repl, $content);
        }

        function fullSetupMenu(&$content, $activeId, $keepactive){
                if ($activeId >=0){
                        $_SESSION['tabindex'] += 4;
                }else{
                        $_SESSION['tabindex'] += 5;
                }
                $menuText = file_get_contents(__("menu.html"));
                $xml = new DOMDocument();
                $xml->loadHTML($menuText, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                $links = $xml->getElementsByTagName('a');
                $tabindex = $_SESSION['tabindex'];
                for ($i = $links->length-1; $i>=0; $i--){
                        $linkNode = $links->item($i);
                        $tabindex--;
                        if($i == $activeId && !$keepactive){
                                $linkText = $linkNode->textContent;
                                $newTextNode = $xml->createTextNode($linkText);
                                $linkNode->parentNode->replaceChild($newTextNode, $linkNode);
                                $tabindex++;
                        }else{
                                $linkNode->setAttribute('tabindex',$tabindex);
                        }
                }
                $lis = $xml->getElementsByTagName('li');
                for ($i = $lis->length-1; $i>=0; $i--){
                        $liNode = $lis->item($i);
                        if($i == $activeId){
                                $liNode->setAttribute('class','navbtncurr');
                        }else{
                                $liNode->setAttribute('class','navbtn');
                        }
                }
                $menuText = $xml->saveHTML();
                $menuText = str_replace("<div>", "", $menuText);
                $menuText = str_replace("</div>", "", $menuText);
                $content = str_replace("<!--MENU-->", $menuText, $content);
        }

        function setupMenu(&$content, $activeId){
                fullSetupMenu($content, $activeId, false);
        }

        function setContentFromFile(&$content, $filename){
                $additions = file_get_contents($filename);
                $content = str_replace("<!--CONTENUTO-->", $additions, $content);
        }

        function setContentFromString(&$content, $string){
                $content = str_replace("<!--CONTENUTO-->", $string, $content);
        }

        function checkLoggedUser(){
                if (empty($_SESSION['username'])){
                        header("Location: " . __("accesso_negato.html"));
                        exit();
                }
        }

        function checkLoggedUserAndRedirect($referral){
                if (empty($_SESSION['username'])){
                        $_SESSION['referral'] = $referral;
                        header("Location: login.php");
                        exit();
                }
        }

        function checkLoggedAdmin(){
                if (empty($_SESSION['admin']) || $_SESSION['admin']==false){
                        header("Location: " . __("accesso_negato.html"));
                        exit();
                }
        }

        function convertDateToISO($date){
                $data = DateTime::createFromFormat("d/m/Y", $date);
                $data = $data->format("Ymd");
                return $data;
        }

        function checkDateInput($date){
                if (!preg_match(DATE_REGEX, $date, $match)){
                        if (isset($_SESSION['dateerrors'])){
                                $_SESSION['dateerrors'] = $_SESSION['dateerrors'] . getMessage("203");
                        }else{
                                $_SESSION['dateerrors'] = getMessage("203");
                        }
                }else{
                        $currY = date("Y");
                        if ($match['Y'] < $currY){
                                if (isset($_SESSION['dateerrors'])){
                                        $_SESSION['dateerrors'] = $_SESSION['dateerrors'] . getMessage("204");
                                }else{
                                        $_SESSION['dateerrors'] = getMessage("204");
                                }
                        }
                        if (!checkDate($match['m'], $match['d'], $match['Y'])){
                                if (isset($_SESSION['dateerrors'])){
                                        $_SESSION['dateerrors'] = $_SESSION['dateerrors'] . getMessage("205");
                                }else{
                                        $_SESSION['dateerrors'] = getMessage("205");
                                }
                        }
                }
                return (empty($_SESSION['dateerrors']));
        }

        function checkDateOrder($di, $df){
                return $df > $di;
        }

        function checkTelInput($tel){
                if (!preg_match(PHONE_REGEX, $tel)){
                        $_SESSION['RtelErr'] = true;
                        return false;
                }else{
                        return true;
                }
        }

        function checkMailInput($mail){
                if (!preg_match(MAIL_REGEX, $mail)){
                        $_SESSION['RemailErr2'] = true;
                        return false;
                }else{
                        return true;
                }
        }

        function checkMoneyInput($amount){
                if (preg_match(DIGITS_REGEX, $amount)){
                        return true;
                }else{
                        $_SESSION['moneyErrors']=getMessage("206");
                        return false;
                }
        }

        function checkQtyInput($amount){
                if (preg_match(DIGITS_REGEX, $amount)){
                        return true;
                }else{
                        $_SESSION['qtyErrors']=getMessage("207");
                        return false;
                }
        }

        function checkFileFormatInput($name){
                if (preg_match(FILEFORMAT_REGEX, $name)){
                        return true;
                }else{
                        $_SESSION['formatErrors']=getMessage("208");
                        return false;
                }
        }

        function checkTimeInput($time){
                if (!preg_match(TIME_REGEX, $time, $match)){
                        if (isset($_SESSION['timeerrors'])){
                                $_SESSION['timeerrors'] = $_SESSION['timeerrors'] . getMessage("209") . "<br />";
                        }else{
                                $_SESSION['timeerrors'] = getMessage("209") . "<br />";
                        }
                }else{
                        if ($match['hour'] > 23 || $match['hour'] < 0){
                                if (isset($_SESSION['timeerrors'])){
                                        $_SESSION['timeerrors'] = $_SESSION['timeerrors'] . getMessage("210") . "<br />";
                                }else{
                                        $_SESSION['timeerrors'] = getMessage("210") . "<br />";
                                }
                }
                        return (empty($_SESSION['timeerrors']));

                }
        }

        function checkDurationInput($dur){
                if (!preg_match(DURATION_REGEX, $dur, $match)){
                        $_SESSION['durationerrors'] = getMessage("211") . "<br />";
                }else{
                        if ($match['dur'] <= 0){
                                $_SESSION['durationerrors'] = getMessage("211") . "<br />";
                        }
                }
                return (empty($_SESSION['durationerrors']));
        }

        function addStylesheet($path, $media, &$content){
                $content = str_replace("<!--STYLESHEETS-->",
                        '<link rel="stylesheet" href="' . $path . '" type="text/css" media="' . $media . '" charset="utf-8" />' . '<!--STYLESHEETS-->',
                        $content);
        }

        function addScreenStylesheet($path, &$content){
                addStylesheet($path, "screen", $content);
        }

        function addPrintStylesheet($path, &$content){
                addStylesheet($path, "print", $content);
        }

        function addSmallStylesheet($path, &$content){
                addStylesheet($path, "screen and (max-width: 800px)", $content);
        }


        function addMobileStylesheet($path, &$content){
                addStylesheet($path, "handheld, screen and (max-width: 480px), only screen and (max-width: 480px)", $content);
        }

        /* Funzione per il debug su console, richiede un browser con javascript*/
        function console_print($data){
                $out = $data;
                if (is_array($out)){
                        $out=implode(",", $out);
                }
                echo('<script>console.log("DEBUG: ' . $out . '");</script>');
        }

        /* Funzioni per debug dell'HTML generato su un file statico HTML */
        /* Piazza questa all'inizio del php da flushare*/
        function prepare_flush_page(){
                ob_start();
        }

        /* Piazza questa alla fine del php da flushare, con un nome di file*/
        function flush_page($filename){
                file_put_contents("DEBUG_".$filename, ob_get_contents());
                ob_end_flush();
        }

        /* Funzione di richiesta elementi tradotti */
        function __($filename){
                if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                        preg_match(FILEFORMAT_REGEX, $filename, $match);
                        $filename = $match['name'] . "_EN." . $match['ext'];
                        $filename = 'Traduzioni/' . $filename;
                }
                return $filename;
        }

        /* Funzione di ricerca messaggi*/
        function getMessage($key){
                if (!isset($_SESSION['language']) || $_SESSION['language']=='it'){
                        if (array_key_exists($key, $GLOBALS['MESSAGES_IT'])){
                                return $GLOBALS['MESSAGES_IT'][$key];
                        }
                }
                if ($_SESSION['language']=='en'){
                        if (array_key_exists($key, $GLOBALS['MESSAGES_EN'])){
                                return $GLOBALS['MESSAGES_EN'][$key];
                        }
                }
        }

        /*Workaround per sostituzione DTD libxml/domdocument*/
        function addXHTMLdtd(&$content){
                $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . $content;
        }

        /* Workaround per duplicazione namespace di libxml/domdocument*/
        function setHTMLNameSpaces(&$xml){
                $field = $xml->getElementsByTagName("html")->item(0);
                $field->setAttribute("xmlns", "http://www.w3.org/1999/xhtml");
                if (isset($_SESSION['language']) && $_SESSION['language']=='en'){
                        $field->setAttribute("xml:lang", "en");
                        $field->setAttribute("lang", "en");
                }else{
                        $field->setAttribute("xml:lang", "it");
                        $field->setAttribute("lang", "it");
                }
        }

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

        function preSelect($fieldID, $value, &$xml){
                $field = $xml -> getElementById($fieldID);
                $options = iterator_to_array($field -> getElementsByTagName("option"));
                for ($i = 0; $i < count($options); $i++) {
                        $attr = $options[$i]->getAttribute("value");
                        if ($attr == $value){
                                $options[$i]->setAttribute("selected", "selected");
                        }
                }
        }

?>
