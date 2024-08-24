<?php

namespace Server\Src\Classes;   
require_once 'Product.php';
use Server\Src\Classes\Product;
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

    // Getters
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

    // Setters
    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setLength($length)
    {
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
