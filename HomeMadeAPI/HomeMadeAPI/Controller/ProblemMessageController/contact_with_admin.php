<?php
require_once "ProblemMessageControllerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = ProblemMessageControllerInit::getData();
$message = ProblemMessageControllerInit::getProblemMessage();
if (DataValidation::checkProblemMessageData($data)) {
    echo $message->contactWithAdmin($data);
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "contact with admin failed! check your data",
        "flag" => 0
    ));
}