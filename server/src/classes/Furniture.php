<?php

namespace Server\Src\Classes;   
require_once 'Product.php';

use Exception;
class Furniture extends Product
{
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height, $width, $length)
    {
        parent::__construct($sku, $name, $price);
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
    }

    public function getType()
    {
        return 'Furniture';
    }

    public function getSpecificAttribute()
    {
        return "Dimensions: {$this->getHeight()}x{$this->getWidth()}x{$this->getLength()}";
    }

    
    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getLength()
    {
        return $this->length;
    }

    
    public function setHeight($height)
    {
        if ($height <= 0) {
            throw new Exception('Height must be positive.');
        }
        $this->height = $height;
    }

    public function setWidth($width)
    {
        if ($width <= 0) {
            throw new Exception('Width must be positive.');
        }
        $this->width = $width;
    }

    public function setLength($length)
    {
        if ($length <= 0) {
            throw new Exception('Length must be positive.');
        }
        $this->length = $length;
    }

    public function save($conn)
    {
        $query = "INSERT INTO products (sku, name, price, type, height, width, length) VALUES (:sku, :name, :price, 'Furniture', :height, :width, :length)";
        $stmt = $conn->prepare($query);

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $height = $this->getHeight();
        $width = $this->getWidth();
        $length = $this->getLength();

        $stmt->bindParam(':sku', $sku);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':width', $width);
        $stmt->bindParam(':length', $length);

        return $stmt->execute();
    }
}
