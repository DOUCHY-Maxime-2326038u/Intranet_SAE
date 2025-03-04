<?php

/**
 * Class Database
 *
 * Gestionnaire de connexion à la base de données utilisant le pattern Singleton.
 */
class Database {
    /**
     * @var string Adresse de l'hôte de la base de données.
     */
    private $host;

    /**
     * @var string Nom d'utilisateur de la base de données.
     */
    private $user;

    /**
     * @var string Mot de passe pour la base de données.
     */
    private $pass;

    /**
     * @var string Nom de la base de données.
     */
    private $dbname;

    /**
     * @var Database|null Instance unique de la classe (singleton).
     */
    private static $instance = null;

    /**
     * @var PDO|null Instance PDO de la connexion.
     */
    private $pdo = null;

    /**
     * Constructeur privé de la classe Database.
     *
     * Initialise la connexion PDO avec les paramètres fournis.
     *
     * @param string $dbname Nom de la base de données.
     * @param string $host Adresse de l'hôte de la base de données.
     * @param string $user Nom d'utilisateur pour la base de données.
     * @param string $pass Mot de passe pour la base de données.
     * @throws PDOException En cas d'échec de connexion.
     */
    private function __construct($dbname, $host = 'mysql-intraiut.alwaysdata.net', $user = 'intraiut', $pass = 'intraiutsae13100') {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;

        // Création de la connexion PDO
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance unique de Database (singleton).
     *
     * @param string $dbname Nom de la base de données.
     * @return Database Instance unique de la classe.
     */
    public static function getInstance($dbname) {
        if (self::$instance === null) {
            self::$instance = new Database($dbname);
        }
        return self::$instance;
    }

    /**
     * Retourne l'instance PDO de la connexion.
     *
     * @return PDO Instance PDO de la base de données.
     */
    public function getPDO() {
        return $this->pdo;
    }

    /**
     * Exécute une requête SQL et retourne les résultats sous forme d'objets.
     *
     * @param string $statement Requête SQL à exécuter.
     * @return array Résultat de la requête sous forme d'un tableau d'objets.
     */
    public function query($statement) {
        $req = $this->getPDO()->query($statement);
        $datas = $req->fetchAll(PDO::FETCH_OBJ);
        return $datas;
    }
}
