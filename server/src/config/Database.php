<?php

namespace Scandiweb\Server\Config;

use PDO;
use PDOException;
class Database {
    private $dsn = 'mysql:host=localhost;dbname=productsdb';
    private $username = 'root';
    private $password = 'Actor@2000';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}


