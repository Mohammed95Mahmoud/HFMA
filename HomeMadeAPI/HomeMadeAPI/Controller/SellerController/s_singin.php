<?php
require_once "SellerInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = SellerInit::getData();
$seller = SellerInit::getSeller();
if (!empty($data->email)
    &&!empty($data->password)){
   echo $seller->signIn($data->email,$data->password);
}
else {
    http_response_code(402);
    echo json_encode(array(
        "message" => "seller signIn failed! check your data",
        "flag" => 0
    ));
}