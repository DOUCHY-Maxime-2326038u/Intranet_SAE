<?php

class IntranetEtudiant implements IntranetStrategy
{
    private Intranet $model;

    public function __construct(Intranet $model) {
        $this->model = $model;
    }
    public function getDashboardData(): array {
        $derniereAnnonce = $this->model->getLastAnnonce();
        $reservationEnCours = $this->model->getReservationEtudiant($_SESSION['id_user']);
        $emploiDuTemps = $this->model->getEmploiDuTempsEtudiant($_SESSION['id_user']);

        return [
            'annonce' => $derniereAnnonce,
            'reservation' => $reservationEnCours,
            'emploi_du_temps' => $emploiDuTemps,
        ];
    }
}