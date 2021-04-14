<?php
require_once "CustomerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = CustomerInit::getData();
$customer = CustomerInit::getCustomer();
if(DataValidation::checkCustomerData($data)){
    echo $customer->signUp($data);
}
else{
    http_response_code(402);
    echo json_encode(array(
        "message"=>"Customer registered failed! check your data",
        "flag"=>0
    ));
}