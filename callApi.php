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

function select_user_from($type, $link, $data)
{
    $sql2 = "SELECT * FROM users WHERE $type = '$data'";
    $result2 = mysqli_query($link, $sql2);
    $count2 = mysqli_num_rows($result2);
    $boolResult2 = $count2 > 0 ? true : false;
    return [$boolResult2, $result2];
}


function select_user($LineId, $EMail, $CompanyCode)
{
    try {
        $data = null;
        $id = $LineId;
        $link = dbConnect();
        $obj = new stdClass;
        $objData = new stdClass;
        $objData->LineId = $id;
        $sql = "SELECT * FROM users WHERE LineId = '$id' AND EMail = '$EMail' AND CompanyCode = '$CompanyCode'";
        $result = mysqli_query($link, $sql);
        $count = mysqli_num_rows($result);
        $boolResult = $count > 0 ? true : false;
        if ($boolResult == true) {
            $data = $objData;
            $txtResult = "Duplicate";
        } else if ($boolResult == false) {
            $txtResult = "New";
            $ResultEMail = select_user_from('EMail', $link, $EMail);
            if ($ResultEMail[0] == false) {
                $ResultLineId = select_user_from('LineId', $link, $LineId);
                if ($ResultLineId[0] == false) {
                    $txtResult = "Not Found User";
                } else  if ($ResultLineId[0] == true) {
                    $ResultData = $ResultLineId[1];
                }
            } else if ($ResultEMail[0] == true) {
                $ResultData = $ResultEMail[1];
            }

            if ($txtResult == "New") {
                $row = $ResultData->fetch_array(MYSQLI_ASSOC);
                $objData->CustName = $row["CustName"];
                $objData->CustSurName = $row["CustSurName"];
                $objData->MobileNo = $row["MobileNo"];
                if ($ResultEMail[0] == true) {
                    $objData->EMail =  $EMail;
                } else {
                    $objData->EMail = $row["EMail"];
                }
                $data = $objData;
            }
        }
        $obj->body = $data;
        $obj->result = $txtResult;
        $data = $obj;
    } catch (\Throwable $th) {
        $data = null;
    }
    return $data;
}

function save_user()
{
    $obj = new stdClass;
    $obj->result = 200;
    try {
        $json = file_get_contents('php://input');
        $json_data = json_decode($json);
        $link = dbConnect();
        $sql = "INSERT INTO users (LineId, CustName, CustSurName,EMail,MobileNo,CompanyCode) VALUES ('$json_data->LineId', '$json_data->CustName', '$json_data->CustSurName','$json_data->EMail','$json_data->MobileNo','$json_data->CompanyCode')";
        if ($link->query($sql) === TRUE) {
            $obj->body = "New record created successfully";
        } else {
            $obj->body = "Error: " . $sql . "<br>" . $link->error;
        }
    } catch (\Throwable $th) {
        $obj->body =  $th;
        $obj->result = 400;
    }
    $data = $obj;
    return  $data;
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

function CallApiLine($LINEID, $type)
{
    $RICHMENUID = "richmenu-db181b79c35a2d6bfb2aaa286bbe95fd";
    if ($type == 'member') {
        $CURLOPT = CURLOPT_POST;
        $url = "https://api.line.me/v2/bot/user/$LINEID/richmenu/$RICHMENUID";
        $data = array();
        $method = "POST";
    } else if ($type == 'logout') {
        $url = "https://api.line.me/v2/bot/user/$LINEID/richmenu";
        $data = "{\"userIds\":[\"$LINEID\"]}";
        $method = "DELETE";
    }

    $headers = [
        "Authorization: Bearer s2l19GfGgdDnsbO9cidJGvlkKDvlT9MRiQla/SKo63c3Us7Tv/xKjLnkLnafX15C3U9N9AT5FiL/ARZHWhicfAqm7bSmB1TJWFAzYkBxgSdZbHVKMag6WdTUtnsb56UmvcwbxVq5WUiRzRfTcLTv9QdB04t89/1O/w1cDnyilFU=", "Content-Type: application/json"
    ];
    try {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        // curl_setopt($ch, $CURLOPT, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $data
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); # receive server response
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # do not verify SSL
        $data = curl_exec($ch); # execute curl
        $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # http response status code
        curl_close($ch);

        $data = "{}";
    } catch (Exception $ex) {
        $data = $ex;
    }
    return $data;
}

function callApiFlexMessage(){
    
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
        $LineId = $json_data->LineId;
        $EMail = $json_data->EMail;
        $data = select_user($LineId, $EMail, $CompanyCode);
    } else if ($menutype == 'saveUser') {
        $data = save_user();
    } else if ($menutype == 'lineApi') {
        $LineId = $json_data->LineId;
        $type = $json_data->type;
        CallApiLine($LineId, $type);
    }
    echo json_encode($data);
} else {
    function sendMessage($replyJson, $sendInfo)
    {
        $ch = curl_init($sendInfo["URL"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $sendInfo["AccessToken"]
            )
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $replyJson);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    $LINEData = file_get_contents('php://input');
    $jsonData = json_decode($LINEData, true);

    $Name = "HIHI";
    $Surname = "GOGO";
    $replyText["text"] = "สวัสดีคุณ $Name $Surname";

    $lineData['URL'] = "https://api.line.me/v2/bot/message/reply";
    $lineData['AccessToken'] = "s2l19GfGgdDnsbO9cidJGvlkKDvlT9MRiQla/SKo63c3Us7Tv/xKjLnkLnafX15C3U9N9AT5FiL/ARZHWhicfAqm7bSmB1TJWFAzYkBxgSdZbHVKMag6WdTUtnsb56UmvcwbxVq5WUiRzRfTcLTv9QdB04t89/1O/w1cDnyilFU=";

    $replyJson["replyToken"] = $replyToken;
    $replyJson["messages"][0] = $replyText;

    $encodeJson = json_encode($replyJson);

    
    $results = sendMessage($encodeJson, $lineData);
    echo $results;
    http_response_code(200);
}
