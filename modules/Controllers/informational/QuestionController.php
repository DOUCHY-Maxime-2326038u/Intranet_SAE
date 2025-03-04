<?php



/**
 * Class QuestionController
 *
 * Gère les actions liées aux questions (affichage, ajout, etc.).
 */
final class QuestionController
{
    /**
     * @var ViewParams Stocke les paramètres à transmettre aux vues.
     */
    private ViewParams $params;

    /**
     * @var Question Instance du modèle de questions.
     */
    private Question $questionModel;

    /**
     * Constructeur.
     *
     * Initialise le modèle de questions.
     */
    public function __construct()
    {
        $this->questionModel = new Question();
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
     * Action par défaut.
     *
     * Prépare les paramètres (titre, feuille de style, token CSRF, etc.) et affiche la vue listant les questions publiées.
     *
     * @return void
     */
    public function defaultAction()
    {
        $this->params->set('titre', "Questions");
        $this->params->set('css', "/_assets/styles/informational/question.css");
        // Transmettre le token CSRF à la vue
        $this->params->set('csrf_token', $_SESSION['csrf_token']);
        // Récupérer les questions publiées depuis le modèle
        $questions = $this->questionModel->getQuestionPubliee();
        $this->params->set('questions', $questions);
        ViewHandler::show("informational/question", $this->params);
    }

    /**
     * Ajoute une nouvelle question.
     *
     * Vérifie que la requête est en POST, valide le token CSRF et la saisie utilisateur,
     * puis ajoute la question via le modèle. En cas d'erreur, renvoie à l'action par défaut avec un message.
     *
     * @return void
     */
    public function ajouterAction()
    {
        // Vérifier que la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: root.php?ctrl=Question');
            exit();
        }

        // Vérifier le token CSRF pour contrer les attaques CSRF
        if (!$this->verifyCSRFToken()) {
            $this->params->set('error', 'Jeton CSRF invalide.');
            $this->defaultAction();
            exit();
        }

        // Récupérer et nettoyer la question
        $questionText = trim($_POST['question'] ?? '');

        // Valider que la question a une longueur suffisante (ici au moins 5 caractères)
        if (!$this->validateQuestionInput($questionText)) {
            $this->params->set('error', 'Votre question doit contenir au moins 5 caractères.');
            $this->defaultAction();
            exit();
        }

        // Sanitize la question en retirant les tags HTML
        $questionText = strip_tags($questionText);

        // Enregistrement de la question via le modèle
        if ($this->questionModel->ajouterQuestion($questionText)) {
            header('Location: root.php?ctrl=Question');
            exit();
        } else {
            $this->params->set('error', 'Erreur lors de l\'enregistrement de la question.');
            $this->defaultAction();
        }
    }

    /**
     * Vérifie le token CSRF pour sécuriser la requête.
     *
     * @return bool True si le token est valide, sinon false.
     */
    private function verifyCSRFToken(): bool
    {
        return isset($_POST['csrf_token'], $_SESSION['csrf_token']) &&
            hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    /**
     * Valide la saisie de la question.
     *
     * La question doit contenir au moins 5 caractères.
     *
     * @param string $question La question à valider.
     * @return bool True si la question est valide, sinon false.
     */
    private function validateQuestionInput(string $question): bool
    {
        return (strlen($question) >= 5);
    }
}
