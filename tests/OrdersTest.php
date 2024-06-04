<?php
    use PHPUnit\Framework\TestCase;

    class OrdersTest extends TestCase {
        public function testPlaceOrder() {
            $_SERVER['HTTP_X_API_KEY'] = getenv('API_KEY') ?: 'default-api-key';
            $postData = [
                'brothId' => '1',
                'proteinId' => '1'
            ];

            $_POST = $postData;

            ob_start();
            require_once 'src/Orders/placeOrder.php';
            $output = ob_get_clean();

            $response = json_decode($output, true);
            $this->assertArrayHasKey('id', $response);
            $this->assertArrayHasKey('description', $response);
            $this->assertArrayHasKey('image', $response);
        }

        public function testMissingParameters() {
            $_SERVER['HTTP_X_API_KEY'] = getenv('API_KEY') ?: 'default-api-key';
            $_POST = [];

            ob_start();
            require_once 'src/Orders/placeOrder.php';
            $output = ob_get_clean();

            $response = json_decode($output, true);
            $this->assertArrayHasKey('error', $response);
            $this->assertEquals('both brothId and proteinId are required', $response['error']);
        }

        public function testInvalidApiKey() {
            $_SERVER['HTTP_X_API_KEY'] = 'invalid-key';
            $_POST = [
                'brothId' => '1',
                'proteinId' => '1'
            ];

            ob_start();
            require_once 'src/Orders/placeOrder.php';
            $output = ob_get_clean();

            $response = json_decode($output, true);
            $this->assertArrayHasKey('error', $response);
            $this->assertEquals('x-api-key header missing', $response['error']);
        }
    }
?>