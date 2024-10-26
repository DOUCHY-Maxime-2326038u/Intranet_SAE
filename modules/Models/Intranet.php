<?php

class Intranet
{
    private $db;
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }
    public function getAllAnnonces() {
        $queryAnnonce = "SELECT ANNONCE.TITRE AS titre, ANNONCE.CONTENU AS contenu, ANNONCE.DATE_PUBLICATION AS date_publication, PROFESSEURS.nom_prof AS professeur_nom, PROFESSEURS.PRENOM_PROF AS professeur_prenom 
                         FROM ANNONCE 
                         JOIN PROFESSEURS ON ANNONCE.ID_PROFESSEUR = PROFESSEURS.ID_PROFESSEUR";

        $stmt = $this->db->getPDO()->prepare($queryAnnonce);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // Récupère les résultats en objets
    }
}