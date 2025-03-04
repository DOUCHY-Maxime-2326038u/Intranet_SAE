<?php

/**
 * Class IntranetEtudiant
 *
 * Stratégie d'affichage du tableau de bord pour les étudiants.
 */
class IntranetEtudiant implements IntranetStrategy
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
     * Retourne les données du tableau de bord pour un étudiant.
     *
     * @return array Tableau associatif contenant la dernière annonce, la réservation en cours et l'emploi du temps.
     */
    public function getDashboardData(): array {
        $derniereAnnonce = $this->model->getLastAnnonce();
        $reservationEnCours = $this->model->getReservationEtudiant($_SESSION['id_user']);
        $emploiDuTemps = $this->model->getEmploiDuTempsEtudiant($_SESSION['id_user']);

        return [
            'annonce'         => $derniereAnnonce,
            'reservation'     => $reservationEnCours,
            'emploi_du_temps' => $emploiDuTemps,
        ];
    }
}