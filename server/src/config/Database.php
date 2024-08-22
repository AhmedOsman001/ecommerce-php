<?php

namespace Scandiweb\Server\Config;

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
        } catch(PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}


