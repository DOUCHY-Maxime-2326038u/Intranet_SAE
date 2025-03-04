<?php
/**
 * Class BdeController
 *
 * Gère les actions liées au BDE.
 */
final class BdeController
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
     * Prépare les paramètres (titre, feuille de style, script JS) et affiche la page du BDE.
     *
     * @return void
     * @throws Exception
     */
    public function defaultAction()
    {
        $this->params->set('titre', "Bde");
        $this->params->set('css', "/_assets/styles/informational/bde.css");
        ViewHandler::show("informational/bde", $this->params);
    }

}
