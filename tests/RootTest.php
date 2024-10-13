<?php
use PHPUnit\Framework\TestCase;

final class RootTest extends TestCase
{

    public function testCompleteRequest()
    {
        // Simule une requête en définissant un contrôleur et une action
        $_GET['ctrl'] = 'Test';

        // Exécute le routeur
        include 'root.php';
        ob_start();
        include 'Views/test.php';
        
        // Vérifie si le contenu de la réponse est ce qui est attendu
        $this->assertEquals(ob_end_flush(), $displayContent);
    }

    public function testAjaxRequest()
    {
        // Simule une requête AJAX
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $_GET['ctrl'] = 'Test';


        include 'root.php';

        // Simuler la réponse AJAX
        $this->assertEquals(null, $A_params['body'], "La requête AJAX devrait retourner uniquement le contenu partiel.");
    }

}