<?php
require_once "ProductInit.php";
require_once "../DataValidation.php";
header("Access-Control-Allow-Methods: POST");
$data = ProductInit::getData();
$product = ProductInit::getProduct();
if (DataValidation::checkProductData($data)) {
    echo $product->addProduct($data);
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "product added failed! check your data",
        "flag" => 0
    ));
}