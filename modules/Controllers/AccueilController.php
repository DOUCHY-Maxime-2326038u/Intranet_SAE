
<?php

final class AccueilController
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
        $this->params->set('titre', "Accueil");
        $this->params->set('css', "/_assets/styles/accueil.css");
        $this->params->set('js', "/_assets/scripts/accueil.js");
        ViewHandler::show("accueil", $this->params);
    }
}
