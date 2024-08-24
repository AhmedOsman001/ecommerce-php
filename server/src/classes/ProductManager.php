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

        // Map of type to class names
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
    
                // Extract only the needed parameters for the specific product type
                foreach ($productClassInfo['params'] as $param) {
                    $constructorParams[] = $row[$param];
                }
    
                // Dynamically create the product object
                $product = new $className(...$constructorParams);
                // Convert product object to array using getter methods
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
}
