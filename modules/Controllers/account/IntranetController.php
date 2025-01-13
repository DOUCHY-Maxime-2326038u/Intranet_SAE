
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
            'etudiant' => new IntranetEtudiant($this->intranetModel),
            'professeur' => new IntranetProfesseur($this->intranetModel),
            default => throw new Exception("Rôle utilisateur inconnu : $role"),
        };
    }
    public function defaultAction()
    {
        $this->initStrategy();
        $this->params->set('titre', "Intranet");
        $this->params->set('css', "/_assets/styles/account/intranet.css");
        ViewHandler::show("account/intranet/intranet",  $this->params);
        //$this->intranetModel->insertIntoDatabase(ICS::extractGroup(ICS::parseICS('modules/Controllers/account/AN2.ics')), 2);

    }

    public function dashboardAction()
    {
        if (!isset($this->intranetStrategy)) {
            $this->initStrategy();
        }
        $dashboardData = $this->intranetStrategy->getDashboardData();

        // Charger la vue avec les données
        $this->params->set('dashboardData', $dashboardData);
        $this->params->set('css', '/_assets/styles/dashboard.css');
        $this->params->set('titre', 'Dashboard');

        ViewHandler::show('account/intranet/dashboard', $this->params);
    }

    public function annoncesAction()
    {
        $annonces = $this->intranetModel->getAllAnnonces();
        $this->params->set('annonces', $annonces);

        ViewHandler::show('account/intranet/annonces', $this->params);

    }
    public function professeurAction()
    {
        ViewHandler::show('intranet/professeur');
    }

    public function posterAction(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'poster_annonce') {
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $idProfesseur = $_SESSION['id_user'];

            // Vérifiez que les champs sont remplis
            if (!empty($titre) && !empty($contenu)) {
                $success = $this->intranetModel->posterAnnonce($idProfesseur, $titre, $contenu);
                $this->params->set('success', $success);
                $this->defaultAction();
            } else {
                echo '<p class="error">Veuillez remplir tous les champs.</p>';
            }
        }
    }

    public function reservationAction(){
        $salleModel = new Salle();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idSalle = $_POST['id_salle'];
            $debut = $_POST['debut'];
            $fin = $_POST['fin'];
            $idUser = $_SESSION['id_user'];
            $success = $salleModel->reserverSalle($idSalle, $idUser, $debut, $fin);
            $this->params->set('success', $success);
            $this->defaultAction();
        }
        else{
            $salles = $salleModel->getSalles();
            $this->params->set('salles', $salles);
            ViewHandler::show('account/intranet/salle', $this->params);
        }

    }



    public function errorAction()
    {
        ViewHandler::show('views/intranet/404');
    }
}
