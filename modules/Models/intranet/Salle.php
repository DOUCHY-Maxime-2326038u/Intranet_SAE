<?php

class Salle
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');
    }
    public function getSalles(): array {
        $query = "SELECT * FROM SALLE";
        $stmt = $this->db->getPDO()->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reserverSalle(int $idSalle, int $idUser, string $debut, string $fin): bool {
        // Vérifier les conflits avec les cours
//        $queryCours = "
//            SELECT COUNT(*)
//            FROM COUR
//            WHERE ID_SALLE = :idSalle
//            AND (DEBUT < :fin AND FIN > :debut)
//        ";
//        $stmtCours = $this->db->getPDO()->prepare($queryCours);
//        $stmtCours->execute([
//            ':idSalle' => $idSalle,
//            ':debut' => $debut,
//            ':fin' => $fin,
//        ]);
//        $conflictCours = $stmtCours->fetchColumn();
//
//        if ($conflictCours > 0) {
//            return false; // Conflit avec un cours
//        }

        // Vérifier les conflits avec les réservations existantes
        $queryReservation = "
            SELECT COUNT(*) 
            FROM RESERVATION 
            WHERE ID_SALLE = :idSalle
            AND (DEBUT < :fin AND FIN > :debut)
        ";
        $stmtReservation = $this->db->getPDO()->prepare($queryReservation);
        $stmtReservation->execute([
            ':idSalle' => $idSalle,
            ':debut' => $debut,
            ':fin' => $fin,
        ]);
        $conflictReservation = $stmtReservation->fetchColumn();

        if ($conflictReservation > 0) {
            return false; // Conflit avec une réservation existante
        }

        // Insérer la réservation
        $queryInsert = "
            INSERT INTO RESERVATION (ID_SALLE, ID_ETUDIANT, DEBUT, FIN) 
            VALUES (:idSalle, :idUser, :debut, :fin)
        ";
        $stmtInsert = $this->db->getPDO()->prepare($queryInsert);
        return $stmtInsert->execute([
            ':idSalle' => $idSalle,
            ':idUser' => $idUser,
            ':debut' => $debut,
            ':fin' => $fin,
        ]);
    }



}