<?php

/**
 * Class Intranet
 *
 * Gère les interactions avec la base de données pour l'intranet,
 * notamment l'authentification, les annonces, les réservations, et les statistiques.
 */
class Intranet
{
    /**
     * @var Database Instance de la connexion à la base de données.
     */
    private $db;

    /**
     * @var array Tableau associatif des tables autorisées avec leur clé primaire.
     */
    private array $allowedTables = [
        'ETUDIANTS'   => 'ID_ETUDIANT',
        'PROFESSEURS' => 'ID_PROFESSEUR',
        'STAFF'       => 'ID_STAFF',
    ];

    /**
     * Constructeur.
     *
     * Initialise la connexion à la base de données en utilisant l'instance unique.
     */
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }

    /**
     * Termine la session en cours.
     *
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
    }

    /**
     * Retourne le rôle de l'utilisateur en vérifiant son existence dans les tables autorisées.
     *
     * @param int    $userId    L'identifiant de l'utilisateur.
     * @param string $userEmail L'adresse e-mail de l'utilisateur.
     * @return string Le rôle de l'utilisateur ('etudiant' ou 'professeur').
     * @throws InvalidArgumentException Si l'ID utilisateur n'est pas positif ou si la table/colonne n'est pas autorisée.
     * @throws RuntimeException Si l'utilisateur n'est trouvé dans aucune table.
     */
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

        // Si aucun utilisateur trouvé, renvoyer une exception
        throw new RuntimeException("Utilisateur introuvable.");
    }

    /**
     * Vérifie si un enregistrement existe dans une table donnée avec deux colonnes.
     *
     * @param string $tableName Nom de la table.
     * @param string $column1   Nom de la première colonne (clé primaire attendue).
     * @param string $column2   Nom de la deuxième colonne (ex : email).
     * @param int    $value1    Valeur à vérifier pour la première colonne.
     * @param string $value2    Valeur à vérifier pour la deuxième colonne.
     * @return bool True si l'enregistrement existe, sinon false.
     * @throws InvalidArgumentException Si la table ou la colonne n'est pas autorisée.
     */
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

    /**
     * Récupère toutes les annonces en joignant les informations du professeur.
     *
     * @return array Tableau d'objets contenant les annonces et les informations associées.
     */
    public function getAllAnnonces() {
        $queryAnnonce = "SELECT ANNONCE.TITRE AS titre, ANNONCE.CONTENU AS contenu, ANNONCE.DATE_PUBLICATION AS date_publication, 
                                PROFESSEURS.nom_prof AS professeur_nom, PROFESSEURS.PRENOM_PROF AS professeur_prenom 
                         FROM ANNONCE 
                         JOIN PROFESSEURS ON ANNONCE.ID_PROFESSEUR = PROFESSEURS.ID_PROFESSEUR";
        $stmt = $this->db->getPDO()->prepare($queryAnnonce);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Insère des affectations de cours dans la base de données.
     *
     * Pour chaque affectation, vérifie l'existence du cours, l'insère si nécessaire,
     * puis associe le cours aux groupes (et sous-groupes) en fonction de l'année.
     *
     * @param array $assignments Tableau contenant les affectations.
     * @param int   $year        L'année concernée.
     * @return void
     */
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
                $groupIds = [];
                if (!empty($assignment['groups'])) {
                    foreach ($assignment['groups'] as $groupName) {
                        $getGroupId->execute([trim($groupName)]);
                        $groupId = $getGroupId->fetchColumn();
                        if ($groupId) {
                            $groupIds[] = $groupId;
                        }
                    }
                }

                if (empty($groupIds)) {
                    // Association par défaut en fonction de l'année
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

                // Associer le cours aux groupes (et sous-groupes) avec l'année
                foreach ($groupIds as $groupId) {
                    $insertGroupCourse->execute([$courseId, $groupId, $subGroupId ?? null, $year]);
                }
            } catch (PDOException $e) {
                error_log("Erreur lors de l'insertion : " . $e->getMessage());
                var_dump($e->getMessage());
            } catch (Exception $e) {
                error_log("Erreur générale : " . $e->getMessage());
                var_dump($e->getMessage());
            }
        }
    }

    /**
     * Récupère la dernière annonce publiée.
     *
     * @return array|null Tableau associatif de la dernière annonce ou null s'il n'y en a aucune.
     */
    public function getLastAnnonce(): ?array {
        $query = "SELECT * FROM ANNONCE ORDER BY DATE_PUBLICATION DESC LIMIT 1";
        return $this->db->getPDO()->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insère une nouvelle annonce dans la base de données.
     *
     * @param int    $idProfesseur L'identifiant du professeur.
     * @param string $titre        Le titre de l'annonce.
     * @param string $contenu      Le contenu de l'annonce.
     * @return bool True en cas de succès, sinon false.
     */
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

    /**
     * Récupère la réservation en cours pour un étudiant.
     *
     * @param int $idEtudiant L'identifiant de l'étudiant.
     * @return array|null Tableau associatif de la réservation ou null s'il n'y a aucune réservation en cours.
     */
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
        return $reserv ? $reserv : null;
    }

    /**
     * Récupère l'emploi du temps d'un étudiant.
     *
     * @param int $idEtudiant L'identifiant de l'étudiant.
     * @return array Tableau associatif contenant les cours de l'étudiant.
     */
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

    /**
     * Récupère les statistiques par matière pour un professeur.
     *
     * @param int $idProfesseur L'identifiant du professeur.
     * @return array Tableau associatif regroupant le nom de la matière et le nombre d'étudiants.
     */
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

    /**
     * Récupère des statistiques globales sur les étudiants, professeurs et cours.
     *
     * @return array Tableau associatif contenant le nombre d'étudiants, de professeurs et de cours.
     */
    public function getGlobalStats(): array {
        return [
            'nb_etudiants' => $this->db->getPDO()->query("SELECT COUNT(*) FROM ETUDIANT")->fetchColumn(),
            'nb_profs'     => $this->db->getPDO()->query("SELECT COUNT(*) FROM PROFESSEUR")->fetchColumn(),
            'nb_cours'     => $this->db->getPDO()->query("SELECT COUNT(*) FROM COUR")->fetchColumn(),
        ];
    }

    /**
     * Récupère les annonces récentes.
     *
     * @return array Tableau associatif des annonces récentes.
     */
    public function getRecentAnnonces(): array {
        $query = "SELECT * FROM ANNONCES ORDER BY DATE_PUBLICATION DESC LIMIT 5";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les réservations actives.
     *
     * @return array Tableau associatif des réservations actives.
     */
    public function getActiveReservations(): array {
        $query = "SELECT * FROM RESERVATIONS WHERE FIN > NOW()";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}