<?php

namespace Server\Src\Classes;
require_once 'Product.php';

use Exception;
class Book extends Product {
    private $weight;

    public function __construct($sku, $name, $price, $weight)
    {
        parent::__construct($sku, $name, $price);
        $this->setWeight($weight);
    }

    public function getType()
    {
        return 'Book';
    }

    public function getSpecificAttribute()
    {
        return "Weight: {$this->getWeight()} KG";
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {   
        if ($weight <= 0) {
            throw new Exception('Weight must be positive.');
        }                                          
        $this->weight = $weight;
    }

    public function save($conn)
    {
        
        $query = "INSERT INTO products (sku, name, price, type, weight) VALUES (:sku, :name, :price, 'Book' , :weight)";
        $stmt = $conn->prepare($query);

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $weight = $this->getWeight();

        $stmt->bindParam(':sku', $sku);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':weight', $weight);
        

        return $stmt->execute();
    }
}