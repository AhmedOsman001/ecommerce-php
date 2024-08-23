<?php

namespace Scandiweb\Server\Classes;

class DVD extends Product {
    private $size;

    public function __construct($sku, $name, $price, $size)
    {
        parent::__construct($sku, $name, $price);
        $this->setSize($size);
    }

    public function getType()
    {
        return 'DVD';
    }

    public function getSpecificAttribute()
    {
        return "Size: {$this->getSize()} MB";
    }

    // Getters
    public function getSize()
    {
        return $this->size;
    }

    // Setters
    public function setSize($size)
    {
        // You can add validation logic here
        $this->size = $size;
    }

    public function save($conn)
    {
        $query = "INSERT INTO products (sku, name, price, type, size) VALUES (:sku, :name, :price, 'DVD', :size)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':sku', $this->getSku());
        $stmt->bindParam(':name', $this->getName());
        $stmt->bindParam(':price', $this->getPrice());
        $stmt->bindParam(':size', $this->getSize());

        return $stmt->execute();
    }
}