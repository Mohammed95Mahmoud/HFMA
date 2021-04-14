<?php

require_once "../../config/Database.php";
require_once "../../config/headers.php";
require_once "../DataValidation.php";
include  "../../Model/Chat.php";
class ChatInit
{
    public static function getDatabase()
    {
        return new Database();
    }

    public static function getConn()
    {
        return self::getDatabase()->connect();
    }


    public static function getChat()
    {

        return new Chat(self::getConn());
    }

    public static function getData()
    {
        return json_decode(file_get_contents("php://input"));
    }

}