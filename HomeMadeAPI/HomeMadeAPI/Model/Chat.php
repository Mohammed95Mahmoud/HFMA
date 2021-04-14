<?php


class Chat
{
    private $conn;
    private $table = 'chat';

    /**
     * Chat constructor.
     * @param $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    function addMessage($data)
    {
        $query = "INSERT INTO " . $this->table . "
            SET
                time = ?,
                date = ?,
                sender_id = ?,
                message = ?,
                receiver_id = ?
               ";
        $msg = json_encode($data->message);
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data->time);
        $stmt->bindParam(2, $data->date);
        $stmt->bindParam(3, $data->sender_id);
        $stmt->bindParam(4, $msg);
        $stmt->bindParam(5, $data->receiver_id);

        try {
            if ($stmt->execute()) {
                http_response_code(200);
                return json_encode(array(
                    "message" => "message Add correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(200);
                return json_encode(array(
                    "message" => "massage Add failed" . $stmt->errorInfo(),
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

    function getReceiverMessage($id)
    {
        $query = "SELECT * FROM $this->table WHERE receiver_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;

    }
}