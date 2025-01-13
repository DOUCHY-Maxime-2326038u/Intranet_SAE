<?php

class IntranetProfesseur implements IntranetStrategy
{

    private Intranet $model;

    public function __construct(Intranet $model) {
        $this->model = $model;
    }

    public function getDashboardData(): array {
        $derniereAnnonce = $this->model->getLastAnnonce();
        $matiereStatistiques = $this->model->getStatistiquesMatiere($_SESSION['id_user']);


        return [
            'annonce' => $derniereAnnonce,
            'poster_annonce' => 'nah',
            'statistiques' => $matiereStatistiques,
        ];
    }
}