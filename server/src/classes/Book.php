<?php

namespace Server\Src\Classes;
require_once 'Product.php';
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

    // Getters
    public function getWeight()
    {
        return $this->weight;
    }

    // Setters
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function save($conn)
    {
        echo $this->getWeight();
        $query = "INSERT INTO products (sku, name, price, type, weight) VALUES (:sku, :name, :price, :book, :weight)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':sku', $this->getSku());
        $stmt->bindParam(':name', $this->getName());
        $stmt->bindParam(':price', $this->getPrice());
        $stmt->bindParam(':book', $this->getType());
        $stmt->bindParam(':weight', $this->getWeight());

        return $stmt->execute();
    }
}