<?php
require_once "SellerInit.php";
require_once "../DataValidation.php";
include "../../Model/Rate.php";

$rate = new Rate(SellerInit::getConn());
header("Access-Control-Allow-Methods: POST");
$data = SellerInit::getData();
$seller = SellerInit::getSeller();
$result = $rate->getAll();
if ($result->rowCount() > 0) {
    $proArr = array();
    $proArr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $s_result = $seller->getSellersById($row['saller_id']);
        while ($row2 = $s_result->fetch(PDO::FETCH_ASSOC)) {
            $proItem = array(
                "full_name" => $row2['full_name'],
                "id" => $row2['id'],
                "address" => $row2['address'],
                "phone_number" => $row2['phone_number'],
                "password" => $row2['password'],
                "email" => $row2['email'],
                "stor_name" => $row2['stor_name'],
                "category" => $row2['category'],
                "type" => $row2['type'],
                "logo" => $seller->getServerDir() . "/" . $row2['logo'],
                "rate" => $row['rate']
            );
            array_push($proArr['data'], $proItem);
        }
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