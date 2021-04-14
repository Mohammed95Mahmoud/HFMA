<?php
require_once "CustomerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = CustomerInit::getData();
$customer = CustomerInit::getCustomer();
if (!empty($data->email)){
    echo $customer->getInfo($data->email);
}else {
    http_response_code(402);
    echo json_encode(array(
        "message" => "Customer not exist! check your data",
        "flag" => 0
    ));
}