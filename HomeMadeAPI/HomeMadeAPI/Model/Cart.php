<?php


class Cart

{
    private $conn;
    private $table = 'cart';

    /**
     * Cart constructor.
     * @param $conn
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function addToCart($data)
    {
        $query = "INSERT INTO " . $this->table . "
            SET
                c_id = ?,
                p_id = ?,
                quantity = ?
               ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data->c_id);
        $stmt->bindParam(2, $data->p_id);
        $stmt->bindParam(3, $data->q);
        try {
            if ($stmt->execute()) {
                http_response_code(200);
                return json_encode(array(
                    "message" => "product Add to cart correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "product Add to cart failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "product Add to cart failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }
    public function getCustomerOrdersFromCart($id)
    {
        $q = "SELECT * FROM cart WHERE c_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    function deleteItemFromCart($id){
        $q = "Delete from cart where id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1,$id);
        if($stmt->execute()){
            http_response_code(200);
            return json_encode("item deleted");
        }
        else{
            http_response_code(404);
            return json_encode("item deleted faild!");
        }
    }
    function deleteAllFromCart($id){
        $q = "Delete from cart where c_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1,$id);
        if($stmt->execute()){
            http_response_code(200);
            return json_encode("item deleted");
        }
        else{
            http_response_code(404);
            return json_encode("item deleted faild!");
        }
    }

}