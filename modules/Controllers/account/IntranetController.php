
<?php
final class IntranetController
{
    private ViewParams $params;
    private Intranet $intranetModel;
    private IntranetStrategy $intranetStrategy;
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

    public function getIntranetStrategy(): IntranetStrategy
    {
        return $this->intranetStrategy;
    }

    public function setIntranetStrategy(IntranetStrategy $intranetStrategy): void
    {
        $this->intranetStrategy = $intranetStrategy;
    }
    private function initStrategy(): void
    {
        if (!isset($_SESSION['id_user'], $_SESSION['email_user'])) {
            header("Location: root.php?ctrl=Connexion");
            exit();
        }

        $role = $this->intranetModel->getUserRole($_SESSION['id_user'], $_SESSION['email_user']);
        $this->intranetStrategy = match ($role) {
            'eleve' => new IntranetEleve(),
            'professeur' => new IntranetProfesseur(),
            'staff' => new IntranetStaff(),
            default => throw new Exception("Rôle utilisateur inconnu : $role"),
        };
    }
    public function defaultAction()
    {
        echo "test";
        $this->initStrategy();
        $this->params->set('titre', "Intranet");
        $this->params->set('css', "/_assets/styles/account/intranet.css");
        ViewHandler::show("account/intranet/intranet",  $this->params);

    }

    public function dashboardAction()
    {
        if (!isset($this->intranetStrategy)) {
            $this->initStrategy();
        }
        ViewHandler::show($this->intranetStrategy->getDashboard());
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
