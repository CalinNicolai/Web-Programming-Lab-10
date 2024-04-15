<?php

// Include the autoloader
include __DIR__ . '/../vendor/autoload.php';

use App\database\Database;
use App\router\Router;

session_start();

$connection = Database::connect(); // Establish a database connection

$method = $_SERVER['REQUEST_METHOD']; // Get the HTTP request method
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Parse the URL path

Router::insertRoutes($connection); // Insert routes into the Router
$result = Router::handleRequest($method, $url); // Handle the request using the Router

if ($result === false) {
    http_response_code(404); // Set response code to 404
    require_once __DIR__ . '/../src/view/404/404.php'; // Load the 404 error page
} else {
    echo $result; // Output the result
}