<?php

class Connexion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');
    }

    public function authenticate($identifiant, $mot_de_passe)
    {
        // Requête pour vérifier dans toutes les tables (ETUDIANTS, PROFESSEURS, STAFF)
        $query = "
        SELECT ID_ETUDIANT AS ID_USER, EMAIL_ET AS EMAIL_USER
        FROM ETUDIANTS
        WHERE EMAIL_ET = :identifiant
        UNION
        SELECT ID_PROFESSEUR AS ID_USER, EMAIL_PROF AS EMAIL_USER 
        FROM PROFESSEURS
        WHERE EMAIL_PROF = :identifiant
        ";
//        UNION
//        SELECT ID_STAFF AS ID_USER, EMAIL_STAFF AS EMAIL_USER
//        FROM STAFF
//        WHERE EMAIL_STAFF = :identifiant
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['identifiant' => $identifiant]);

        // Récupérer les informations de l'utilisateur
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si un utilisateur est trouvé, le retourner avec son rôle
        if ($utilisateur) {
            return $utilisateur;
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
