<?php
require_once "SellerInit.php";
require_once "../DataValidation.php";
include "../../Model/Rate.php";

$rate = new Rate(SellerInit::getConn());
header("Access-Control-Allow-Methods: POST");
$data = SellerInit::getData();
$seller = SellerInit::getSeller();
$result = $seller->getAllSellers();
if ($result->rowCount() > 0) {
    $proArr = array();
    $proArr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $r = $rate->getSellerRate($row['email']);
        $proItem = array(
            "full_name" => $row['full_name'],
            "id" => $row['id'],
            "address" => $row['address'],
            "phone_number" => $row['phone_number'],
            "password" => $row['password'],
            "email" => $row['email'],
            "stor_name" => $row['stor_name'],
            "category" => $row['category'],
            "logo" => $seller->getServerDir() . $row['logo'],
            "type" => $row['type'],
            "rate" => $r
        );
        array_push($proArr['data'], $proItem);
    }
    http_response_code(201);
    echo json_encode(array(
        "sellers" => $proArr,
        "flag" => 1

    ));
} else {
    http_response_code(404);
    echo json_encode(array(
        "message" => "no data found",
        "flag" => -1

    ));
}