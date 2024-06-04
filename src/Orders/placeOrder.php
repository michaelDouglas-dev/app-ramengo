<?php
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../config/api_auth.php';

    // Validate API Key
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    validateApiKey($apiKey);

    // Function to generate order ID
    function generateOrderId($apiKey) {
        $endpoint = 'https://api.tech.redventures.com.br/orders/generate-id';
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json' . PHP_EOL .
                            'x-api-key: ' . $apiKey
            ]
        ];

        $response = file_get_contents($endpoint, false, stream_context_create($options));
        if ($response === false) {
            throw new Exception("Failed to generate order ID");
        }

        $orderData = json_decode($response, true);
        if ($orderData === null || !isset($orderData['orderId'])) {
            throw new Exception("Failed to parse order ID");
        }

        return $orderData['orderId'];
    }

    // Function to insert the order into the database
    function insertOrder($orderId, $brothId, $proteinId) {
        $database = Database::getInstance();
        $pdo = $database->connect();
        
        $stmt = $pdo->prepare('INSERT INTO orders (orderId, brothId, proteinId) VALUES (:orderId, :brothId, :proteinId)');
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':brothId', $brothId);
        $stmt->bindParam(':proteinId', $proteinId);

        if (!$stmt->execute()) {
            throw new Exception("Could not place order");
        }
    }

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    $logger = new Logger('api_activity');
    // Add a handler to write logs to a file
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/api.log', Logger::INFO));

    try {

        // Ensure request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
            exit();
        }

        // Decode request body
        $data = json_decode(file_get_contents("php://input"));

        // Ensure required fields are present
        if (!isset($data->brothId) || !isset($data->proteinId)) {
            http_response_code(400);
            echo json_encode(["error" => "both brothId and proteinId are required"]);
            exit();
        }

        // Generate order ID
        $orderId = generateOrderId($apiKey);

        // Insert order into database
        insertOrder($orderId, $data->brothId, $data->proteinId);

        // Construct order response
        $orderResponse = [
            "id" => $orderId,
            "description" => "Salt and Chasu Ramen",
            "image" => "https://tech.redventures.com.br/icons/ramen/ramenChasu.png"
        ];

        // Return order response as JSON
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($orderResponse);

        // Record a success log
        $logger->info('Order placed successfully', [
            'apiKey' => $apiKey,
            'orderId' => $orderId
        ]);
    } catch (Exception $e) {
        // Record an error log
        $logger->error('Error placing order', [
            'error' => $e->getMessage(),
            'apiKey' => $apiKey
        ]);

        // Return internal server error
        http_response_code(500);
        echo json_encode(["error" => "could not place order"]);
    }
?>