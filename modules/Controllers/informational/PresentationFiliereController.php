<?php
final class PresentationFiliereController
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
        $this->params->set('titre', "Présentation Filière");
        $this->params->set('css', "/_assets/styles/informational/presentation_filiere.css");
        ViewHandler::show("informational/presentation_filiere", $this->params);
    }
}
