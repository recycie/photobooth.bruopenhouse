<?php
include("config.php");
function loadConfig($CONFIGFILE){
    return file_exists($CONFIGFILE) ? json_decode(file_get_contents($CONFIGFILE), true) : null;
}

function saveConfig($CONFIGFILE){
    if (isset($_POST['config'])) {
        $config = $_POST['config'];

        try{
            file_put_contents($CONFIGFILE, $config);
            if (isset($_POST['image'])) {
                $imageData = $_POST['image'];
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $imageFile = '../images/frame.png'; // Set the path where you want to save the image
                file_put_contents($imageFile, base64_decode($imageData));
            }
            echo "succeess";
        }catch(Exception $error){
            echo "Error: ". $error->getMessage();
        }
        die;
    }
}

?>
