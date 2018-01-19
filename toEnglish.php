<?php
        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $_SESSION['language']='en';
        header('Location: ' . $_GET['ref']);
?>
