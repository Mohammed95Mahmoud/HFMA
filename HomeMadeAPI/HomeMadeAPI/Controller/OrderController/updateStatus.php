<?php
require_once "PurchesOrderInit.php";
require_once "../DataValidation.php";
require_once  "../../Model/Product.php";
header("Access-Control-Allow-Methods: POST");
$data = PurchesOrderInit::getData();
$order = PurchesOrderInit::getPurchesOrder();
if (!empty($data->newState)&&!empty($data->orderId)) {
echo $order->updateStatus($data->newState,$data->orderId);
} else {
    http_response_code(204);
    echo json_encode(array(
        "message" => "check your input",
        "flag" => 0

    ));
}