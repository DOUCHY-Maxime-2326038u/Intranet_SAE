
<?php
final class IntranetController
{
    private string $titre  = "intranet";
    private string $css = "_assets/styles/account/intranet.css";
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
        ViewHandler::show("account/intranet", $this->params);
    }

}
