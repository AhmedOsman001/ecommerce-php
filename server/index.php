<?php


use PDO;
use PDOException;
class Database {
    private $dsn = 'mysql:host=mysql.railway.internal;dbname=railway';
    private $username = 'root';
    private $password = 'ihiqJYGzjcMNVmhvmcmoXXAlXlRavOsI';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch(PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}


$connect = new Database();
$conn = $connect->getConnection();
