
<?php
final class PresentationFiliereController
{
    private string $titre  = "presentation_filiere";
    private string $css = "_assets/styles/informational/presentation_filiere.css";
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
        ViewHandler::show("informational/presentation_filiere", $this->params);
    }
}
