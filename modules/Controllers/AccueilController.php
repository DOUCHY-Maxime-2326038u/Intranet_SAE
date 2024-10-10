
<?php
final class AccueilController
{
    private string $titre  = "Accueil";
    private array $params = [];

    public function __construct()
    {
        $this->params['titre'] = $this->titre;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function defaultAction()
    {
        ViewHandler::show("Accueil", $this->params);
    }
}
