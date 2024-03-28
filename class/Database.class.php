<?php

class Database
{
    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "travelapp";
    protected static $db;

    private function __construct() {}

    public static function connect()
    {
        try {
            self::$db = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$username, self::$password);
        } catch (PDOException $e) {
            die("Non è possibile connettersi al database: " . self::$dbname . ": " . $e->getMessage());
        }
    }

    public static function disconnect()
    {
        self::$db = null;
    }

    public static function getConnection()
    {
        return self::$db;
    }
}

?>
