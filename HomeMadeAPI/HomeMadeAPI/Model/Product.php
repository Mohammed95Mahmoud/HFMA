<?php


class Product
{
    private $conn;
    private $table = 'product';
    private $server_dir;
    private $dir;

    /**
     * Seller constructor.
     * @param PDO $conn
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $ip = gethostbyname(gethostname());
        $this->server_dir = "http://" . $ip . "/HomeMadeAPI/Controller/ProductController/images/";
        $this->dir = "/HomeMadeAPI/Controller/ProductController/images/";
    }

    /**
     * @return string
     */
    public function getServerDir(): string
    {
        return $this->server_dir;
    }


    function addProduct($data)
    {
        $query = "INSERT INTO " . $this->table . "
            SET
                name = ?,
                price = ?,
                quantity = ?,
                size = ?,
                image = ?,
                description = ?,
                saller_id = ?,
                time = ?,
                date = ?
               ";
        $stmt = $this->conn->prepare($query);
        $imageName = $data->name . "_" . $data->date . "_" . uniqid() . ".jpg";
        $destination_folder = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = $destination_folder . $this->dir . $imageName;
        $stmt->bindParam(1, $data->name);
        $stmt->bindParam(2, $data->price);
        $stmt->bindParam(3, $data->quantity);
        $stmt->bindParam(4, $data->size);
        $stmt->bindParam(5, $imageName);
        $stmt->bindParam(6, $data->description);
        $stmt->bindParam(7, $data->saller_id);
        $stmt->bindParam(8, $data->time);
        $stmt->bindParam(9, $data->date);

        try {
            if ($stmt->execute()) {
                $handle = fopen($imagePath, 'w');
                fwrite($handle, base64_decode($data->ImageData));
                fclose($handle);
                http_response_code(201);
                return json_encode(array(
                    "message" => "Product Add correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "Product Add failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "Product Add failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }

    function editProduct($data)
    {
        $query = "UPDATE product
            SET
                name = ?,
                price = ?,
                quantity = ?,
                size = ?,
                description = ?
                
            WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data->name);
        $stmt->bindParam(2, $data->price);
        $stmt->bindParam(3, $data->quantity);
        $stmt->bindParam(4, $data->size);
        $stmt->bindParam(5, $data->description);
        $stmt->bindParam(6, $data->id);

        try {
            if ($stmt->execute()) {
                http_response_code(201);
                return json_encode(array(
                    "message" => "Product UPDATE correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "Product UPDATE failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "Product UPDATE failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }

    function deleteProduct($id)
    {
        $query = "DELETE FROM product WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        try {
            if ($stmt->execute()) {
                http_response_code(200);
                return json_encode(array(
                    "message" => "Product DELETE correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "Product DELETE failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "Product DELETE failed " . $e->getMessage(),
                "flag" => -2
            ));
        }
    }

    function getAllSellerProduct($id)
    {
        $q = "SELECT * FROM $this->table WHERE saller_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    function getAllProduct()
    {
        $q = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    public function getProduct($p_id)
    {
        $q = "SELECT * FROM $this->table where id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $p_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt;
        else
            return 0;
    }

    public function getImageUrl($product_id)
    {
        $q = "SELECT image FROM $this->table WHERE   id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $product_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['image'];
        } else
            return "no image";
    }

    public function getName($product_id)
    {
        $q = "SELECT name FROM $this->table WHERE   id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $product_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
        } else
            return "no name";
    }


}