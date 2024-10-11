<?php

class Connexion {
    private $db;

    public function __construct() {
        // Utiliser le Singleton directement
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }

    public function authenticate($identifiant, $mot_de_passe) {
        // Requête pour vérifier si l'utilisateur est un étudiant
        $queryEtudiant = "SELECT ID_ETUDIANT AS ID_USER, EMAIL_ET AS EMAIL_USER 
                          FROM ETUDIANTS 
                          WHERE (EMAIL_ET = :identifiant)";
        // AND mot_de_passe = :mot_de_passe
        $stmtEtudiant = $this->db->getPDO()->prepare($queryEtudiant);
        $stmtEtudiant->execute(['identifiant' => $identifiant]);
        // mettre cette ligne une fois l'attribut mdp créer dans la bdd :
        //$stmtEtudiant->execute(['identifiant' => $identifiant, 'mot_de_passe' => $mot_de_passe]);
        $etudiant = $stmtEtudiant->fetch(PDO::FETCH_ASSOC);

        // Si l'utilisateur est trouvé dans les étudiants, on le retourne
        if ($etudiant) {
            return $etudiant;
        }

        // Sinon, on vérifie dans la table des professeurs
        $queryProfesseur = "SELECT ID_PROFESSEUR AS ID_USER, EMAIL_PROF AS EMAIL_USER, PROFESSEUR_ADMIN 
                            FROM PROFESSEURS 
                            WHERE (EMAIL_PROF = :identifiant)";
        // AND mot_de_passe = :mot_de_passe
        $stmtProfesseur = $this->db->getPDO()->prepare($queryProfesseur);
        $stmtProfesseur->execute(['identifiant' => $identifiant]);
        // mettre cette ligne une fois l'attribut mdp créer dans la bdd :
        //$stmtProfesseur->execute(['identifiant' => $identifiant, 'mot_de_passe' => $mot_de_passe]);
        $professeur = $stmtProfesseur->fetch(PDO::FETCH_ASSOC);

        // Retourner les informations du professeur s'il est trouvé
        if ($professeur) {
            return $professeur;
        }

        // Si aucun utilisateur trouvé, renvoie false
        return false;
    }
}
