<?php 

namespace Server;

require "./vendor/autoload.php";

include "./src/config/Database.php";
include "./src/classes/ProductManager.php";

use Server\Src\Classes\ProductManager;
use Server\Src\Config\Database;

use PDOException;
    
function cors()
{

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

}
cors();

$database = new Database();
$db = $database->getConnection();

$productManager = new ProductManager($db);

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
