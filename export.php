<?php
error_reporting(E_ALL);
ini_set('display_errors', 'off');

function msg($status, $msg){
    return json_encode(array(
        "status" => $status,
        "msg" => $msg,
    ), JSON_UNESCAPED_UNICODE);
}

function guidv4($data){
    assert(strlen($data) == 16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

if(isset($_POST['export'])){
    $data = $_POST['data'];
    $picid = $_POST['id'];
    $img = substr(explode(";",$data)[1], 7);
    if ($picid == ""){
        $uuid = guidv4(openssl_random_pseudo_bytes(16));
    }else{
        $uuid = $picid;
    }

    if(file_put_contents("uptmp/".$uuid.".png", base64_decode($img))){
        echo msg("success", "$uuid");
        exit;
    }
    echo "error";
    exit;
}

?>
