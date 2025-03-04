<?php
/**
 * Class AccueilController
 *
 * Gère les actions liées à l'accueil du site.
 */
final class AccueilController
{
    /**
     * @var ViewParams Stocke les paramètres à transmettre aux vues.
     */
    private ViewParams $params;

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
     * Action par défaut.
     *
     * Prépare les paramètres (titre, feuille de style, script JS) et affiche la page d'accueil.
     *
     * @return void
     */
    public function defaultAction()
    {
        $this->params->set('titre', "Accueil");
        $this->params->set('css', "/_assets/styles/accueil.css");
        $this->params->set('js', "/_assets/scripts/accueil.js");
        ViewHandler::show("accueil", $this->params);
    }

    /**
     * Action de déconnexion.
     *
     * Détruit la session en cours puis redirige vers la page d'accueil.
     *
     * @return void
     */
    public function logoutAction(){
        session_destroy();
        $this->defaultAction();
    }
}
