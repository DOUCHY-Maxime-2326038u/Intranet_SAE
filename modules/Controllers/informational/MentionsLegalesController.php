
<?php
final class MentionsLegalesController
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
        $this->params->set('titre', "Mentions légales");
        $this->params->set('css', "/_assets/styles/account/mentions_legales.css");
        ViewHandler::show("informational/mentions_legales");
    }
}
