<?php
require_once "PurchesOrderInit.php";
require_once "../DataValidation.php";

$data = PurchesOrderInit::getData();
$order = PurchesOrderInit::getPurchesOrder();
if ( !empty($data->id)) {
    echo $order->deleteOrder($data->id);
} else {
    http_response_code(404);
    echo json_encode(array(
        "message" => "order deleted failed! check your data",
        "flag" => 0
    ));
}
