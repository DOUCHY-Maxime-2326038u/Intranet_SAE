
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
    }

    public function dashboardAction()
    {
        if (!isset($this->intranetStrategy)) {
            $this->initStrategy();
        }
        $dashboardData = $this->intranetStrategy->getDashboardData();
        $this->params->set('dashboardData', $dashboardData);
        ViewHandler::show('account/intranet/dashboard', $this->params);
    }

    public function annoncesAction()
    {
        $annonces = $this->intranetModel->getAllAnnonces();
        $this->params->set('annonces', $annonces);
        ViewHandler::show('account/intranet/annonces', $this->params);
    }

    public function bdeAction()
    {
        ViewHandler::show('account/intranet/bde', $this->params);
    }

    public function professeurAction()
    {
        ViewHandler::show('intranet/professeur', $this->params);
    }

    public function posterAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'poster_annonce') {
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $idProfesseur = $_SESSION['id_user'];
            if (!empty($titre) && !empty($contenu)) {
                $success = $this->intranetModel->posterAnnonce($idProfesseur, $titre, $contenu);
                $this->params->set('success', $success);
                $this->defaultAction();
            } else {
                echo '<p class="error">Veuillez remplir tous les champs.</p>';
            }
        }
    }

    public function reservationAction()
    {
        $salleModel = new Salle();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idSalle = $_POST['id_salle'];
            $debut = $_POST['debut'];
            $fin = $_POST['fin'];
            $idUser = $_SESSION['id_user'];
            $success = $salleModel->reserverSalle($idSalle, $idUser, $debut, $fin);
            $this->params->set('success', $success);
            $this->defaultAction();
        } else {
            $salles = $salleModel->getSalles();
            $this->params->set('salles', $salles);
            ViewHandler::show('account/intranet/salle', $this->params);
        }
    }

    public function errorAction()
    {
        ViewHandler::show('views/intranet/404', $this->params);
    }


    public function reviewQuestionsAction()
    {
        // Vérifier que l'utilisateur est bien un professeur
        $role = $this->intranetModel->getUserRole($_SESSION['id_user'], $_SESSION['email_user']);
        if ($role !== 'professeur') {
            header("Location: root.php?ctrl=Intranet");
            exit();
        }

        // Charger le modèle Question et récupérer les questions non publiées (en attente de validation)
        $questionModel = new Question();
        $questions = $questionModel->getQuestionsNonPubliees();

        $this->params->set('titre', "Revue des Questions");
        $this->params->set('css', "/_assets/styles/account/intranet.css");
        $this->params->set('questions', $questions);
        ViewHandler::show('account/intranet/questions_review', $this->params);
    }


    public function majQuestionAction()
    {
        // Vérifier que l'utilisateur est bien un professeur
        $role = $this->intranetModel->getUserRole($_SESSION['id_user'], $_SESSION['email_user']);
        if ($role !== 'professeur') {
            header("Location: root.php?ctrl=Intranet");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ctrl=Intranet');
            exit();
        }

        $id = intval($_POST['id'] ?? 0);
        $answer = trim($_POST['answer'] ?? '');
        // On vérifie si la case de publication est cochée (valeur "1" attendue)
        $isPubliee = (isset($_POST['is_publiee']) && $_POST['is_publiee'] == 1) ? 1 : 0;

        $questionModel = new Question();
        // Met à jour la réponse si elle n'est pas vide
        if ($answer !== '') {
            $questionModel->repondreQuestion($id, $answer);
        }
        // Publie la question si le professeur a coché la case
        if ($isPubliee === 1) {
            $questionModel->publierQuestion($id);
        }

        header('Location: root.php?ctrl=Intranet');
        exit();
    }

    public function supprimerQuestionAction()
    {
        // Vérifier que l'utilisateur est bien un professeur
        $role = $this->intranetModel->getUserRole($_SESSION['id_user'], $_SESSION['email_user']);
        if ($role !== 'professeur') {
            header("Location: root.php?ctrl=Intranet");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: root.php?ctrl=Intranet');
            exit();
        }
        $id = intval($_POST['id'] ?? 0);
        $questionModel = new Question();
        $questionModel->supprimerQuestion($id);
        header('Location: root.php?ctrl=Intranet');
        exit();
    }
}

