<?php
/**
 * Classe gérant les salles et les réservations.
 */
class Salle
{
    /**
     * Instance de la base de données.
     *
     * @var Database
     */
    private $db;

    /**
     * Constructeur de la classe Salle.
     *
     * Initialise la connexion à la base de données en utilisant le pattern Singleton.
     */
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');
    }

    /**
     * Récupère la liste des salles disponibles.
     *
     * Exécute une requête SQL pour obtenir toutes les salles.
     *
     * @return array Liste des salles sous forme de tableau associatif.
     */
    public function getSalles(): array {
        $query = "SELECT * FROM SALLE";
        $stmt = $this->db->getPDO()->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Réserve une salle pour un étudiant.
     *
     * Vérifie qu'il n'existe aucun conflit avec une réservation déjà existante pour la même salle,
     * puis insère une nouvelle réservation dans la base de données.
     *
     * @param int $idSalle ID de la salle à réserver.
     * @param int $idUser ID de l'étudiant effectuant la réservation.
     * @param string $debut Heure de début de la réservation (format DATETIME ou similaire).
     * @param string $fin Heure de fin de la réservation (format DATETIME ou similaire).
     * @return bool True si la réservation a été effectuée avec succès, false en cas de conflit ou d'échec.
     */
    public function reserverSalle(int $idSalle, int $idUser, string $debut, string $fin): bool {
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

        // Insérer la réservation dans la base de données
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
?>
