<?php

class Intranet
{
    private $db;

    private array $allowedTables = [
        'ETUDIANTS' => 'ID_ETUDIANT',
        'PROFESSEURS' => 'ID_PROFESSEUR',
        'STAFF' => 'ID_STAFF',
    ];
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }

    public function getUserRole(int $userId, string $userEmail): string
    {
        // Validation stricte de l'ID utilisateur
        if ($userId <= 0) {
            throw new InvalidArgumentException("L'ID utilisateur doit être un entier positif.");
        }

        // Vérifier dans chaque table autorisée
        if ($this->existsInTable('ETUDIANTS', 'ID_ETUDIANT', 'EMAIL_ET', $userId, $userEmail)) {
            return 'eleve';
        }

        if ($this->existsInTable('PROFESSEURS', 'ID_PROFESSEUR', 'EMAIL_PROF', $userId, $userEmail)) {
            return 'professeur';
        }

//        if ($this->existsInTable('STAFF', 'ID_STAFF', $userId)) {
//            return 'staff';
//        }

        // Renvoyer une erreur générique pour un utilisateur introuvable
        throw new RuntimeException("Utilisateur introuvable.");
    }

    private function existsInTable(string $tableName, string $column1, string $column2, int $value1, string $value2): bool
    {

        // Vérifier si la table et la colonne sont autorisées
        if (!isset($this->allowedTables[$tableName]) || $this->allowedTables[$tableName] !== $column1) {
            throw new InvalidArgumentException("Table ou colonne non autorisée.");
        }

        // Requête préparée pour vérifier l'existence de l'utilisateur
        $query = "SELECT 1 FROM $tableName WHERE $column1 = :id AND $column2 = :email LIMIT 1";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['id' => $value1, 'email' => $value2]);

        return (bool) $stmt->fetchColumn();
    }

    public function getAllAnnonces() {
        $queryAnnonce = "SELECT ANNONCE.TITRE AS titre, ANNONCE.CONTENU AS contenu, ANNONCE.DATE_PUBLICATION AS date_publication, PROFESSEURS.nom_prof AS professeur_nom, PROFESSEURS.PRENOM_PROF AS professeur_prenom 
                         FROM ANNONCE 
                         JOIN PROFESSEURS ON ANNONCE.ID_PROFESSEUR = PROFESSEURS.ID_PROFESSEUR";

        $stmt = $this->db->getPDO()->prepare($queryAnnonce);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // Récupère les résultats en objets
    }
}