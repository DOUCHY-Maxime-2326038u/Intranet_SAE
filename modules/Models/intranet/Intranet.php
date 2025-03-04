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

    public function logout(): void
    {
        session_destroy();
    }
    public function getUserRole(int $userId, string $userEmail): string
    {
        // Validation stricte de l'ID utilisateur
        if ($userId <= 0) {
            throw new InvalidArgumentException("L'ID utilisateur doit être un entier positif.");
        }

        // Vérifier dans chaque table autorisée
        if ($this->existsInTable('ETUDIANTS', 'ID_ETUDIANT', 'EMAIL_ET', $userId, $userEmail)) {
            return 'etudiant';
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


    public function insertIntoDatabase($assignments, $year): void
    {
        // Récupérer l'objet PDO une seule fois
        $pdo = $this->db->getPDO();

        // Préparation des requêtes
        $getCourseId = $pdo->prepare('SELECT ID_COUR FROM COUR WHERE NOM_COUR = ? AND DEBUT = ? AND FIN = ?');
        $insertCourse = $pdo->prepare('INSERT INTO COUR (NOM_COUR, SALLE, DEBUT, FIN) VALUES (?, ?, ?, ?)');
        $getGroupId = $pdo->prepare('SELECT ID_GROUPE FROM GROUPE WHERE NOM_GROUPE = ?');
        $insertGroupCourse = $pdo->prepare('INSERT INTO GROUPE_COUR (ID_COUR, ID_GROUPE, ID_SOUSGROUPE, ANNEE) VALUES (?, ?, ?, ?)');

        foreach ($assignments as $assignment) {
            try {
                // Vérifier si le cours existe déjà
                $getCourseId->execute([$assignment['course_name'], $assignment['start'], $assignment['end']]);
                $courseId = $getCourseId->fetchColumn();

                // Si le cours n'existe pas, l'insérer
                if (!$courseId) {
                    $insertCourse->execute([$assignment['course_name'], $assignment['location'], $assignment['start'], $assignment['end']]);
                    $courseId = $pdo->lastInsertId();
                }

                // Récupérer l'ID du sous-groupe (si présent)
                $subGroupId = null;
                if (!empty($assignment['subgroup'])) {
                    $getGroupId->execute([$assignment['subgroup']]);
                    $subGroupId = $getGroupId->fetchColumn();
                }

                // Gérer les groupes associés
                $groupIds = []; // Liste des IDs des groupes associés au cours
                if (!empty($assignment['groups'])) {
                    // Si des groupes sont spécifiés, récupérer leurs IDs
                    foreach ($assignment['groups'] as $groupName) {
                        $getGroupId->execute([trim($groupName)]);
                        $groupId = $getGroupId->fetchColumn();
                        if ($groupId) {
                            $groupIds[] = $groupId;
                        }
                    }
                }

                if (empty($groupIds)) {
                    // Si aucun groupe spécifique trouvé, associer aux groupes par défaut selon l'année
                    if ($year == 1) {
                        $defaultGroups = ['1', '2', '3', '4'];
                    } elseif (in_array($year, [2, 3])) {
                        $defaultGroups = ['A1', 'A2', 'B1'];
                    } else {
                        throw new Exception("Année invalide : $year");
                    }

                    foreach ($defaultGroups as $groupName) {
                        $getGroupId->execute([trim($groupName)]);
                        $groupId = $getGroupId->fetchColumn();
                        if ($groupId) {
                            $groupIds[] = $groupId;
                        }
                    }
                }

                // Associer le cours aux groupes (et sous-groupes s'ils existent) avec l'année
                foreach ($groupIds as $groupId) {
                    $insertGroupCourse->execute([$courseId, $groupId, $subGroupId ?? null, $year]);
                }
            } catch (PDOException $e) {
                // Gérer les erreurs
                error_log("Erreur lors de l'insertion : " . $e->getMessage());
                var_dump($e->getMessage());
            } catch (Exception $e) {
                // Gérer les exceptions générales
                error_log("Erreur générale : " . $e->getMessage());
                var_dump($e->getMessage());
            }
        }
    }
    public function getLastAnnonce(): ?array {
        $query = "SELECT * FROM ANNONCE ORDER BY DATE_PUBLICATION DESC LIMIT 1";
        return $this->db->getPDO()->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    public function posterAnnonce(int $idProfesseur, string $titre, string $contenu): bool {
        $query = "INSERT INTO ANNONCE (ID_PROFESSEUR, TITRE, CONTENU, DATE_PUBLICATION) 
              VALUES (:idProfesseur, :titre, :contenu, NOW())";
        $stmt = $this->db->getPDO()->prepare($query);
        return $stmt->execute([
            ':idProfesseur' => $idProfesseur,
            ':titre' => $titre,
            ':contenu' => $contenu
        ]);
    }

    public function getReservationEtudiant(int $idEtudiant): ?array {
        $query = "
        SELECT 
            r.DEBUT, 
            r.FIN, 
            s.NUM_SALLE
        FROM 
            RESERVATION r
        JOIN 
            SALLE s ON r.ID_SALLE = s.ID_SALLE
        WHERE 
            r.ID_ETUDIANT = :idEtudiant 
            AND r.FIN > NOW()
        LIMIT 1
    ";

        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute([':idEtudiant' => $idEtudiant]);
        $reserv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reserv) {
            return null;
        }

        return $reserv;
    }


    public function getEmploiDuTempsEtudiant(int $idEtudiant): array {
        $query = "SELECT c.* 
                  FROM COUR c
                  JOIN GROUPE_COUR gcp ON c.ID_COUR = gcp.ID_COUR
                  JOIN ETUDIANTS_GROUPE eg ON gcp.ID_GROUPE = eg.ID_GROUPE
                  WHERE eg.ID_ETUDIANT = :idEtudiant";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute([':idEtudiant' => $idEtudiant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getStatistiquesMatiere(int $idProfesseur): array {
        $query = "SELECT m.NOM_MATIERE, COUNT(DISTINCT eg.ID_ETUDIANT) AS NB_ETUDIANTS
              FROM MATIERE m
              JOIN COUR c ON m.ID_MATIERE = c.ID_MATIERE
              JOIN GROUPE_COUR gc ON c.ID_COUR = gc.ID_COUR
              JOIN ETUDIANTS_GROUPE eg ON gc.ID_GROUPE = eg.ID_GROUPE
              WHERE m.ID_PROFESSEUR = :idProfesseur
              GROUP BY m.NOM_MATIERE";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute([':idProfesseur' => $idProfesseur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getGlobalStats(): array {
        return [
            'nb_etudiants' => $this->db->getPDO()->query("SELECT COUNT(*) FROM ETUDIANT")->fetchColumn(),
            'nb_profs' => $this->db->getPDO()->query("SELECT COUNT(*) FROM PROFESSEUR")->fetchColumn(),
            'nb_cours' => $this->db->getPDO()->query("SELECT COUNT(*) FROM COUR")->fetchColumn(),
        ];
    }

    public function getRecentAnnonces(): array {
        $query = "SELECT * FROM ANNONCES ORDER BY DATE_PUBLICATION DESC LIMIT 5";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveReservations(): array {
        $query = "SELECT * FROM RESERVATIONS WHERE FIN > NOW()";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }




}