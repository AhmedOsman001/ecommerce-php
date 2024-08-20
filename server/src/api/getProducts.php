<?php
// getProducts.php
require '../../vendor/autoload.php';

include "../config/Database.php";
include "../classes/ProductManager.php";

use Scandiweb\Server\Config\Database;
use Scandiweb\Server\Classes\ProductManager;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$database = new Database();
$db = $database->getConnection();   

$productManager = new ProductManager($db);
$products = $productManager->getProducts();

echo json_encode($products);