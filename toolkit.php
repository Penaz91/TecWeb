<?php
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

        function setUserStatus(&$content){
                $_SESSION['tabindex'] = 4;
                if (empty($_SESSION['username'])){
                        if ($_SESSION['language']=='en'){
                                $repl = "<li class='specialbtn'><a href='login.php' tabindex='4'><span xml:lang='en'>Sign in</span> | Sign Up</a></li>";
                        }else{
                                $repl = "<li class='specialbtn'><a href='login.php' tabindex='4'><span xml:lang='en'>Login</span> | Registrazione</a></li>";
                        }
                        $_SESSION['tabindex'] = 5;
                }else{
                        $uname = $_SESSION['username'];
                        if ($_SESSION['language']=='en'){
                                $repl = "<li class='specialbtn'><span class='lefticon' id='userlogged'>$uname</span><a href='userpanel.php' tabindex='4'>User panel and Bookings</a><a href='logout.php' tabindex='5'><span xml:lang='en'>Logout</span></a></li>";
                        }else{
                                $repl = "<li class='specialbtn'><span class='lefticon' id='userlogged'>$uname</span><a href='userpanel.php' tabindex='4'>Pannello Utente</a><a href='logout.php' tabindex='5'><span xml:lang='en'>Logout</span></a></li>";
                        }
                        $_SESSION['tabindex'] = 6;
                }
                $content = str_replace("<!--STATOUTENTE-->", $repl, $content);
        }

        function setAdminArea(&$content){
                $repl = "";
                if (isset($_SESSION['admin']) && $_SESSION['admin']==1){
                        if ($_SESSION['language']=='en'){
                                $repl="<li class='specialbtn' id='admin'><a href='admin.php' tabindex='" . $_SESSION['tabindex'] . "'>Admin Area</a></li>";
                        }else{
                                $repl="<li class='specialbtn' id='admin'><a href='admin.php' tabindex='" . $_SESSION['tabindex'] . "'>Area Amministrazione</a></li>";
                        }
                        $_SESSION['tabindex']++;
                }
                $content = str_replace("<!--ADMINAREA-->", $repl, $content);
        }

        function setLangArea(&$content, $ref){
                $repl = "";
                        if ($_SESSION['language']=='en'){
                                $repl="<li class='specialbtn'><a href='toItalian.php?ref=" . $ref . "' tabindex='" . $_SESSION['tabindex'] . "'>Versione Italiana</a></li>";
                        }else{
                                $repl="<li class='specialbtn'><a href='toEnglish.php?ref=" . $ref . "' tabindex='" . $_SESSION['tabindex'] . "'>English Version</a></li>";
                        }
                $content = str_replace("<!--LANGAREA-->", $repl, $content);
        }

        function setupMenu(&$content, $activeId){
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
                        if($i == $activeId){
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
                $menuText = str_replace("<container>", "", $menuText);
                $menuText = str_replace("</container>", "", $menuText);
                $content = str_replace("<!--MENU-->", $menuText, $content);
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
                        header("Location: accesso_negato.html");
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
                if (empty($_SESSION['admin']) ||$_SESSION['admin']==false){
                        header("Location: accesso_negato.html");
                        exit();
                }
        }

        function checkDateInput($date){
                if (!preg_match("/^(?<d>\d{2})\/(?<m>\d{2})\/(?<Y>\d{4})$/", $date, $match)){
                        $_SESSION['dateerrors'] = $_SESSION['dateerrors'] . "La data deve essere nel formato gg/mm/aaaa <br />";
                }else{
                        $currY = date("Y");
                        if ($match['Y'] < $currY){
                                $_SESSION['dateerrors'] = $_SESSION['dateerrors'] . "La data fa riferimento ad un anno passato <br />";
                        }
                        if (!checkDate($match['m'], $match['d'], $match['Y'])){
                                $_SESSION['dateerrors'] = $_SESSION['dateerrors'] . "La data inserita non esiste<br />";
                        }
                }
                return (empty($_SESSION['dateerrors']));
        }

        function checkTimeInput($time){
                if (!preg_match("/^(?<hour>\d\d):(0{2})$/", $time, $match)){
                        $_SESSION['timeerrors'] = $_SESSION['timeerrors'] . "L'ora deve essere nel formato hh:00 (Non sono ammesse mezz'ore) <br />";
                }else{
                        if ($match['hour'] > 23 || $match['hour'] < 0){
                                $_SESSION['timeerrors'] = $_SESSION['timeerrors'] . "L'ora inserita non è valida <br />";
                }
                        return (empty($_SESSION['timeerrors']));

                }
        }

        function checkDurationInput($dur){
                if (!preg_match("/^(?<dur>\d+)$/", $dur, $match)){
                        $_SESSION['durationerrors'] = "La durata deve essere un intero maggiore o uguale ad 1.<br />";
                }
                if ($match['dur'] <= 0){
                        $_SESSION['durationerrors'] = "La durata deve essere un intero maggiore o uguale ad 1.<br />";
                }
                return (empty($_SESSION['durationerrors']));
        }

        function addStylesheet($path, $media, &$content){
                $content = str_replace("<!--STYLESHEETS-->",
                        '<link rel="stylesheet" href="' . $path . '" type="text/css" media="' . $media . '" charset="utf-8" />' . "\r\n" .'<!--STYLESHEETS-->',
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
                if ($_SESSION['language']=='en'){
                        preg_match("/^(?<name>\w*).(?<ext>\w*)$/", $filename, $match);
                        $filename = $match['name'] . "_EN." . $match['ext'];
                        $filename = 'Traduzioni/' . $filename;
                }
                return $filename;
        }
?>
