<?php
//database configurations
session_start();
define('SERVER', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('DATABASE', 'contactlist');

//anti injections
function clearInputs($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


//check images
function checkImage($image)
{
    global $uploadOk;
    $target_dir = "contact-images";
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = false;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //check for submit before
    //check if real image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $uploadOk = true;
    } else {
        global $imageError;
        $imageError = "File is not a real image.";
        $uploadOk = false;
        return $uploadOk;
    }
    //check size 5mb limit
    if ($image["size"] > 5242880) {
        global $imageError;
        $imageError = "Sorry, your file is too large. Max size = 5MB";
        $uploadOk = false;
        return $uploadOk;


        //check if supported file format .png JPG e JPEG
    } elseif (
        $fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
    ) {
        global $imageError;
        $imageError = "Only .jpeg | .jpg | .png  files are allowed.";
        $uploadOk = false;
        return $uploadOk;
    }
    return $uploadOk;
}
