<?php
require_once "RateInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = RateInit::getData();
$rate = RateInit::getRate();
if(DataValidation::checkRateData($data)){
    echo $rate->setRate($data);
}
else{
    http_response_code(402);
    echo json_encode(array(
        "message"=>"rating failed! check your data",
        "flag"=>0
    ));
}