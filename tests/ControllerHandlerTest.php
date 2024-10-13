<?php
use PHPUnit\Framework\TestCase;

final class ControllerHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../_assets/Essentials/Autoloader.php';
        require_once __DIR__ . '/../_assets/Essentials/ControllerHandler.php';
    }
    public function testControllerExecution()
    {
        // Teste si un contrôleur valide est exécuté
        $controllerTester = new ControllerHandler('Test', 'default');
        $controllerTester->execute();
        
        $this->assertEquals(['controller' => 'TestController', 'action' => 'defaultAction'], $controllerTester->getUrl());
    }

    public function testControllerNotFound()
    {
        // Teste si une exception est levée pour un contrôleur introuvable
        $this->expectException(Exception::class);
        $controllerTester = new ControllerHandler('NonExistent', 'default');
        $controllerTester->execute();
    }

    public function testActionNotFound()
    {
        // Teste si une exception est levée pour une action introuvable
        $this->expectException(Exception::class);
        $controllerTester = new ControllerHandler('Test', 'nonExistentAction');
        $controllerTester->execute();
    }
}
