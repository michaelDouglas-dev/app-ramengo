<?php
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../config/api_auth.php';
    
    // Validate API Key
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    validateApiKey($apiKey);

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    $logger = new Logger('api_activity');
    // Add a handler to write logs to a file
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/api.log', Logger::INFO));

    try {

        // Connect to the database
        $database = Database::getInstance();
        $pdo = $database->connect();

        // Retrieve proteins from the database using a prepared statement
        $stmt = $pdo->prepare("SELECT * FROM proteins WHERE available = TRUE");
        $stmt->execute();
        $proteins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output proteins as JSON
        header('Content-Type: application/json');
        echo json_encode($proteins);

        // Grava um log de sucesso
        $logger->info('Proteins listed successfully', [
            'apiKey' => $apiKey,
            'count' => count($proteins)
        ]);
    } catch (Exception $e) {
        // Record a success log
        $logger->error('Error listing proteins', [
            'error' => $e->getMessage(),
            'apiKey' => $apiKey
        ]);

        http_response_code(500);
        echo json_encode(["error" => "Could not list proteins"]);
    }
?>
