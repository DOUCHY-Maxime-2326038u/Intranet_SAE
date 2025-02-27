<?php
final class ConnexionController
{
    private ViewParams $params;
    private Connexion $userModel;

    public function __construct()
    {
        $this->userModel = new Connexion();
    }

    public function setParams(ViewParams $params): void
    {
        $this->params = $params;
    }
    public function getParams(): ViewParams
    {
        return $this->params;
    }

    public function defaultAction(){
        $this->params->set('titre', "connexion");
        $this->params->set('css', "/_assets/styles/account/connexion.css");
        ViewHandler::show("account/connexion", $this->params);
    }

    public function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
            $identifiant = filter_var(trim($_POST['identifiant']));
            $mot_de_passe = $_POST['mot_de_passe'];

            // Authentifie l'utilisateur via le modèle
            $utilisateur = $this->userModel->authenticate($identifiant, $mot_de_passe);

            if ($utilisateur) {
                // Si c'est le mdp par défaut : redirige vers la page de changement de mdp
                if ($utilisateur['CHANGER_MDP'] === 1) {
                    echo "oui default";
                    $_SESSION['id_user'] = $utilisateur['ID_USER'];
                    $_SESSION['email_user'] = $utilisateur['EMAIL_USER'];
                    header("Location: /root.php?ctrl=Connexion&action=changePassword");
                    exit();
                }
                // Initialiser la session
                $_SESSION['id_user'] = $utilisateur['ID_USER'];
                //$_SESSION['nom_et'] = $utilisateur['NOM_ET'];
                $_SESSION['email_user'] = $utilisateur['EMAIL_USER'];

                // Rediriger vers l'intranet
                header("Location: /root.php?ctrl=Intranet");
                exit();
            }
            else {
                // Si erreur, renvoyer à la vue avec un message
                $this->params->set('erreur', "Identifiant ou mot de passe incorrect.");
                $this->defaultAction();
            }
        }
        else{
            var_dump($_POST);
        }

    }

    private function validatePassword(string $password): bool {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }
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
            }else{
                $this->params->set('erreur', "Le mdp doit contenir au minimum 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécialà.");
            }
        }

        // Affiche la vue de changement de mot de passe
        $this->params->set('titre', "Changer le mot de passe");
        ViewHandler::show("account/change_password", $this->params);
    }


}
