<?php
function sendMessage($replyJson)
{
    $sendInfo['URL'] = "https://api.line.me/v2/bot/message/push";

    $sendInfo['AccessToken'] = "s2l19GfGgdDnsbO9cidJGvlkKDvlT9MRiQla/SKo63c3Us7Tv/xKjLnkLnafX15C3U9N9AT5FiL/ARZHWhicfAqm7bSmB1TJWFAzYkBxgSdZbHVKMag6WdTUtnsb56UmvcwbxVq5WUiRzRfTcLTv9QdB04t89/1O/w1cDnyilFU=";

    try {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sendInfo['URL']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $replyJson
        );
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $sendInfo["AccessToken"]
            )
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); # receive server response
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # do not verify SSL
        $data = curl_exec($ch); # execute curl
        $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # http response status code
        curl_close($ch);

        $data = $data;
    } catch (Exception $ex) {
        $data = $ex;
    }
    return $data;
}

function ticketDetailRowLayout($title, $val)
{
    $objDetailRow = new stdClass;
    $objDetailBaselineTitle = new stdClass;
    $objDetailBaselineValue = new stdClass;

    //Title
    $objDetailBaselineTitle->type = "text";
    $objDetailBaselineTitle->text = $title;
    $objDetailBaselineTitle->size = "sm";
    $objDetailBaselineTitle->color = "#AAAAAA";
    $objDetailBaselineTitle->weight = "bold";
    $objDetailBaselineTitle->flex = 2;
    $objDetailBaselineValue->wrap = true;
    $objDetailBaselineTitle->contents = [];

    //Value
    $objDetailBaselineValue->type = "text";
    $objDetailBaselineValue->text = $val;
    $objDetailBaselineValue->size = "sm";
    $objDetailBaselineValue->color = "#666666";
    $objDetailBaselineValue->flex = 4;
    $objDetailBaselineValue->wrap = true;
    $objDetailBaselineValue->align = "end";
    $objDetailBaselineValue->contents = [];

    $contentsList = [$objDetailBaselineTitle, $objDetailBaselineValue];

    $objDetailRow->type = "box";
    $objDetailRow->layout = "baseline";
    $objDetailRow->spacing = "sm";
    $objDetailRow->contents = $contentsList;

    return $objDetailRow;
}

function selectTicketDetail()
{
    $data = [];
    $title = array("Ticket No.", "Ticket Date", "Order No.", "Order Date", "Ship To", "Product Name", "Plant Name", "Order Qty.", "Ticket Qty.", "Driver Name", "Truck No.", "License Plate", "Leave Time", "Ship Condition", "Ticket Status");
    $arrVal = array("1011808270007", "24/10/2018", "S01P901-00000331", "27/08/2018", "320000106 SH_Name 105", "997525133500 WPROOF PMP 25MPa 25mm S120 25@7DWPC1", "cV101 RMX Plant 101", "78", "2", "Theary Theary_", "FS22", "51E00491", "16:54:43", "Delivery", "5");

    for ($i = 0; $i < count($title); $i++) {
        array_push($data, ticketDetailRowLayout($title[$i], $arrVal[$i]));
    }

    return $data;
}

function ticketDetailFlexMessage()
{
    $objSeparator = new stdClass;
    $objSeparator->type = "separator";

    $objTitleH1 = new stdClass;
    $objTitleH1->type = "text";
    $objTitleH1->text = "Ticket Detail";
    $objTitleH1->weight = "bold";
    $objTitleH1->color = "#B6961EFF";
    $objTitleH1->size = "xl";
    $objTitleH1->wrap = true;
    $objTitleH1->contents = [];

    $objDetail = new stdClass;
    $objDetail->type = "box";
    $objDetail->layout = "vertical";
    $objDetail->spacing = "md";
    $objDetail->margin = "lg";
    $objDetail->contents = selectTicketDetail();

    $output = array($objTitleH1, $objSeparator, $objDetail);

    $replyText["type"] = "flex";
    $replyText["altText"] =  "Ticket Detail";
    $replyText["contents"]["type"] = "bubble";
    $replyText["contents"]["body"]["type"] = "box";
    $replyText["contents"]["body"]["layout"] = "vertical";
    $replyText["contents"]["body"]["spacing"] = "sm";
    $replyText["contents"]["body"]["contents"] = $output;

    return $replyText;
}

$LINEData = file_get_contents('php://input');
$jsonData = json_decode($LINEData, true);

$replyToken = $jsonData["events"][0]["replyToken"];
$replyUserId = $jsonData["events"][0]["source"]["userId"];
$MessageType = $jsonData["events"][0]["message"]["type"];
$MessageText = $jsonData["events"][0]["message"]["text"];

$postbackParams = $jsonData["events"][0]["postback"]["data"];
parse_str($postbackParams, $arr);
$ActionMenuText = $arr["action"];

$replyJson["to"] = $replyUserId;
$replyJson["replyToken"] = $replyToken;
$replyJson["messages"][0] = ticketDetailFlexMessage();
$encodeJson = json_encode($replyJson);

if ($ActionMenuText == 'status') {
    $results = sendMessage($encodeJson);
    echo $results;
    http_response_code(200);
}
