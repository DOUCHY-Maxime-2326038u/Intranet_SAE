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
        if (isset($_POST['connexion'])) {
            $identifiant = $_POST['identifiant'];
            $mot_de_passe = $_POST['mot_de_passe'];
            echo $identifiant;
            echo $mot_de_passe;

            // Authentifie l'utilisateur via le modèle
            $utilisateur = $this->userModel->authenticate($identifiant, $mot_de_passe);

            if ($utilisateur) {
                // Initialiser la session
                $_SESSION['id_user'] = $utilisateur['ID_USER'];
                $_SESSION['nom_ut'] = $utilisateur['NOM_UT'];
                $_SESSION['email_user'] = $utilisateur['EMAIL_USER'];

                // Rediriger vers l'intranet
                header("Location: root.php?ctrl=Intranet");
                exit();
            } else {
                // Si erreur, renvoyer à la vue avec un message
                $erreur = "Identifiant ou mot de passe incorrect.";
                ViewHandler::show("connexion", ['erreur' => $erreur]);
            }
        }
    }

}
