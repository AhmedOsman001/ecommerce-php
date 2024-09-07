<?php 

namespace Server\Src\Classes;

use Exception;
abstract class Product
{
    private $sku;
    private $name;
    private $price;

    public function __construct($sku, $name, $price)
    {   
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
    }

    abstract public function getType();

    abstract public function getSpecificAttribute();

    abstract public function save($conn);

    public function getSku()
    {
        return $this->sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setSku($sku)
    {   
        if (strlen($sku) < 1) {
            throw new Exception('SKU must be at least 1 character long.');
        }
        $this->sku = $sku;
    }

    public function setName($name)
    {
        if (strlen($name) < 1) {
            throw new Exception('Name must be at least 1 character long.');
        }
        $this->name = $name;
    }

    public function setPrice($price)
    {
        if ($price <= 0) {
            throw new Exception('Price must be positive.');
        }
        $this->price = $price;
    }
}