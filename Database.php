<?php

require_once 'config.php';

class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    protected $dbconn;

    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->dbconn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
        } catch (PDOException $e) {
            die("Non è possibile connettersi al database: " . $this->dbname . ": " . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $this->dbconn = null;
    }

    public function getConnection()
    {
        return $this->dbconn;
    }
}

?>
