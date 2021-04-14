<?php

include "UserRole.php";

class Customer
{
    private $conn;
    private $table = 'user';
    private $password;


    /**
     * Customer constructor.
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function signUp($data)
    {

        // insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                full_name = ?,
                address = ?,
                phone_number = ?,
                password = ?,
                email = ?
               ";
        $stmt = $this->conn->prepare($query);
        $password = password_hash($data->password, PASSWORD_BCRYPT);
        $stmt->bindParam(1, $data->full_name);
        $stmt->bindParam(2, $data->address);
        $stmt->bindParam(3, $data->phone_number);
        $stmt->bindParam(4, $password);
        $stmt->bindParam(5, $data->email);

        try {
            if ($stmt->execute()) {
                $role = new UserRole($this->conn);
                $result = $role->setRole($data->email, 0);
                if ($result == 1) {
                    http_response_code(201);
                    return json_encode(array(
                        "message" => "Customer registered correctly",
                        "flag" => 1
                    ));
                } else {
                    http_response_code(201);
                    return json_encode(array(
                        "message" => "Customer registered but no role set!",
                        "flag" => -1
                    ));
                }

            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "Customer registered failed" . $stmt->errorInfo(),
                    "flag" => -2
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "Customer registered failed " . $e->getMessage(),
                "flag" => -3
            ));
        }

    }

    function signIn($email, $password)
    {
        if ($this->emailExist($email)) {
            if (password_verify($password, $this->password)) {
                http_response_code(200);
                return json_encode(array(
                    "message" => "customer signIn correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "password wrong",
                    "flag" => -2
                ));
            }
        } else {
            http_response_code(404);
            return json_encode(array(
                "message" => "customer singin failed! check your email!",
                "flag" => 1
            ));
        }
    }

    private function emailExist($email)
    {
        $query = "select password from $this->table where email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        try {
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->password = $row['password'];
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "customer singin failed " . $e->getMessage(),
                "flag" => -1
            ));
        }
    }

    function getInfo($email)
    {
        $query = "SELECT * FROM $this->table WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);

        try {
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                http_response_code(200);
                return json_encode(array(
                    "full_name" => $row['full_name'],
                    "address" => $row['address'],
                    "phone_number" => $row['phone_number'],
                    "email" => $row['email'],
                    "password" => $row['password'],
                    "id" => $row['id'],
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "Customer information error!",
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "customer get information failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }



}