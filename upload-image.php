<?php

//
function checkImage($image)
{
    $target_dir = "contact-images";
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = false;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //check for submit before
    //check if real image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = true;
    } else {
        echo "File is not an image.";
        $uploadOk = false;
        return $uploadOk;
    }
    //check size 5mb limit
    if ($image["size"] > 500000) {
        echo "Sorry, your file is too large. Max size = 5MB";
        $uploadOk = false;
        return $uploadOk;


        //check if supported file format .png JPG e JPEG
    } elseif (
        $fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
    ) {
        echo "Sorry, only JPG, JPEG & PNG  files are allowed.";
        $uploadOk = false;
        return $uploadOk;
    }
    return $uploadOk;
}
