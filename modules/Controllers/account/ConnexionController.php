<?php
final class ConnexionController
{
    private string $titre  = "connexion";
    private string $css = "_assets/styles/account/connexion.css";
    private array $params = [];
    private $userModel;

    public function __construct() {
        // Instancie la base de données une seule fois pour ce contrôleur
        $this->userModel = new Connexion();  // Passe l'instance de la base de données au modèle
        $this->params['titre'] = $this->titre;
        $this->params['css'] = $this->css;
    }

    public function defaultAction(){
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

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
