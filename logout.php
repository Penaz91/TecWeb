<?php
        session_start();
        $sname=session_name();
        session_destroy();
        if (isset($_COOKIE[$sname])){
                setcookie($sname, '', time()-3600, '/');
        };
        header("Location: index.php");
?>
