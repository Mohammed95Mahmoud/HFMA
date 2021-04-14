<?php

require_once "SellerInit.php";
require_once "../DataValidation.php";
include "../../Model/Rate.php";
header("Access-Control-Allow-Methods: POST");
$data = SellerInit::getData();
$seller = SellerInit::getSeller();
if (!empty($data->email)) {
    $result = $seller->getSellersById($data->email);
    $sellerData = array();
    $sellerData['data']= array();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $sellerItem = array(
        "full_name" => $row['full_name'],
        "id" => $row['id'],
        "address" => $row['address'],
        "phone_number" => $row['phone_number'],
        "password" => $row['password'],
        "email" => $row['email'],
        "storname" => $row['stor_name'],
        "category" => $row['category'],
        "logo" => $seller->getServerDir() . $row['logo'],
        "type" => $row['type']
    );
    array_push($sellerData['data'], $sellerItem);
    http_response_code(200);
    echo json_encode(array("Seller" => $sellerData, "flag" => 1));
}