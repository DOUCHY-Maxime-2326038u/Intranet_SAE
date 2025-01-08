<?php

class Connexion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');
    }

    public function authenticate($identifiant, $mot_de_passe) {
        // Requête pour vérifier si l'utilisateur est un étudiant
        $queryEtudiant = "SELECT ID_ETUDIANT AS ID_USER, EMAIL_ET AS EMAIL_USER, DEFAUT_MDP_ET, MDP_ET 
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
            if (password_verify($mot_de_passe, $etudiant['MDP_ET']) || $mot_de_passe === $etudiant['DEFAUT_MDP_ET']) {
                $etudiant['ROLE'] = 'etudiant';
                return $etudiant;
            }
            //return $etudiant;
        }

        // Sinon, on vérifie dans la table des professeurs
        $queryProfesseur = "SELECT ID_PROFESSEUR AS ID_USER, EMAIL_PROF AS EMAIL_USER, PROFESSEUR_ADMIN, MDP_PROF, DEFAUT_MDP_PROF 
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
            if (password_verify($mot_de_passe, $professeur['MDP_PROF']) || $mot_de_passe === $professeur['DEFAUT_MDP_PROF']) {
                $professeur['ROLE'] = 'professeur';
                return $professeur;
            }
            //return $professeur;
        }

        // Si aucun utilisateur trouvé, renvoie false
        return false;
    }
    public function updatePassword($idUser, $nouveauMotDePasse) {
        // Hacher le nouveau mot de passe
        $motDePasseHache = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT);

        // Vérifier le rôle de l'utilisateur dans la session
        $role = $_SESSION['role'] ?? null;

        if ($role === 'etudiant') {
            // Mise à jour dans la table ETUDIANTS
            $query = "UPDATE ETUDIANTS SET MDP_ET = :mdp, DEFAUT_MDP_ET = NULL WHERE ID_ETUDIANT = :id";
        } elseif ($role === 'professeur') {
            // Mise à jour dans la table PROFESSEURS
            $query = "UPDATE PROFESSEURS SET MDP_PROF = :mdp, DEFAUT_MDP_PROF = NULL WHERE ID_PROFESSEUR = :id";
        } else {
            // Si aucun rôle valide, retour false
            echo "trouvé";
            return false;
        }

        // Exécuter la requête
        $stmt = $this->db->getPDO()->prepare($query);
        return $stmt->execute(['mdp' => $motDePasseHache, 'id' => $idUser]);
    }
}
