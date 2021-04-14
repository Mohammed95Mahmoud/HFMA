<?php

error_reporting(E_ALL);
ini_set('display_errors','On');
require_once "SellerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = SellerInit::getData();
$seller = SellerInit::getSeller();

if (DataValidation::checkSellerData($data)) {
    echo $seller->signUp($data);
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "seller registered failed! check your data",
        "flag" => 0
    ));
}