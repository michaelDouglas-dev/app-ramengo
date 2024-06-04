<?php
    require_once 'config.php';

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    $logger = new Logger('api_activity');
    // Add a handler to write logs to a file
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::INFO));

    function validateApiKey($apiKey) {
        $expectedApiKey = $_ENV['API_KEY'];
    
        if ($apiKey !== $expectedApiKey) {
            http_response_code(403);
            echo json_encode(["error" => "x-api-key header missing"]);
            // Log a failure message
            global $logger;
            $logger->warning('Invalid API key', [
                'apiKey' => $apiKey
            ]);
            exit();
        }
    
        // Log a success message
        global $logger;
        $logger->info('API key validated successfully', [
            'apiKey' => $apiKey
        ]);
    }
?>
