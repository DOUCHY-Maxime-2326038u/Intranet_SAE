<?php

final class ControllerHandler
{
    private array $url;
    private array $params = [];

    public function __construct(?string $S_controller, ?string $S_action)
    {
        $this->url['controller'] = $this->cleanControllerName($S_controller);
        $this->url['action'] = $this->cleanActionName($S_action);
    }

    private function cleanControllerName(?string $controller): string
    {
        if (empty($controller)) {
            return 'AccueilController';
        }

        $controller = ucfirst($controller) . 'Controller';
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $controller)) {
            throw new InvalidArgumentException("Nom de contrôleur invalide.");
        }

        return htmlspecialchars($controller, ENT_QUOTES, 'UTF-8');
    }

    private function cleanActionName(?string $action): string
    {
        if (empty($action)) {
            return 'defaultAction';
        }

        $action = $action . 'Action';
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $action)) {
            throw new InvalidArgumentException("Nom d'action invalide.");
        }

        return htmlspecialchars($action, ENT_QUOTES, 'UTF-8');
    }

    public function getUrl(): array
    {
        return $this->url;
    }

    public function execute(): void
    {
        $controller = $this->url['controller'];
        $action = $this->url['action'];

        if (!class_exists($controller)) {
            throw new RuntimeException("Le contrôleur '$controller' est introuvable.");
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $action)) {
            throw new RuntimeException("L'action '$action' est introuvable dans le contrôleur '$controller'.");
        }

        try {
            call_user_func_array([$controllerInstance, $action], []);
        } catch (Exception $e) {
            throw new RuntimeException("Erreur lors de l'exécution de l'action '$action' : " . $e->getMessage());
        }

        if (method_exists($controllerInstance, 'getParams')) {
            $this->params = $controllerInstance->getParams();
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
