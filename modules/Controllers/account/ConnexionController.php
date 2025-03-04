<?php
/**
 * Class ConnexionController
 *
 * Gère les actions liées à la connexion des utilisateurs (authentification, changement de mot de passe, etc.).
 */
final class ConnexionController
{
    /**
     * @var ViewParams Stocke les paramètres à transmettre aux vues.
     */
    private ViewParams $params;

    /**
     * @var Connexion Instance du modèle de connexion pour gérer l'authentification.
     */
    private Connexion $userModel;

    /**
     * Constructeur.
     *
     * Initialise le modèle de connexion.
     */
    public function __construct()
    {
        $this->userModel = new Connexion();
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
     * Action par défaut affichant la page de connexion.
     *
     * Prépare les paramètres (titre, feuille de style) et affiche la vue correspondante.
     *
     * @return void
     */
    public function defaultAction()
    {
        $this->params->set('titre', "connexion");
        $this->params->set('css', "/_assets/styles/account/connexion.css");
        ViewHandler::show("account/connexion", $this->params);
    }

    /**
     * Action de connexion.
     *
     * Vérifie la méthode POST et authentifie l'utilisateur via le modèle.
     * En cas de succès, initialise la session et redirige vers l'intranet ou la page de changement de mot de passe.
     * En cas d'échec, renvoie à la vue avec un message d'erreur.
     *
     * @return void
     */
    public function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
            $identifiant = filter_var(trim($_POST['identifiant']));
            $mot_de_passe = $_POST['mot_de_passe'];

            // Authentifie l'utilisateur via le modèle
            $utilisateur = $this->userModel->authenticate($identifiant, $mot_de_passe);

            if ($utilisateur) {
                // Si le mot de passe est le mdp par défaut : redirige vers la page de changement de mdp
                if ($utilisateur['CHANGER_MDP'] === 1) {
                    echo "oui default";
                    $_SESSION['id_user'] = $utilisateur['ID_USER'];
                    $_SESSION['email_user'] = $utilisateur['EMAIL_USER'];
                    header("Location: /root.php?ctrl=Connexion&action=changePassword");
                    exit();
                }
                // Initialiser la session
                $_SESSION['id_user'] = $utilisateur['ID_USER'];
                $_SESSION['email_user'] = $utilisateur['EMAIL_USER'];

                // Rediriger vers l'intranet
                header("Location: /root.php?ctrl=Intranet");
                exit();
            } else {
                // Si erreur, renvoyer à la vue avec un message
                $this->params->set('erreur', "Identifiant ou mot de passe incorrect.");
                $this->defaultAction();
            }
        } else {
            var_dump($_POST);
        }
    }

    /**
     * Valide la complexité d'un mot de passe.
     *
     * Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
     *
     * @param string $password Le mot de passe à valider.
     * @return bool True si le mot de passe respecte la complexité, sinon false.
     */
    private function validatePassword(string $password): bool {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }

    /**
     * Action de changement de mot de passe.
     *
     * Vérifie et met à jour le mot de passe de l'utilisateur. En cas d'erreur, affiche la vue avec un message d'erreur.
     *
     * @return void
     */
    public function changePasswordAction() {
        if (isset($_POST['changer_mot_de_passe'])) {
            $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'];
            $idUser = $_SESSION['id_user'];
            $emailUser = $_SESSION['email_user'];

            if ($this->validatePassword($nouveauMotDePasse)) {
                // Mettre à jour le mot de passe dans la base de données
                if ($this->userModel->updatePassword($idUser, $emailUser, $nouveauMotDePasse)) {
                    header("Location: /root.php?ctrl=Intranet");
                    exit();
                } else {
                    $this->params->set('erreur', "Erreur lors de la mise à jour du mot de passe.");
                }
            } else {
                $this->params->set('erreur', "Le mdp doit contenir au minimum 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécialà.");
            }
        }

        // Affiche la vue de changement de mot de passe
        $this->params->set('titre', "Changer le mot de passe");
        ViewHandler::show("account/change_password", $this->params);
    }
}