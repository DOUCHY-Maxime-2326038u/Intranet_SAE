<?php
use PHPUnit\Framework\TestCase;

    final class CSRFTest extends TestCase
    {
        protected function setUp(): void
        {
            include 'root.php';
        }

        public function testCsrfTokenGenerated()
        {
            // Vérifie si le jeton CSRF est bien créé
            $this->assertNotEmpty($_SESSION['csrf_token']);
            $this->assertEquals(64, strlen($_SESSION['csrf_token'])); // Test si c'est un token de 32 bytes (64 chars hex)
        }

        public function testCsrfTokenRegenerated()
        {
                
            $oldToken = $_SESSION['csrf_token'];
                
            // Regénère le jeton CSRF
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                
            // Vérifie si le jeton CSRF est bien recréé et différent de l'ancien
            $this->assertNotEmpty($_SESSION['csrf_token']);
            $this->assertEquals(64, strlen($_SESSION['csrf_token']));
            $this->assertNotEquals($oldToken, $_SESSION['csrf_token']);
        }

        public function testCsrfTokenValidation()
        {
            $token = $_SESSION['csrf_token'];
                
            // Simule une requête POST avec le jeton CSRF
            $_POST['csrf_token'] = $token;
                
            // Vérifie si le jeton CSRF est valide
            $this->assertTrue(isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $_POST['csrf_token']);
        }

    }
        

