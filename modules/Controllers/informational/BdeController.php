
<?php
final class BdeController
{
    private string $titre  = "bde";
    private string $css = "/_assets/styles/informational/bde.css";
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
        ViewHandler::show("informational/bde", $this->params);
    }

}
