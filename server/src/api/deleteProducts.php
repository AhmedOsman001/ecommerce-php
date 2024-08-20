<?php

require '../../vendor/autoload.php';

use Scandiweb\Server\Config\Database;
use Scandiweb\Server\Classes\ProductManager;

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

    echo "You have CORS!";
}
cors();
$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents("output.txt", print_r(json_decode(file_get_contents('php://input'), true), true), FILE_APPEND);
$productManager = new ProductManager($db);

if (isset($data['skus']) && is_array($data['skus'])) {
    $skus = $data['skus'];

    if ($productManager->deleteProducts($skus)) {
        echo json_encode(["message" => "Products deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error deleting products"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "SKUs are required and should be an array"]);
}
