<?php
require_once "CustomerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = CustomerInit::getData();
$customer = CustomerInit::getCustomer();
if (!empty($data->email)
    &&!empty($data->password)){
    echo $customer->signIn($data->email,$data->password);
}
else {
    http_response_code(402);
    echo json_encode(array(
        "message" => "Customer signIn failed! check your data",
        "flag" => 0
    ));
}