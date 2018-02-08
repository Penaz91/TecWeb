<?php
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";
        if (session_status() == PHP_SESSION_NONE){
                session_start();
        }
        $target_dir = "Images" . DIRECTORY_SEPARATOR . "foto" . DIRECTORY_SEPARATOR . "strumentazione" . DIRECTORY_SEPARATOR;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $exists = false;
        $toobig = false;
        $formaterr = false;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        //if(isset($_POST["submit"])) {
                //$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                //if($check !== false) {
                        //$uploadOk = 1;
                //} else {
                        //$uploadOk = 0;
                //}
        //}
        if (file_exists($target_file)) {
                $exists = true;
                $uploadOk = 0;
        }
        // MAX 2MB
        if ($_FILES["fileToUpload"]["size"] > 2000000) {
                $toobig = true;
                $uploadOk = 0;
        }
        // Solo jpg, png e jpeg
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $formaterr = true;
                $uploadOk = 0;
        }
        if ($uploadOk == 0) {
                $_SESSION['statussuccess'] = false;
                $_SESSION['statusmessage'] = "";
                if ($toobig){
                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . getMessage("241") . "<br />";
                }
                if ($exists){
                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . getMessage("242") . "<br />";
                }
                if ($formaterr){
                        $_SESSION['statusmessage'] = $_SESSION['statusmessage'] . getMessage("243") . "<br />";
                }
                header("Location: uploadImg.php");
        } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $_SESSION['statussuccess'] = true;
                        $_SESSION['statusmessage'] = getMessage("24");
                        header("Location: uploadImg.php");
                } else {
                        $_SESSION['statussuccess'] = false;
                        $_SESSION['statusmessage'] = getMessage("240");
                        header("Location: uploadImg.php");
                }
        }
?>
