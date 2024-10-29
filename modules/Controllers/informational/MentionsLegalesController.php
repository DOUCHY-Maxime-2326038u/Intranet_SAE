
<?php
final class MentionsLegalesController
{
    private string $titre  = "mentions_legales";
    private string $css = "/_assets/styles/mentions_legales.css";
    private array $params = [];

    public function __construct()
    {
        $this->params['titre'] = $this->titre;
        $this->params['css'] = $this->css;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function defaultAction()
    {
        ViewHandler::show("informational/mentions_legales",  $this->params);
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
