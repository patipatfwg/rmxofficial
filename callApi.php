<?php

//กำหนดค่า Access-Control-Allow-Origin ให้ เครื่อง อื่น ๆ สามารถเรียกใช้งานหน้านี้ได้
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
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
    $obj = new stdClass;

    // echo $_SERVER['QUERY_STRING']['userId'];
    // if (isset($_POST['userId']) && !empty($_POST['userId'])) {
    //     $arr = array();
    //     $link = dbConnect();
    //     $id = $_POST['userId'];
    //     $sql = "SELECT * FROM users WHERE userId = $id";
    //     $result = mysqli_query($link, $sql);
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $arr[] = $row;
    //     }
    //     // $obj->LineUserId = "Uae4bfcada214d07661bb5a8779ad4fd3";
    //     // $data = $obj;
    //     $data = $arr;
    // }
    try {
        $obj->LineUserId = $_POST['userId'];
        $data = $obj;
    } catch (\Throwable $th) {
        $data = [];
    }
    echo json_encode($data);
}
