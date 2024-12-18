<?php
final class AproposController
{
    private ViewParams $params;

    public function setParams(ViewParams $params): void
    {
        $this->params = $params;
    }
    public function getParams(): ViewParams
    {
        return $this->params;
    }
    public function defaultAction()
    {
        $this->params->set('titre', "A propos");
        $this->params->set('css', "/_assets/styles/informational/apropos.css");
        ViewHandler::show("informational/aPropos", $this->params);
    }
}