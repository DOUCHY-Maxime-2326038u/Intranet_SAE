<?php
/**
 * Classe Connexion
 *
 * Gère l'authentification et la mise à jour du mot de passe des utilisateurs
 * en interrogeant différentes tables (ETUDIANTS, PROFESSEURS, STAFF).
 */
class Connexion {
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
     * Authentifie un utilisateur à partir de son identifiant et mot de passe.
     *
     * Effectue une requête UNION sur les tables ETUDIANTS et PROFESSEURS
     * pour récupérer les informations de l'utilisateur. Le mot de passe fourni
     * est vérifié par rapport au hash stocké.
     *
     * @param mixed $identifiant L'email de l'utilisateur (ou identifiant).
     * @param mixed $mot_de_passe Le mot de passe fourni par l'utilisateur.
     * @return array|false Renvoie les informations de l'utilisateur sous forme de tableau associatif si l'authentification est réussie, sinon false.
     */
    public function authenticate($identifiant, $mot_de_passe)
    {
        $query = "
        SELECT ID_ETUDIANT AS ID_USER, EMAIL_ET AS EMAIL_USER, MDP_ET AS MDP_USER, CHANGER_MDP
        FROM ETUDIANTS
        WHERE EMAIL_ET = :identifiant
        UNION
        SELECT ID_PROFESSEUR AS ID_USER, EMAIL_PROF AS EMAIL_USER, MDP_PROF AS MDP_USER, CHANGER_MDP
        FROM PROFESSEURS
        WHERE EMAIL_PROF = :identifiant
        ";
        // La partie STAFF est commentée

        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['identifiant' => $identifiant]);

        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            if (password_verify($mot_de_passe, $utilisateur['MDP_USER'])) {
                return $utilisateur;
            }
        }
        return false;
    }

    /**
     * Met à jour le mot de passe de l'utilisateur.
     *
     * Le nouveau mot de passe est haché et mis à jour dans la table appropriée
     * (ETUDIANTS ou PROFESSEURS) en fonction du rôle de l'utilisateur.
     *
     * @param mixed $idUser L'ID de l'utilisateur.
     * @param mixed $emailUser L'email de l'utilisateur.
     * @param string $nouveauMotDePasse Le nouveau mot de passe en clair.
     * @return bool Renvoie true si la mise à jour a réussi, false sinon.
     */
    public function updatePassword($idUser, $emailUser, $nouveauMotDePasse): bool
    {
        $motDePasseHache = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

        // Détermine le rôle de l'utilisateur pour choisir la table à mettre à jour
        $role = (new Intranet)->getUserRole($idUser, $emailUser);
        var_dump($role);
        if ($role === 'etudiant') {
            $query = "UPDATE ETUDIANTS SET MDP_ET = :mdp, CHANGER_MDP = 0 WHERE ID_ETUDIANT = :id";
        } elseif ($role === 'professeur') {
            $query = "UPDATE PROFESSEURS SET MDP_PROF = :mdp, CHANGER_MDP = 0 WHERE ID_PROFESSEUR = :id";
        } else {
            echo "trouvé";
            return false;
        }

        $stmt = $this->db->getPDO()->prepare($query);
        return $stmt->execute(['mdp' => $motDePasseHache, 'id' => $idUser]);
    }
}
?>
