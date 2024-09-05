<?php

namespace Server\Src\Config;

use PDO;
use PDOException;

class Database {
    private $dsn;
    private $username;
    private $password;
    private $conn;

    public function __construct() {

        $host = getenv('MYSQLHOST') ;
        $dbname = getenv('MYSQLDATABASE') ;
        $this->username = getenv('MYSQLUSER') ;
        $this->password = getenv('MYSQLPASSWORD');
        
        $this->dsn = "mysql:host=$host;dbname=$dbname";
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}
