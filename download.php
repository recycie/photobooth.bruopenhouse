<?php
session_start();

function create_users($data)
{
    foreach (file('usertmps.txt') as $line) {
        $n = trim(preg_replace('/\s+/', ' ', $line));
        if ($n == $data) {
            return;
        }
    }

    $f = fopen("usertmps.txt", "a+");
    fwrite($f, $data . "\n");
    fclose($f);
    return;
}

function read_users($data)
{
    foreach (file('usertmps.txt') as $line) {
        $n = trim(preg_replace('/\s+/', ' ', $line));
        if ($n == $data) {
            return "true";
            exit;
        }
    }
    return "false";
    exit;
}

function msg($status, $msg)
{
    return json_encode(array(
        "status" => $status,
        "msg" => $msg,
    ), JSON_UNESCAPED_UNICODE);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $file = "uptmp/" . $id . ".png";
    if (!$file) {
        die('file not found');
    } else {
        create_users($id);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=bru openhouse_' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($file);
        exit;
    }
}

if (isset($_GET['checksession'])) {
    $data = $_GET['checksession'];
    $result = read_users($data);
    if ($result == 'true') {
        echo msg("success", True);
        exit;
    }
    echo msg("error", "empty");
    exit;
}
