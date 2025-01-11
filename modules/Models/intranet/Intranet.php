<?php

class Intranet
{
    private $db;

    private array $allowedTables = [
        'ETUDIANTS' => 'ID_ETUDIANT',
        'PROFESSEURS' => 'ID_PROFESSEUR',
        'STAFF' => 'ID_STAFF',
    ];
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }

    public function getUserRole(int $userId, string $userEmail): string
    {
        // Validation stricte de l'ID utilisateur
        if ($userId <= 0) {
            throw new InvalidArgumentException("L'ID utilisateur doit être un entier positif.");
        }

        // Vérifier dans chaque table autorisée
        if ($this->existsInTable('ETUDIANTS', 'ID_ETUDIANT', 'EMAIL_ET', $userId, $userEmail)) {
            return 'eleve';
        }

        if ($this->existsInTable('PROFESSEURS', 'ID_PROFESSEUR', 'EMAIL_PROF', $userId, $userEmail)) {
            return 'professeur';
        }

//        if ($this->existsInTable('STAFF', 'ID_STAFF', $userId)) {
//            return 'staff';
//        }

        // Renvoyer une erreur générique pour un utilisateur introuvable
        throw new RuntimeException("Utilisateur introuvable.");
    }

    private function existsInTable(string $tableName, string $column1, string $column2, int $value1, string $value2): bool
    {

        // Vérifier si la table et la colonne sont autorisées
        if (!isset($this->allowedTables[$tableName]) || $this->allowedTables[$tableName] !== $column1) {
            throw new InvalidArgumentException("Table ou colonne non autorisée.");
        }

        // Requête préparée pour vérifier l'existence de l'utilisateur
        $query = "SELECT 1 FROM $tableName WHERE $column1 = :id AND $column2 = :email LIMIT 1";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['id' => $value1, 'email' => $value2]);

        return (bool) $stmt->fetchColumn();
    }

    public function getAllAnnonces() {
        $queryAnnonce = "SELECT ANNONCE.TITRE AS titre, ANNONCE.CONTENU AS contenu, ANNONCE.DATE_PUBLICATION AS date_publication, PROFESSEURS.nom_prof AS professeur_nom, PROFESSEURS.PRENOM_PROF AS professeur_prenom 
                         FROM ANNONCE 
                         JOIN PROFESSEURS ON ANNONCE.ID_PROFESSEUR = PROFESSEURS.ID_PROFESSEUR";

        $stmt = $this->db->getPDO()->prepare($queryAnnonce);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // Récupère les résultats en objets
    }

//    public function insertCoursesByYear($events, $year) {
//        $insertCoursQuery = "INSERT INTO COUR (NOM_COUR, DEBUT, FIN, SALLE)
//                         VALUES (:titre, :debut, :fin, :salle)";
//        $insertGroupQuery = "INSERT INTO GROUPE_COUR (ID_COUR, ID_GROUPE)
//                         VALUES (:cours_id, :groupe)";
//
//        $stmtCours = $this->db->getPDO()->prepare($insertCoursQuery);
//        $stmtGroup = $this->db->getPDO()->prepare($insertGroupQuery);
//
//        foreach ($events as $event) {
//            $groups = ICS::extractGroupsByYear($event['SUMMARY'], $event['DESCRIPTION'] ?? '', $year);
//
//            // Insérer le cours principal
//            $stmtCours->execute([
//                ':titre' => $event['SUMMARY'],
//                ':debut' => date('Y-m-d H:i:s', strtotime($event['DTSTART'])),
//                ':fin' => date('Y-m-d H:i:s', strtotime($event['DTEND'])),
//                ':salle' => $event['LOCATION'] ?? null,
//            ]);
//            $coursId = $this->db->getPDO()->lastInsertId();
//
//            // Insérer les groupes associés
//            foreach ($groups as $group) {
//                $stmtGroup->execute([
//                    ':cours_id' => $coursId,
//                    ':groupe' => $group,
//                ]);
//            }
//        }
//    }

    public function insertIntoDatabase($assignments): void
    {
        // Récupérer l'objet PDO une seule fois
        $pdo = $this->db->getPDO();

        // Préparation des requêtes
        $getCourseIdStmt = $pdo->prepare('SELECT ID FROM COUR WHERE NOM_COUR = ? AND DEBUT = ? AND FIN = ?');
        $insertCourseStmt = $pdo->prepare('INSERT INTO COUR (NOM_COUR, SALLE, DEBUT, FIN) VALUES (?, ?, ?, ?)');
        $getGroupIdStmt = $pdo->prepare('SELECT ID_GROUPE FROM GROUPE WHERE NOM_GROUPE = ?');
        $insertGroupCourseStmt = $pdo->prepare('INSERT INTO GROUPE_COUR (ID_COUR, ID_GROUPE, ID_SOUSGROUPE) VALUES (?, ?, ?)');

        foreach ($assignments as $assignment) {
            try {
                // Vérifier si le cours existe déjà
                $getCourseIdStmt->execute([$assignment['course_name'], $assignment['start'], $assignment['end']]);
                $courseId = $getCourseIdStmt->fetchColumn();

                // Si le cours n'existe pas, l'insérer
                if (!$courseId) {
                    $insertCourseStmt->execute([$assignment['course_name'], $assignment['salle'], $assignment['start'], $assignment['end']]);
                    $courseId = $pdo->lastInsertId();
                }

                // Récupérer l'ID du groupe principal
                $getGroupIdStmt->execute([$assignment['group']]);
                $groupId = $getGroupIdStmt->fetchColumn();

                // Récupérer l'ID du sous-groupe (si présent)
                $subGroupId = null;
                if (!empty($assignment['subgroup'])) {
                    $getGroupIdStmt->execute([$assignment['subgroup']]);
                    $subGroupId = $getGroupIdStmt->fetchColumn();
                }

                // Si le groupe principal existe, insérer dans GROUPE_COUR
                if ($groupId) {
                    $insertGroupCourseStmt->execute([$courseId, $groupId, $subGroupId]);
                }
            } catch (PDOException $e) {
                // Gérer les erreurs, par exemple, journaliser le message d'erreur
                error_log("Erreur lors de l'insertion : " . $e->getMessage());
            }
        }
    }



}