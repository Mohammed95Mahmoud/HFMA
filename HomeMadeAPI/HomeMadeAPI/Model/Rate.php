<?php


class Rate
{
    private $conn;
    private $table = 'rating';

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function  setRate($data){
        $query = "INSERT INTO " . $this->table . "
            SET
                rate = ?,
                customer_id = ?,
                saller_id = ?
               ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data->rate);
        $stmt->bindParam(2, $data->customer_id);
        $stmt->bindParam(3, $data->saller_id);



        try {
            if ($stmt->execute()) {
                http_response_code(200);
                return json_encode(array(
                    "message" => "rate Add correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(200);
                return json_encode(array(
                    "message" => "rate Add failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(200);
            return json_encode(array(
                "message" => "rate Add failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }

    public function getSellerRate($seller_id)
    {
        $q = "Select AVG(rate) as rate from $this->table where saller_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $seller_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['rate'] == null ? 0 : $row['rate'];
        } else {
            return 0;
        }
    }

    public function getAll()
    {
        $q = "Select AVG(rate) as rate ,saller_id  from $this->table  GROUP by saller_id  order by rate desc";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $seller_id);
        $stmt->execute();
        return $stmt;
    }


}