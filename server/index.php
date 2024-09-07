<?php

namespace Server;


require_once './src/classes/ProductManager.php';
require_once './src/config/Database.php';
require_once './src/classes/Book.php';
require_once './src/classes/DVD.php';
require_once './src/classes/Furniture.php';

use Server\Src\Classes\ProductManager;
use Server\Src\Config\Database;


use PDOException;


$database = new Database();
$db = $database->getConnection();

$productManager = new ProductManager($db);
$productManager->cors();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode($productManager->getProducts());
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productClasses = [
        'DVD' => ['class' => 'Server\Src\Classes\DVD', 'params' => ['sku', 'name', 'price', 'size']],
        'Book' => ['class' => 'Server\Src\Classes\Book', 'params' => ['sku', 'name', 'price', 'weight']],
        'Furniture' => ['class' => 'Server\Src\Classes\Furniture', 'params' => ['sku', 'name', 'price', 'height', 'width', 'length']]
    ];

    $productClassInfo = $productClasses[$data['type']] ?? null;

    if ($productClassInfo) {
        $className = $productClassInfo['class'];

        $constructorParams = [];
        foreach ($productClassInfo['params'] as $param) {
            $constructorParams[] = $data[$param] ?? null;
        }

        try {
            $product = new $className(...$constructorParams);

            if ($productManager->addProduct($product)) {
                echo json_encode(["message" => "Product added successfully"]);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                http_response_code(409);
                echo json_encode(['error' => 'Duplicate entry detected.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
            }
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid product type']);
    }
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productManager->deleteProducts($data['skus']);
    echo json_encode(['message' => 'Products deleted successfully']);
}
