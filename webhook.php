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

function orderDetailRow($orderArr)
{
    $title = array("Ticket No.", "Ticket Date", "Order No.", "Order Date", "Ship To", "Product Name", "Plant Name", "Order Qty.", "Ticket Qty.", "Driver Name", "Truck No.", "License Plate", "Leave Time", "Ship Condition", "Ticket Status");
    $countTitle = count($title);

    $objDetailRowA = new stdClass;
    $objDetailBaselineTitle = new stdClass;
    $objDetailBaselineValue = new stdClass;
    $objDetailRowA2 = new stdClass;

    $a = [];


    foreach ($title as $titlevalue) {
        //Title
        $objDetailBaselineTitle->type = "text";
        $objDetailBaselineTitle->text = $titlevalue;
        $objDetailBaselineTitle->size = "sm";
        $objDetailBaselineTitle->color = "#AAAAAA";
        $objDetailBaselineTitle->weight = "bold";
        $objDetailBaselineTitle->flex = 2;
        $objDetailBaselineTitle->contents = [];

        //Value
        $objDetailBaselineValue->type = "text";
        $objDetailBaselineValue->text = 'abc';
        $objDetailBaselineValue->size = "sm";
        $objDetailBaselineValue->color = "#666666";
        $objDetailBaselineValue->flex = 4;
        $objDetailBaselineValue->wrap = true;
        $objDetailBaselineValue->align = "end";
        $objDetailBaselineValue->contents = [];

        $b = [$objDetailBaselineTitle, $objDetailBaselineValue];

        array_push($a,$b);
    }

    $objDetailRowA->type = "box";
    $objDetailRowA->layout = "baseline";
    $objDetailRowA->spacing = "sm";
    $objDetailRowA->contents = [];

    return $objDetailRowA;
}

function orderDetail()
{
    $objSeparator = new stdClass;
    $objSeparator->type = "separator";

    $objTitleH1 = new stdClass;
    $objTitleH1->type = "text";
    $objTitleH1->text = "Order Detail";
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
    $objDetail->contents = [orderDetailRow("S01P901-00000331")];

    $output = array(
        $objTitleH1,
        $objSeparator,
        $objDetail
    );

    return $output;
}

function flexLayout()
{
    $replyText["type"] = "flex";
    $replyText["altText"] =  "Order Detail";
    $replyText["contents"]["type"] = "bubble";
    $replyText["contents"]["body"]["type"] = "box";
    $replyText["contents"]["body"]["layout"] = "vertical";
    $replyText["contents"]["body"]["spacing"] = "sm";
    $replyText["contents"]["body"]["contents"] = orderDetail();

    return $replyText;
}

function NewOrderForm()
{
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
$replyJson["messages"][0] = flexLayout();
$encodeJson = json_encode($replyJson);

if ($ActionMenuText == 'status') {
    $results = sendMessage($encodeJson);
    echo $results;
    http_response_code(200);
}
