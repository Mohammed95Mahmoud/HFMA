<?php
include "../../config/headers.php";
header("Access-Control-Allow-Methods: POST");
include "../../config/Database.php";
include "../../Model/Cart.php";
include "../../Model/Product.php";
$data = json_decode(file_get_contents("php://input"));
$db = new Database();
$conn = $db->connect();
$cart = new Cart($conn);
$p = new Product($conn);
if (!empty($data->c_id)) {
    $result = $cart->getCustomerOrdersFromCart($data->c_id);
    $num = $result->rowCount();
    if ($num > 0) {
        $cartArr = array();
        $cartArr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $p_id = $row['p_id'];
            $productData = $p->getProduct($p_id);
            if ($productData->rowCount() != 0) {
                $row2 = $productData->fetch(PDO::FETCH_ASSOC);
                $cartItem = array(
                    "cartId" => $row['id'],
                    "p_id" => $p_id,
                    "seller_id" => $row2['saller_id'],
                    "name" => $row2['name'],
                    "image" => $p->getServerDir() . $row2['image'],
                    "price" => $row2['price'],
                    "oldQ" => $row2['quantity'],
                    "cartQ" => $row['quantity'],
                );
                array_push($cartArr['data'], $cartItem);
            }
        }
        http_response_code(200);
        echo json_encode(array(
            "Cart" => $cartArr,
            "flag" => 1
        ));
    } else {
        http_response_code(404);
        echo json_encode(array(
            "message" => "no data",
            "flag" => -1
        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "check your data",
        "flag" => 0
    ));
}