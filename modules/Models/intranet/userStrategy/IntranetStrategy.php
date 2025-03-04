<?php

/**
 * Interface IntranetStrategy
 *
 * Définit la méthode permettant d'obtenir les données du tableau de bord pour l'intranet.
 */
interface IntranetStrategy
{
    /**
     * Récupère les données nécessaires à l'affichage du tableau de bord.
     *
     * @return array Tableau associatif contenant les données du dashboard.
     */
    public function getDashboardData(): array;
}