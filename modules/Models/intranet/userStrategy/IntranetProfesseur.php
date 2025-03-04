<?php

/**
 * Class IntranetProfesseur
 *
 * Stratégie d'affichage du tableau de bord pour les professeurs.
 */
class IntranetProfesseur implements IntranetStrategy
{
    /**
     * @var Intranet Modèle Intranet utilisé pour récupérer les données.
     */
    private Intranet $model;

    /**
     * Constructeur.
     *
     * @param Intranet $model Instance du modèle Intranet.
     */
    public function __construct(Intranet $model) {
        $this->model = $model;
    }

    /**
     * Retourne les données du tableau de bord pour un professeur.
     *
     * @return array Tableau associatif contenant la dernière annonce, un indicateur pour poster une annonce et les statistiques.
     */
    public function getDashboardData(): array {
        $derniereAnnonce = $this->model->getLastAnnonce();
        $matiereStatistiques = $this->model->getStatistiquesMatiere($_SESSION['id_user']);

        return [
            'annonce'         => $derniereAnnonce,
            'poster_annonce'  => 'nah',
            'statistiques'    => $matiereStatistiques,
        ];
    }
}