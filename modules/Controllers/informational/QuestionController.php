<?php


final class QuestionController
{
    private ViewParams $params;
    private Question $questionModel;

    public function __construct()
    {
        $this->questionModel = new Question();
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
        $this->params->set('titre', "Questions");
        $this->params->set('css', "/_assets/styles/informational/question.css");
        // Transmettre le token CSRF à la vue
        $this->params->set('csrf_token', $_SESSION['csrf_token']);
        // Récupérer les questions publiées depuis le modèle
        $questions = $this->questionModel->getQuestionPubliee();
        $this->params->set('questions', $questions);
        ViewHandler::show("informational/question", $this->params);
    }

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


    private function verifyCSRFToken(): bool
    {
        return isset($_POST['csrf_token'], $_SESSION['csrf_token']) &&
            hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    private function validateQuestionInput(string $question): bool
    {
        return (strlen($question) >= 5);
    }
}
