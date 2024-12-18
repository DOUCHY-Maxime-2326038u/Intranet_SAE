
<?php
final class IntranetController
{
    private ViewParams $params;
    private Intranet $intranetModel;
    public function __construct()
    {
        $this->intranetModel = new Intranet();
    }
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
        $this->params->set('titre', "Intranet");
        $this->params->set('css', "/_assets/styles/account/intranet.css");
        ViewHandler::show("account/intranet",  $this->params);

    }

    public function dashboardAction()
    {
        ViewHandler::show('intranet/dashboard', );
    }

    public function annoncesAction()
    {
        $annonces = $this->intranetModel->getAllAnnonces();
        $this->params->set('annonces', $annonces);

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
