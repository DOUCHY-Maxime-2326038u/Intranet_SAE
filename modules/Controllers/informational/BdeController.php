
<?php
final class BdeController
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
        $this->params->set('titre', "Bde");
        $this->params->set('css', "/_assets/styles/informational/bde.css");
        ViewHandler::show("informational/bde", $this->params);
    }

}
