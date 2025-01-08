<?php

class Intranet
{
    private $db;
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }

    public function getUserRole(int $userId): string
    {
        // Validation stricte de l'ID utilisateur
        if ($userId <= 0) {
            throw new InvalidArgumentException("L'ID utilisateur doit être un entier positif.");
        }

        // Vérifier dans chaque table autorisée
        if ($this->existsInTable('ETUDIANTS', 'ID_ETUDIANT', $userId)) {
            return 'eleve';
        }

        if ($this->existsInTable('PROFESSEURS', 'ID_PROFESSEUR', $userId)) {
            return 'professeur';
        }

//        if ($this->existsInTable('STAFF', 'ID_STAFF', $userId)) {
//            return 'staff';
//        }

        // Renvoyer une erreur générique pour un utilisateur introuvable
        throw new RuntimeException("Utilisateur introuvable.");
    }

    private function existsInTable(string $tableName, string $columnName, int $userId): bool
    {
        // Liste blanche des tables et colonnes autorisées
        $allowedTables = [
            'ETUDIANTS' => 'ID_ETUDIANT',
            'PROFESSEURS' => 'ID_PROFESSEUR',
            'STAFF' => 'ID_STAFF',
        ];

        // Vérifier si la table et la colonne sont autorisées
        if (!isset($allowedTables[$tableName]) || $allowedTables[$tableName] !== $columnName) {
            throw new InvalidArgumentException("Table ou colonne non autorisée.");
        }

        // Requête préparée pour vérifier l'existence de l'utilisateur
        $query = "SELECT 1 FROM $tableName WHERE $columnName = :id LIMIT 1";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['id' => $userId]);

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