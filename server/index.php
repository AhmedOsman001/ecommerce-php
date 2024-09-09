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
use Exception;

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
        'DVD' => ['class' => 'Server\Src\Classes\DVD', 'props' => ['sku', 'name', 'price', 'size']],
        'Book' => ['class' => 'Server\Src\Classes\Book', 'props' => ['sku', 'name', 'price', 'weight']],
        'Furniture' => ['class' => 'Server\Src\Classes\Furniture', 'props' => ['sku', 'name', 'price', 'height', 'width', 'length']]
    ];

    $productClassInfo = $productClasses[$data['type']] ?? null;

    if ($productClassInfo) {
        $className = $productClassInfo['class'];

        $constructorProps = [];
        foreach ($productClassInfo['props'] as $prop) {
            $constructorProps[] = $data[$prop] ?? null;
        }

        try {
            $product = new $className(...$constructorProps);

            if ($productManager->addProduct($product)) {
                http_response_code(201);
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
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        http_response_code(400); 
        echo json_encode(['error' => 'Bad Request']);
    }
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productManager->deleteProducts($data['skus']);
    echo json_encode(['message' => 'Products deleted successfully']);
}
