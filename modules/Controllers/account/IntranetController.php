
<?php
final class IntranetController
{
    private string $titre  = "intranet";
    private string $css = "/_assets/styles/account/intranet.css";
    private array $params = [];
    private Intranet $intranetModel;
    public function __construct()
    {
        $this->params['titre'] = $this->titre;
        $this->params['css'] = $this->css;
        $this->intranetModel = new Intranet();
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
        $annonces = $this->intranetModel->getAllAnnonces();
        $this->params['css'] = "/_assets/styles/intranet/annonces.css";
        $this->params['annonces'] = $annonces;

        ViewHandler::show('intranet/annonces', $this->params);

    }
    public function professeurAction()
    {
        ViewHandler::show('intranet/professeur');
    }

    public function errorAction()
    {
        ViewHandler::show('views/intranet/404');
    }
}
