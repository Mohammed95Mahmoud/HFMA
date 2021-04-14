<?php
require_once "ChatInit.php";
require_once "../DataValidation.php";
require_once "../../Model/Chat.php";
header("Access-Control-Allow-Methods: POST");
$data = ChatInit::getData();
$message = ChatInit::getChat();
if (!empty($data->id)) {
    $result = $message->getReceiverMessage($data->id);
    if ($result->rowCount() > 0) {
        $messageArr = array();
        $messageArr['msg'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $messageItem = array(
                "id" => $row['id'],
                "time" => $row['time'],
                "date" => $row['date'],
                "sender_id" => $row['sender_id'],
                "message" => json_decode($row['message']),
                "receiver_id" => $row['receiver_id'],

            );
            array_push($messageArr['msg'], $messageItem);
        }
        http_response_code(200);
        echo json_encode(array(
            "message" => $messageArr,
            "flag" => 1

        ));
    } else {
        http_response_code(200);
        echo json_encode(array(
            "message" => "no data found",
            "flag" => -1

        ));
    }
} else {
    http_response_code(200);
    echo json_encode(array(
        "message" => "check your input",
        "flag" => 0

    ));
}
