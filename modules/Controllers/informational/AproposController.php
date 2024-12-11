<?php
final class AproposController
{
    private string $titre  = "apropos";
    private string $css = "_assets/styles/informational/apropos.css";
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
        ViewHandler::show("informational/aPropos", $this->params);
    }
}