<?php
require_once "PurchesOrderInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = PurchesOrderInit::getData();
$order = PurchesOrderInit::getPurchesOrder();
if (!empty($data->id)) {
    $result = $order->getCustomerOrders($data->id);
    if ($result->rowCount() > 0) {
        $orderArr = array();
        $orderArr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $details = json_decode($row['details']);
            $orderItem = array(
                "id" => $row['id'],
                "time" => $row['time'],
                "date" => $row['date'],
                "state" => $row['state'],
                "customer_id" => $row['customer_id'],
                "details" => $details,
                "total" => $row['total']
            );
            array_push($orderArr['data'], $orderItem);
        }
        http_response_code(200);
        echo json_encode(array(
            "orders" => $orderArr,
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