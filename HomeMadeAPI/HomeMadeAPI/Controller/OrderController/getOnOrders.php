<?php
require_once "PurchesOrderInit.php";
require_once "../DataValidation.php";
require_once  "../../Model/Product.php";
header("Access-Control-Allow-Methods: POST");
$data = PurchesOrderInit::getData();
$order = PurchesOrderInit::getPurchesOrder();
if (!empty($data->seller_id)) {
    echo $order->getOnOrders($data->seller_id);
} else {
    http_response_code(200);
    echo json_encode(array(
        "message" => "check your input",
        "flag" => 0

    ));
}