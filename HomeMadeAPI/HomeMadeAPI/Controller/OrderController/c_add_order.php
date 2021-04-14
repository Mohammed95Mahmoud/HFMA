<?php
require_once "PurchesOrderInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = PurchesOrderInit::getData();
$purchesOrder = PurchesOrderInit::getPurchesOrder();
if (DataValidation::checkPurchesOrderData($data)) {
    echo $purchesOrder->addOrder($data);
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "order added failed! check your data",
        "flag" => 0
    ));
}