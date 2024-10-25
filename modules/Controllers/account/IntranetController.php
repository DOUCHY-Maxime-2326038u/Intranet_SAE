
<?php
final class IntranetController
{
    private string $titre  = "intranet";
    private string $css = "/_assets/styles/intranet.css";
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
        ViewHandler::show("account/intranet",  $this->params);

    }

    public function dashboardAction()
    {
        ViewHandler::show('intranet/dashboard');
    }

    public function annoncesAction()
    {
        ViewHandler::show('intranet/annonces');
        $this->params['css'] = "/_assets/styles/intranet/annonces.css";
    }
    public function professeurAction()
    {
        ViewHandler::show('intranet/professeur');
    }

    public function errorAction()
    {
        ViewHandler::show('views/intranet/404');
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
