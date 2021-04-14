<?php
require_once "../../config/Database.php";
require_once "../../config/headers.php";
require_once "../DataValidation.php";
include  "../../Model/ProblemMessage.php";

class ProblemMessageControllerInit
{
    public static function getDatabase()
    {
        return new Database();
    }

    public static function getConn()
    {
        return self::getDatabase()->connect();
    }


    public static function getProblemMessage()
    {

        return new ProblemMessage(self::getConn());
    }

    public static function getData()
    {
        return json_decode(file_get_contents("php://input"));
    }

}