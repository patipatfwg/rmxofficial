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

$LINEData = file_get_contents('php://input');
$jsonData = json_decode($LINEData, true);

$replyToken = $jsonData["events"][0]["replyToken"];
$replyUserId = $jsonData["events"][0]["source"]["userId"];
$MessageType = $jsonData["events"][0]["message"]["type"];
$MessageText = $jsonData["events"][0]["message"]["text"];


$replyText["type"] = "flex";
$replyText["altText"] =  "Q1. Which is the API to create chatbot?";
$replyText["contents"]["type"] = "bubble";
$replyText["contents"]["body"]["type"] = "box";
$replyText["contents"]["body"]["layout"] = "vertical";
$replyText["contents"]["body"]["spacing"] = "md";
$replyText["contents"]["body"]["contents"] = [];

$replyJson["to"] = $replyUserId;
$replyJson["replyToken"] = $replyToken;
$replyJson["messages"][0] = $replyText;
$encodeJson = json_encode($replyJson);

if ($MessageText == 'Cartoon API') {
    $results = sendMessage($encodeJson);
    echo $results;
    http_response_code(200);
}
