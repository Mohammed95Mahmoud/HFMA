<?php
include "../../config/headers.php";
header("Access-Control-Allow-Methods: POST");
include "../../config/Database.php";
include "../../Model/Cart.php";
$data = json_decode(file_get_contents("php://input"));
$db = new Database();
$conn = $db->connect();
$cart = new Cart($conn);
if (!empty($data->c_id)&&!empty($data->p_id)&&!empty($data->q)) {
    echo $cart->addToCart($data);
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "order added to cart failed! check your data",
        "flag" => 0
    ));
}
