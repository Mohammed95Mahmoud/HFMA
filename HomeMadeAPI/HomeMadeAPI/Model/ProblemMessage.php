<?php


class ProblemMessage
{
    private $conn;
    private $table = 'problemmessage';

    /**
     * ProblemMessage constructor.
     * @param $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    function contactWithAdmin($data)
    {
        // insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                type = ?,
                content = ?,
                admin_email = ?,
                sender_id = ?
               ";

        $stmt = $this->conn->prepare($query);
        $content = json_encode($data->content);
        $admin_id = "admain@gmail";
        $stmt->bindParam(1, $data->type);
        $stmt->bindParam(2, $content);
        $stmt->bindParam(3, $admin_id);
        $stmt->bindParam(4, $data->sender_id);
        try {
            if ($stmt->execute()) {
                http_response_code(201);
                return json_encode(array(
                    "message" => "problem message registered correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "problem message failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "problem message failed " . $e->getMessage(),
                "flag" => -2
            ));
        }

    }
}