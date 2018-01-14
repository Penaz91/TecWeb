<?php
        session_start();
        $_SESSION['language']='en';
        header('Location: ' . $_GET['ref']);
?>
