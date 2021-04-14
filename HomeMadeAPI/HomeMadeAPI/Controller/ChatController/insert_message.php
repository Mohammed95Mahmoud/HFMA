<?php
require_once "ChatInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = ChatInit::getData();
$chat = ChatInit::getChat();
if (DataValidation::checkChatData($data)) {
    echo $chat->addMessage($data);
} else {
    http_response_code(401);

    echo json_encode(array(
        "message" => "message added failed! check your data",
        "flag" => 0
    ));
}
