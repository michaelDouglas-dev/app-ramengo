<?php
    use PHPUnit\Framework\TestCase;

    class ProteinsTest extends TestCase {
        public function testListProteins() {
            $_SERVER['HTTP_X_API_KEY'] = getenv('API_KEY') ?: 'default-api-key';

            ob_start();
            include 'src/Proteins/listProteins.php';
            $output = ob_get_clean();

            $response = json_decode($output, true);
            $this->assertIsArray($response);
            $this->assertNotEmpty($response);
            $this->assertArrayHasKey('id', $response[0]);
        }

        public function testInvalidApiKey() {
            $_SERVER['HTTP_X_API_KEY'] = 'invalid-key';

            ob_start();
            include 'src/Proteins/listProteins.php';
            $output = ob_get_clean();

            $response = json_decode($output, true);
            $this->assertArrayHasKey('error', $response);
            $this->assertEquals('x-api-key header missing', $response['error']);
        }
    }
?>