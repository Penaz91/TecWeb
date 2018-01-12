<?php
        session_start();
        $_SESSION['language']='it';
        header('Location: ' . $_GET['ref']);
?>
