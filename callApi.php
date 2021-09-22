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

function callApi($url, $params)
{
    $data = null;
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $params
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); # receive server response
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # do not verify SSL
        $data = curl_exec($ch); # execute curl
        $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # http response status code
        curl_close($ch); # close curl
        // echo "Errors: " . curl_error($ch) . "<br>";
        // echo "Status: " . $httpstatus . "<br>"; 
    } catch (\Throwable $th) {
        $data =  $th;
    }
    return  $data;
}

function get_num_of_words($string, $sign)
{
    $string = preg_replace('/\s+/', ' ', trim($string));
    $words = explode($sign, $string);
    return count($words);
}

function explode_sp_main_select_company($string)
{
    $sign = "^c";
    $count_word = get_num_of_words($string, $sign);
    $arr = explode($sign, $string);
    $out = array();
    for ($i = 0; $i < $count_word; $i++) {
        $out[] = $arr[$i];
    }
    return  $out;
}

function sp_main_select_company()
{
    $url = "http://rmxcell.pe.hu/rmxLineCmd.php";
    $params = "Command=call sp_main_select_company('')";
    $data = callApi($url, $params);
    if ($data != null) {
        $data = trim($data);
        $data = explode_sp_main_select_company($data);
        $data = array("body" => $data, "result" => 200);
        return $data;
    }
}

function select_user($LineId)
{
    try {
        $obj = new stdClass;
        $objData = new stdClass;
        $link = dbConnect();
        $id = $LineId;
        $sql = "SELECT * FROM users WHERE LineId = '$id'";
        $result = mysqli_query($link, $sql);
        $count = mysqli_num_rows($result);
        $boolResult = $count > 0 ? true : false;
        if ($boolResult == true) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $objData->LineId = $id;
            $objData->CustName = $row["CustName"];
            $objData->CustSurName = $row["CustSurName"];
            $objData->EMail = $row["EMail"];
            $objData->MobileNo = $row["MobileNo"];
            $data = $objData;
        } else {
            $data = null;
        }
        $obj->data = $data;
        $obj->result = $boolResult;
        $data = $obj;
    } catch (\Throwable $th) {
        $data = null;
    }
    return $data;
}

function LineUserId()
{
    $data = [];
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
        $data = $th;
    }
    return json_encode($data);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST') {
    $data = [];
    $json = file_get_contents('php://input');
    $json_data = json_decode($json);
    $menutype = $json_data->menutype;

    if ($menutype == 'getUserId') {
        $data = LineUserId();
    } else if ($menutype == 'getCompanyList') {
        $data = sp_main_select_company();
    } else if ($menutype == 'getUser') {
        $CompanyCode = $json_data->CompanyCode;
        if ($CompanyCode == '00001') {
            $LineId = $json_data->LineId;
            $data = select_user($LineId);
        }
    }
    echo json_encode($data);
}
