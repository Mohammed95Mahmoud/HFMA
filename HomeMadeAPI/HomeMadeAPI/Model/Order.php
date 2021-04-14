<?php


class Order
{
    private $conn;
    private $table = 'purchesorder';

    /**
     * PurchesOrder constructor.
     * @param $conn
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function addOrder($data)
    {
        $query = "INSERT INTO " . $this->table . "
            SET
                time = ?,
                date = ?,
                state = ?,
                customer_id = ?,
                details = ?,
                seller_id = ?,
                total = ?
               ";
        $stmt = $this->conn->prepare($query);
        $details = json_encode($data->details);
        $stmt->bindParam(1, $data->time);
        $stmt->bindParam(2, $data->date);
        $stmt->bindParam(3, $data->state);
        $stmt->bindParam(4, $data->customer_id);
        $stmt->bindParam(5, $details);
        $stmt->bindParam(6, $data->seller_id);
        $stmt->bindParam(7, $data->total);
        try {
            if ($stmt->execute()) {
                http_response_code(200);
                return json_encode(array(
                    "message" => "order Add correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(200);
                return json_encode(array(
                    "message" => "order Add failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(200);
            return json_encode(array(
                "message" => "order Add failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }

    public function getCustomerOrders($id)
    {
        $q = "SELECT * FROM purchesorder WHERE customer_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }


    public function updateStatus($newState, $orderId)
    {
        $q = "update purchesorder set state = ? where id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $newState);
        $stmt->bindParam(2, $orderId);
        if ($stmt->execute()) {
            http_response_code(201);
            return json_encode(array("message" => "Order status updated!", "flag" => 1));
        } else {
            http_response_code(400);
            return json_encode(array("message" => "something went wrong pleas try again later!", "flag" => 0));
        }
    }

    public function getNewOrders($seller_id)
    {
        $q = "select * from purchesorder where seller_id = ? && state = 'new'";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $seller_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $ordersData = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $details = json_decode($row['details']);
                $orderItem = array(
                    "id" => $row['id'],
                    "time" => $row['time'],
                    "state" => $row['state'],
                    "customer_id" => $row['customer_id'],
                    "details" => $details
                );
                array_push($ordersData, $orderItem);
            }
            http_response_code(200);
            return json_encode(array("orders" => $ordersData, "flag" => 1));
        } else {
            http_response_code(404);
            return json_encode(array("orders" => "no data found", "flag" => 0));
        }
    }

    public function getOnOrders($seller_id)
    {
        $q = "select * from purchesorder where seller_id = ? && state = 'on_progress'";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $seller_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $ordersData = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $details = json_decode($row['details']);
                $orderItem = array(
                    "id" => $row['id'],
                    "time" => $row['time'],
                    "state" => $row['state'],
                    "customer_id" => $row['customer_id'],
                    "details" => $details
                );
                array_push($ordersData, $orderItem);
            }
            http_response_code(200);
            return json_encode(array("orders" => $ordersData, "flag" => 1));
        } else {
            http_response_code(404);
            return json_encode(array("orders" => "no data found", "flag" => 0));
        }
    }

    public function getFinishedOrders($seller_id)
    {
        $q = "select * from purchesorder where seller_id = ? && state = 'finished'";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $seller_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $ordersData = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $details = json_decode($row['details']);
                $orderItem = array(
                    "id" => $row['id'],
                    "time" => $row['time'],
                    "state" => $row['state'],
                    "customer_id" => $row['customer_id'],
                    "details" => $details
                );
                array_push($ordersData, $orderItem);
            }
            http_response_code(200);
            return json_encode(array("orders" => $ordersData, "flag" => 1));
        } else {
            http_response_code(404);
            return json_encode(array("orders" => "no data found", "flag" => 0));
        }
    }

    public function deleteOrder($id)
    {
        $q = "DELETE FROM purchesorder WHERE id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            http_response_code(200);
            return json_encode(array(
                "message" => "Delete order done",
                "flag" => 1
            ));
        } else {
            http_response_code(401);
            return json_encode(array(
                "message" => "something went wrong",
                "flag" => -1
            ));
        }
    }

}