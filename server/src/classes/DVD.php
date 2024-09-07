<?php

namespace Server\Src\Classes;
require_once 'Product.php';

use Exception;
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

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {   
        if ($size <= 0) {
            throw new Exception('Size must be positive.');
        }
        $this->size = $size;
    }

    public function save($conn)
    {
        $query = "INSERT INTO products (sku, name, price, type, size) VALUES (:sku, :name, :price, 'DVD', :size)";
        $stmt = $conn->prepare($query);

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $size = $this->getSize();

        $stmt->bindParam(':sku', $sku);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':size', $size);

        return $stmt->execute();
    }
}