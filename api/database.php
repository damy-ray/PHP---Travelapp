<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    protected $db;

    public function __construct($config)
    {
        $this->host = $config['DB_HOST'];
        $this->username = $config['DB_USERNAME'];
        $this->password = $config['DB_PASSWORD'];
        $this->dbname = $config['DB_NAME'];
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
        } catch (PDOException $e) {
            die("Non è possibile connettersi al database: " . $this->dbname . ": " . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $this->db = null;
    }

    public function getConnection()
    {
        return $this->db;
    }
}

?>
