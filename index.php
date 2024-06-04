<?php

    require_once 'config/headers.php';
    require_once 'vendor/autoload.php';

    $requestUri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    // Remove query string from the request URI
    if (strpos($requestUri, '?') !== false) {
        $requestUri = strstr($requestUri, '?', true);
    }

    // Adjust the path to remove the prefix from the project
    $baseFolder = '/app-ramengo';
    $requestUri = substr($requestUri, strpos($requestUri, $baseFolder) + strlen($baseFolder));

    // Route requests based on the request URI
    switch ($requestUri) {
        case '/broths':
            if ($method === 'GET') {
                require 'src/Broths/listBroths.php';
            } else {
                http_response_code(405); // Method Not Allowed
            }
            break;
        case '/proteins':
            if ($method === 'GET') {
                require 'src/Proteins/listProteins.php';
            } else {
                http_response_code(405); // Method Not Allowed
            }
            break;
        case '/order':
            if ($method === 'POST') {
                require_once 'src/Orders/placeOrder.php';
            } else {
                http_response_code(405); // Method Not Allowed
            }
            break;
        default:
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }

?>
