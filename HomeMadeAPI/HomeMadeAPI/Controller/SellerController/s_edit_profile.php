<?php
require_once "SellerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = SellerInit::getData();
$seller = SellerInit::getSeller();
if (DataValidation::checkSellerUpdateData($data)) {
    echo $seller->editSeller($data);
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "seller edit failed! check your data",
        "flag" => 0
    ));
}