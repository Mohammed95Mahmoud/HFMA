<?php
require_once "ProductInit.php";
require_once "../DataValidation.php";
include "../../Model/Seller.php";
header("Access-Control-Allow-Methods: POST");
$data = ProductInit::getData();
$seller = new Seller(ProductInit::getConn());
$product = ProductInit::getProduct();
if (!empty($data->id)) {
    $result = $product->getAllSellerProduct($data->id);
    $imageUrl = $seller->getStoreLogo($data->id);
    if ($result->rowCount() > 0) {
        $proArr = array();
        $proArr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $proItem = array(
                "description" => $row['description'],
                "id" => $row['id'],
                "image" => $product->getServerDir() . $row['image'],
                "name" => $row['name'],
                "price" => $row['price'],
                "quantity" => $row['quantity'],
                "saller_id" => $row['saller_id'],
                "size" => $row['size'],

            );
            array_push($proArr['data'], $proItem);
        }
        http_response_code(200);
        echo json_encode(array(
            "products" => $proArr,
            "storeLogo" => $seller->getServerDir() . "/" . $imageUrl,
            "flag" => 1

        ));
    } else {
        http_response_code(404);
        echo json_encode(array(
            "message" => "no data found",
            "storeLogo" => $seller->getServerDir() . "/" . $imageUrl,
            "flag" => -1

        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "check your input",
        "flag" => 0

    ));
}