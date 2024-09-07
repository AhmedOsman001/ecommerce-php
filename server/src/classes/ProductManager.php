<?php

namespace Server\Src\Classes;

use PDO;


class ProductManager
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getProducts()
    {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        $productClasses = [
            'DVD' => ['class' => 'Server\Src\Classes\DVD', 'params' => ['sku', 'name', 'price', 'size']],
            'Book' => ['class' => 'Server\Src\Classes\Book', 'params' => ['sku', 'name', 'price', 'weight']],
            'Furniture' => ['class' => 'Server\Src\Classes\Furniture', 'params' => ['sku', 'name', 'price', 'height', 'width', 'length']]
        ];


        foreach ($results as $row) {
            $productType = $row['type'];
            $productClassInfo = $productClasses[$productType] ?? null;

            if ($productClassInfo) {
                $className = $productClassInfo['class'];
                $constructorParams = [];

                foreach ($productClassInfo['params'] as $param) {
                    $constructorParams[] = $row[$param];
                }

                $product = new $className(...$constructorParams);
                
                $products[] = [
                    'sku' => $product->getSku(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'type' => $product->getType(),
                    'specificAttribute' => $product->getSpecificAttribute(),
                ];
            }
        }

        return $products;
    }

    public function addProduct(Product $product)
    {
        return $product->save($this->conn);
    }

    public function deleteProducts(array $skus)
    {
        $inQuery = implode(',', array_fill(0, count($skus), '?'));
        $query = "DELETE FROM products WHERE sku IN ($inQuery)";
        $stmt = $this->conn->prepare($query);

        foreach ($skus as $index => $sku) {
            $stmt->bindValue($index + 1, $sku);
        }

        return $stmt->execute();
    }

    public function cors()
    {

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }
}
