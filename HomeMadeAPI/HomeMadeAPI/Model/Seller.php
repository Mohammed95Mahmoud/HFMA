<?php


class Seller
{
    private $conn;
    private $seller_table = 'seller';
    private $password;
    private $id;
    private $server_dir;
    private $dir;
    private $blockedId;
    private $rate;
    /**
     * @var mixed
     */
    private $store_name;
    private $logo;

    /**
     * Seller constructor.
     * @param PDO $conn
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $ip = gethostbyname(gethostname());
        $this->server_dir = "http://" . $ip . "/HomeMadeAPI/Controller/SellerController/images/";
        $this->dir = "/HomeMadeAPI/Controller/SellerController/images/";
    }

    /**
     * @return string
     */
    public function getServerDir(): string
    {
        return $this->server_dir;
    }


    function signUp($data)
    {
        // insert query
        $query = "INSERT INTO " . $this->seller_table . "
            SET
                full_name = ?,
                address = ?,
                phone_number = ?,
                password = ?,
                email = ?,
                stor_name = ?,
                category = ?,
                logo = ?,
                type = ?
               ";
        $stmt = $this->conn->prepare($query);
        $imageName = uniqid() . "_" . $data->full_name . ".jpg";
        $destination_folder = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = $destination_folder . "/" . $this->dir . $imageName;
        $password = password_hash($data->password, PASSWORD_BCRYPT);
        $stmt->bindParam(1, $data->full_name);
        $stmt->bindParam(2, $data->address);
        $stmt->bindParam(3, $data->phone_number);
        $stmt->bindParam(4, $password);
        $stmt->bindParam(5, $data->email);
        $stmt->bindParam(6, $data->stor_name);
        $stmt->bindParam(7, $data->category);
        $stmt->bindParam(8, $imageName);
        $stmt->bindParam(9, $data->type);

        $query2 = "INSERT INTO blacklist
            SET
                duration_of_penatly = ?,
                saller_id = ?
               ";
        $stmt2 = $this->conn->prepare($query2);
        $reason = "30 minute";
        $stmt2->bindParam(1, $reason);
        $stmt2->bindParam(2, $data->email);

        try {
            if ($stmt->execute()) {
                $stmt2->execute();
                $handle = fopen($imagePath, 'w');
                fwrite($handle, base64_decode($data->ImageData));
                fclose($handle);
                http_response_code(201);
                return json_encode(array(
                    "message" => "seller registered correctly",
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(array(
                    "message" => "seller registered failed" . $stmt->errorInfo(),
                    "flag" => -1
                ));
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "seller registered failed " . $e->getMessage(),
                "flag" => -2
            ));
        }

    }

    private function getRate($email)
    {
        $q = "Select AVG(rate) as rate from rating where saller_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $this->rate = $stmt->fetch(PDO::FETCH_ASSOC)['rate'];

    }

    function signIn($email, $password)
    {

        if ($this->emailExist($email)) {
            if (password_verify($password, $this->password)) {
                $this->getRate($email);
                http_response_code(200);
                if ($this->isBlocked($email)) {

                    return json_encode(array(
                        "message" => "seller Blocked contact with admin",
                        "flag" => 1,
                        "blocked" => 1,
                        "rate" => 0,
                        "logo" => $this->logo,
                        "store_name" => $this->store_name
                    ));
                } else
                    if ($this->rate == null)
                        $this->rate = 0;

                return json_encode(array(
                    "message" => "seller signIn correctly",
                    "flag" => 1,
                    "blocked" => 0,
                    "rate" => $this->rate,
                    "logo" => $this->logo,
                    "store_name" => $this->store_name
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
                "message" => "seller singin failed! check your email!",
                "flag" => 1
            ));
        }
    }

    private function emailExist($email)
    {
        $query = "select stor_name,password,id,logo from $this->seller_table where email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        try {
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->password = $row['password'];
                $this->id = $row['id'];
                $this->store_name = $row['stor_name'];
                $this->logo = $this->server_dir. "/" .$row['logo'];
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "seller singin failed " . $e->getMessage(),
                "falg" => -1
            ));
        }
    }

    private function isBlocked($email)
    {
        $query = "select * from blacklist where saller_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        try {
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            http_response_code(403);
            return json_encode(array(
                "message" => "seller singin failed " . $e->getMessage(),
                "falg" => -1
            ));
        }
    }

    public function getAllSellers()
    {
        $q = "Select * from $this->seller_table";
        $stmt = $this->conn->prepare($q);
        $stmt->execute();
        return $stmt;
    }

    public function getAllSellersByCategory($cat)
    {
        $q = "Select * from $this->seller_table where category like '%$cat%' ";
        $stmt = $this->conn->prepare($q);
        //  $stmt->bindParam(1, $cat);
        $stmt->execute();
        return $stmt;
    }

    public function getAllSellersByName($query)
    {
        $q = "Select * from $this->seller_table where stor_name like '%$query%' ";
        $stmt = $this->conn->prepare($q);
        $stmt->execute();
        return $stmt;
    }

    public function getSellersById($id)
    {
        $q = "Select * from $this->seller_table where email = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }


    public function getStoreLogo($id)
    {
        $q = "select  logo from $this->seller_table where email = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['logo'];
        } else {
            return 0;
        }


    }

    function editSeller($data)
    {
        $query = "UPDATE seller
            SET
                full_name = ?,
                address = ?,
                phone_number = ?,
                stor_name = ?,
                category = ?,
                type = ?,
                password = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare("Select id from seller where email = ?");
        $stmt->bindParam(1, $data->s_email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $s_id = $row['id'];
        $stmt = $this->conn->prepare($query);
        $info = password_get_info($data->password);
        if ($info['algo'] != 0)
            $password = $data->password;
        else
            $password = password_hash($data->password, PASSWORD_BCRYPT);
        $stmt->bindParam(1, $data->full_name);
        $stmt->bindParam(2, $data->address);
        $stmt->bindParam(3, $data->phone_number);
        $stmt->bindParam(4, $data->stor_name);
        $stmt->bindParam(5, $data->category);
        $stmt->bindParam(6, $data->type);
        $stmt->bindParam(7, $password);
        $stmt->bindParam(8, $s_id);

        if ($stmt->execute()) {

            http_response_code(201);
            return json_encode(array(
                "message" => "Seller UPDATE correctly",
                "flag" => 1
            ));
        } else {
            http_response_code(401);
            return json_encode(array(
                "message" => "seller UPDATE failed!",
                "flag" => -1
            ));
        }


    }


}