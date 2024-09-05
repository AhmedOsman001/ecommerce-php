<?php 

namespace Server;


require_once __DIR__ . '/src/classes/ProductManager.php';
require_once __DIR__ . '/src/config/Database.php';
require_once __DIR__ . '/src/classes/Book.php';
require_once __DIR__ . '/src/classes/DVD.php';
require_once __DIR__ . '/src/classes/Furniture.php';

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
}

elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productClass = [
        'DVD' => 'Server\Src\Classes\DVD',
        'Book' => 'Server\Src\Classes\Book',
        'Furniture' => 'Server\Src\Classes\Furniture'
    ][$data['type']] ?? null;
    
    $filteredAttributes = array_values(array_filter(
        $data,
        fn($value, $key) => in_array($key, ['size', 'weight', 'height', 'width', 'length']) && $value !== '',
        ARRAY_FILTER_USE_BOTH
    ));
    
    try {
        if ($productClass) {
            $product = new $productClass(
                $data['sku'],
                $data['name'],
                $data['price'],
                ...$filteredAttributes
            );
            if ($productManager->addProduct($product)) {
                echo json_encode(["message" => "Product added successfully"]);
            }
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
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productManager->deleteProducts($data['skus']);
    echo json_encode(['message' => 'Products deleted successfully']);
}
