<?php

final class ControllerHandler
{
    private array $url;
    private $params = [];

    public function __construct(?string $S_controller, ?string $S_action)
    {
        $this->url['controller'] = empty($S_controller)
            ? 'AccueilController'
            : ucfirst($S_controller) . 'Controller';

        $this->url['action'] = empty($S_action)
            ? 'defaultAction'
            : $S_action . 'Action';
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
            throw new Exception("Le contrôleur '$controller' est introuvable.");
        }
        $controllerInstance = new $controller();

        if (!method_exists($controller, $action)) {
            throw new Exception("L'action '$action' est introuvable dans le contrôleur '$controller'.");
        }

        call_user_func_array([$controllerInstance, $action], []);
        
        if (method_exists($controllerInstance, 'getParams')) {
            $this->params = $controllerInstance->getParams();
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
