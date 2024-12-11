
<?php

final class AccueilController
{
    private string $titre  = "Accueil";
    private string $css = "_assets/styles/accueil.css";
    private string $js = "_assets/scripts/accueil.js";
    private array $params = [];

    public function __construct()
    {
        $this->params['titre'] = $this->titre;
        $this->params['css'] = $this->css;
        $this->params['js'] = $this->js;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function defaultAction()
    {
        ViewHandler::show("accueil", $this->params);
    }
}
