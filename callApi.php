<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function dbConnect()
{
    $host = "ro2padgkirvcf55m.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
    $user = 's4jzbnhpni87hgpe';
    $pass = 'sun081re7jba86k1';
    $dbname = 'yu0zqs0841zi5mza';
    $link = mysqli_connect($host, $user, $pass, $dbname);
    mysqli_set_charset($link, 'utf8');
    return $link;
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST') {
    try {
        $json = file_get_contents('php://input');
        $json_data = json_decode($json);
        $obj = new stdClass;
        $link = dbConnect();
        $id = $json_data->userId;
        $sql = "SELECT * FROM users WHERE userId = '$id'";
        $result = mysqli_query($link, $sql);
        $count = mysqli_num_rows($result);
        $boolResult = $count > 0 ? true : false;

        $obj->LineUserId = $id;
        $obj->result = $boolResult;
        $data = $obj;
    } catch (\Throwable $th) {
        $data = [];
    }
    echo json_encode($data);
}
