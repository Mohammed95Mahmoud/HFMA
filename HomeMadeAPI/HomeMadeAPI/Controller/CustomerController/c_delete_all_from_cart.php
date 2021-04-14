<?php
include "../../config/headers.php";
header("Access-Control-Allow-Methods: POST");
include "../../config/Database.php";
include "../../Model/Cart.php";
$db = new Database();
$conn = $db->connect();
$c = new Cart($conn);
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id)) {
    echo $c->deleteAllFromCart($data->id);
} else {
    http_response_code(404);
    echo json_encode(array(
        "message" => "all item deleted failed! check your data",
        "flag" => 0
    ));
}