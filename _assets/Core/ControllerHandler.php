<?php

/**
 * Class ControllerHandler
 *
 * Gère l'exécution du contrôleur et de l'action demandés.
 */
final class ControllerHandler
{
    /**
     * @var array Tableau associatif contenant les noms du contrôleur et de l'action.
     */
    private array $url;

    /**
     * @var ViewParams Instance contenant les paramètres à transmettre à la vue.
     */
    private ViewParams $params;

    /**
     * Constructeur du ControllerHandler.
     *
     * Initialise les paramètres de la vue et nettoie le nom du contrôleur et de l'action.
     *
     * @param string|null $S_controller Nom du contrôleur.
     * @param string|null $S_action Nom de l'action.
     */
    public function __construct(?string $S_controller, ?string $S_action)
    {
        $this->params = new ViewParams();
        $this->url['controller'] = $this->cleanControllerName($S_controller);
        $this->url['action'] = $this->cleanActionName($S_action);
    }

    /**
     * Nettoie et valide le nom du contrôleur.
     *
     * Si le nom est vide, retourne 'AccueilController'. Sinon, formate et sécurise le nom.
     *
     * @param string|null $controller Nom du contrôleur.
     * @return string Nom du contrôleur nettoyé.
     * @throws InvalidArgumentException Si le nom de contrôleur contient des caractères invalides.
     */
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

    /**
     * Nettoie et valide le nom de l'action.
     *
     * Si le nom est vide, retourne 'defaultAction'. Sinon, formate et sécurise le nom.
     *
     * @param string|null $action Nom de l'action.
     * @return string Nom de l'action nettoyée.
     * @throws InvalidArgumentException Si le nom d'action contient des caractères invalides.
     */
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

    /**
     * Retourne l'URL sous forme de tableau associatif contenant le contrôleur et l'action.
     *
     * @return array Tableau associatif avec les clés 'controller' et 'action'.
     */
    public function getUrl(): array
    {
        return $this->url;
    }

    /**
     * Exécute l'action demandée du contrôleur.
     *
     * Instancie le contrôleur et appelle l'action spécifiée.
     * Gère les exceptions si le contrôleur ou l'action ne sont pas trouvés ou en cas d'erreur d'exécution.
     *
     * @return void
     * @throws RuntimeException Si le contrôleur ou l'action sont introuvables, ou en cas d'erreur lors de l'exécution.
     */
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
            $controllerInstance->setParams($this->params);
            call_user_func([$controllerInstance, $action]);
        } catch (Exception $e) {
            throw new RuntimeException("Erreur lors de l'exécution de l'action '$action' : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'objet ViewParams contenant les paramètres de la vue.
     *
     * @return ViewParams Instance contenant les paramètres.
     */
    public function getParams(): ViewParams
    {
        return $this->params;
    }
}