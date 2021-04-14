<?php


class UserRole
{
    private $conn;
    private $table = 'userrole';

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function setRole($email, $user_role)
    {
        // insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                user_id = ?,
                user_role = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $user_role);
        try {
            if ($stmt->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {

            return $e->getMessage();


        }
    }
}