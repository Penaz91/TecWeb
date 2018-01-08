<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "dbconn.php";
        use DBAccess;

        session_start();
        $dbAccess = new DBAccess();
        $dbconn = $dbAccess->openDBConnection();
        unset($_SESSION['Rusername']);
        unset($_SESSION['reload']);
        unset($_SESSION['Remail']);
        unset($_SESSION['RuserErr']);
        unset($_SESSION['RpassErr']);
        unset($_SESSION['RpassErr2']);
        unset($_SESSION['RemailErr']);
        unset($_SESSION['RemailErr2']);
        unset($_SESSION['RtelErr']);

        $_SESSION['Rusername']=$_POST['Rusername'];
        if ($dbAccess->checkUser($_SESSION['Rusername'])){
                $_SESSION['RuserErr']=true;
                $_SESSION['reload']=true;
        }
        $_SESSION['Remail']=$_POST['Remail'];
        if ($dbAccess->checkMail($_SESSION['Remail'])){
                $_SESSION['RemailErr'] = true;
                $_SESSION['reload']=true;
        }
        if (!preg_match("/^([\w\+\-]+\.?[\w\+\-\.]*)\@([\w\+\-]+)\.([\w\+\-]+)$/", $_SESSION['Remail'])){
                $_SESSION['RemailErr2'] = true;
                $_SESSION['reload'] = true;
        }
        $_SESSION['Rtel'] = $_POST['Rtel'];
        if (!preg_match("/^\d{6,11}$/", $_SESSION['Rtel'])){
                $_SESSION['RtelErr'] = true;
                $_SESSION['reload'] = true;
        }
        if (empty($_POST['Rpwd']) && empty($_POST['Rpwd2'])){
                $_SESSION['RpassErr2'] = true;
                $_SESSION['reload'] = true;
        }
        if ($_POST['Rpwd'] != $_POST['Rpwd2']){
                $_SESSION['RpassErr'] = true;
                $_SESSION['reload'] = true;
        }
        if(isset($_SESSION['reload']) && $_SESSION['reload'] == true){
                $dbAccess->closeDBConnection();
                header("Location: registrazione.php");
        }else{
                if ($dbAccess->addUser($_POST['Rusername'], $_POST['Remail'], $_POST['Rtel'], $_POST['Rpwd'])){
                        $dbAccess->closeDBConnection();
                        header("Location: registrazione_ok.html");
                }else{
                        $dbAccess->closeDBConnection();
                        $_SESSION['reload']=true;
                        header("Location: registrazione_ko.html");
                }
        }
?>
