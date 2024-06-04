<?php
require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        global $dbHost, $dbName, $dbUsername, $dbPassword;

        try {
            $this->conn = new PDO(
                "mysql:host={$dbHost};dbname={$dbName}",
                $dbUsername,
                $dbPassword
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
            exit();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connect() {
        return $this->conn;
    }
}
?>
