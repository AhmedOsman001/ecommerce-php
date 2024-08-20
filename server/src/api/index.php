<?php 

include "../config/Database.php";
include "../classes/DVD.php";
include "../classes/Book.php";
include "../classes/Furniture.php";
include "../classes/ProductManager.php";


use Scandiweb\Server\Classes\ProductManager;
use Scandiweb\Server\Config\Database;
use Scandiweb\Server\Classes\DVD;
use Scandiweb\Server\Classes\Book;
use Scandiweb\Server\Classes\Furniture;


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


$db = new Database();
$conn = $db->getConnection();
$productManager = new ProductManager($conn);
var_dump($productManager); 
$method = $_SERVER['REQUEST_METHOD'];
echo $method;

if ($method === 'GET') {
    echo json_encode($productManager->getProducts());
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productClass = [
        'DVD' => DVD::class,
        'Book' => Book::class,
        'Furniture' => Furniture::class
    ][$data['type']] ?? null;

    if ($productClass) {
        $product = new $productClass(
            $data['sku'], 
            $data['name'], 
            $data['price'], 
            $data['size'] ?? null, 
            $data['weight'] ?? null, 
            $data['height'] ?? null, 
            $data['width'] ?? null, 
            $data['length'] ?? null
        );
        $productManager->addProduct($product);
        echo json_encode(['message' => 'Product added successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid product type']);
    }
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productManager->deleteProducts($data['skus']);
    echo json_encode(['message' => 'Products deleted successfully']);
}
