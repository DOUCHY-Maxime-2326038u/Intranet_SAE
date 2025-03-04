
<?php
/**
 * Class IntranetController
 *
 * Gère les actions de l'intranet pour les utilisateurs connectés en fonction de leur rôle.
 */
final class IntranetController
{
    /**
     * @var ViewParams Stocke les paramètres à transmettre aux vues.
     */
    private ViewParams $params;

    /**
     * @var Intranet Instance du modèle de l'intranet.
     */
    private Intranet $intranetModel;

    /**
     * @var IntranetStrategy Stratégie spécifique à l'utilisateur (étudiant ou professeur).
     */
    private IntranetStrategy $intranetStrategy;

    /**
     * Constructeur.
     *
     * Initialise le modèle de l'intranet.
     */
    public function __construct()
    {
        $this->intranetModel = new Intranet();
    }

    /**
     * Définit les paramètres de la vue.
     *
     * @param ViewParams $params Instance contenant les paramètres de la vue.
     * @return void
     */
    public function setParams(ViewParams $params): void
    {
        $this->params = $params;
    }

    /**
     * Retourne les paramètres de la vue.
     *
     * @return ViewParams Les paramètres de la vue.
     */
    public function getParams(): ViewParams
    {
        return $this->params;
    }

    /**
     * Retourne la stratégie d'intranet courante.
     *
     * @return IntranetStrategy La stratégie d'intranet.
     */
    public function getIntranetStrategy(): IntranetStrategy
    {
        return $this->intranetStrategy;
    }

    /**
     * Définit la stratégie d'intranet à utiliser.
     *
     * @param IntranetStrategy $intranetStrategy Instance de la stratégie d'intranet.
     * @return void
     */
    public function setIntranetStrategy(IntranetStrategy $intranetStrategy): void
    {
        $this->intranetStrategy = $intranetStrategy;
    }

    /**
     * Initialise la stratégie en fonction du rôle de l'utilisateur.
     *
     * Si l'utilisateur n'est pas connecté, redirige vers la page de connexion.
     *
     * @return void
     * @throws Exception Si le rôle de l'utilisateur est inconnu.
     */
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

    /**
     * Action par défaut de l'intranet.
     *
     * Initialise la stratégie, définit les paramètres de la vue et affiche la page principale de l'intranet.
     *
     * @return void
     */
    public function defaultAction()
    {
        $this->initStrategy();
        $this->params->set('titre', "Intranet");
        $this->params->set('css', "/_assets/styles/account/intranet.css");
        ViewHandler::show("account/intranet/intranet",  $this->params);
        #$this->intranetModel->insertIntoDatabase(ICS::extractGroup(ICS::parseICS("modules/Controllers/account/AN1.ics")), 1);
        #$this->intranetModel->insertIntoDatabase(ICS::extractGroup(ICS::parseICS("modules/Controllers/account/AN2.ics")), 2);
    }

    /**
     * Affiche le tableau de bord personnalisé.
     *
     * Récupère les données via la stratégie d'intranet et affiche la vue correspondante.
     *
     * @return void
     */
    public function dashboardAction()
    {
        if (!isset($this->intranetStrategy)) {
            $this->initStrategy();
        }
        $dashboardData = $this->intranetStrategy->getDashboardData();
        $this->params->set('dashboardData', $dashboardData);
        ViewHandler::show('account/intranet/dashboard', $this->params);
    }

    /**
     * Affiche la page des annonces de l'intranet.
     *
     * Récupère toutes les annonces depuis le modèle et les transmet à la vue.
     *
     * @return void
     */
    public function annoncesAction()
    {
        $annonces = $this->intranetModel->getAllAnnonces();
        $this->params->set('annonces', $annonces);
        ViewHandler::show('account/intranet/annonces', $this->params);
    }

    /**
     * Affiche la page du BDE.
     *
     * @return void
     */
    public function bdeAction()
    {
        ViewHandler::show('account/intranet/bde', $this->params);
    }

    /**
     * Affiche la vue spécifique aux professeurs.
     *
     * @return void
     */
    public function professeurAction()
    {
        ViewHandler::show('intranet/professeur', $this->params);
    }

    /**
     * Permet aux professeurs de poster une annonce.
     *
     * Vérifie que les champs sont remplis puis poste l'annonce via le modèle.
     *
     * @return void
     */
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

    /**
     * Gère la réservation de salle.
     *
     * Si la requête est POST, effectue la réservation et redirige.
     * Sinon, affiche la vue avec la liste des salles.
     *
     * @return void
     */
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

    /**
     * Affiche la page d'erreur 404 de l'intranet.
     *
     * @return void
     */
    public function errorAction()
    {
        ViewHandler::show('views/intranet/404', $this->params);
    }

    /**
     * Affiche la page de revue des questions pour les professeurs.
     *
     * Vérifie le rôle de l'utilisateur et récupère les questions non publiées.
     *
     * @return void
     */
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

    /**
     * Met à jour la question (réponse et publication).
     *
     * Vérifie que la requête est POST et que l'utilisateur est professeur.
     * Met à jour la réponse et publie la question le cas échéant.
     *
     * @return void
     */
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

    /**
     * Supprime une question.
     *
     * Vérifie que l'utilisateur est professeur et que la requête est POST, puis supprime la question.
     *
     * @return void
     */
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