<?php
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

?>