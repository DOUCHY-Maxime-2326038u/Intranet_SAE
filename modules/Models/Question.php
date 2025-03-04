<?php
/**
 * Classe Question
 *
 * Gère les opérations CRUD relatives aux questions posées par les utilisateurs.
 */
class Question
{
    /**
     * Instance de la base de données.
     *
     * @var Database
     */
    private $db;

    /**
     * Constructeur.
     *
     * Initialise la connexion à la base de données via le pattern Singleton.
     */
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');
    }

    /**
     * Récupère la liste des questions publiées.
     *
     * Exécute une requête pour récupérer toutes les questions marquées comme publiées,
     * triées par date décroissante.
     *
     * @return array Tableau associatif des questions publiées.
     */
    public function getQuestionPubliee(){
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM QUESTION WHERE EST_PUBLIEE = 1 ORDER BY DATE_QUESTION DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une nouvelle question.
     *
     * Insère une question dans la table QUESTION.
     *
     * @param string $question Le contenu de la question.
     * @return bool Renvoie true si l'insertion est réussie, false sinon.
     */
    public function ajouterQuestion($question){
        $stmt = $this->db->getPDO()->prepare("INSERT INTO QUESTION (CONTENU) VALUES (:question)");
        return $stmt->execute([':question' => $question]);
    }

    /**
     * Enregistre une réponse à une question.
     *
     * Met à jour la colonne REPONSE de la question identifiée par son ID.
     *
     * @param int $id L'ID de la question.
     * @param string $reponse La réponse à associer à la question.
     * @return bool Renvoie true si la mise à jour est réussie, false sinon.
     */
    public function repondreQuestion($id, $reponse) {
        $stmt = $this->db->getPDO()->prepare("UPDATE QUESTION SET REPONSE = :reponse WHERE id = :id");
        return $stmt->execute([
            ':reponse' => $reponse,
            ':id' => $id
        ]);
    }

    /**
     * Publie une question.
     *
     * Met à jour la colonne EST_PUBLIEE de la question pour la marquer comme publiée.
     *
     * @param int $id L'ID de la question à publier.
     * @return bool Renvoie true si la mise à jour est réussie, false sinon.
     */
    public function publierQuestion($id) {
        $stmt = $this->db->getPDO()->prepare("UPDATE QUESTION SET EST_PUBLIEE = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Récupère toutes les questions.
     *
     * Exécute une requête pour récupérer toutes les questions, triées par date décroissante.
     *
     * @return array Tableau associatif de toutes les questions.
     */
    public function getQuestions() {
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM QUESTION ORDER BY DATE_QUESTION DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les questions non publiées.
     *
     * Exécute une requête pour récupérer toutes les questions en attente de publication.
     *
     * @return array Tableau associatif des questions non publiées.
     */
    public function getQuestionsNonPubliees(){
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM QUESTION WHERE EST_PUBLIEE = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime une question.
     *
     * Supprime la question identifiée par son ID de la table QUESTION.
     *
     * @param int $id L'ID de la question à supprimer.
     * @return bool Renvoie true si la suppression est réussie, false sinon.
     */
    public function supprimerQuestion($id)
    {
        $stmt = $this->db->getPDO()->prepare("DELETE FROM QUESTION WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
