<?php

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private static $instance = null; // Singleton instance
    private $pdo = null;

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct($dbname, $host = 'mysql-intraiut.alwaysdata.net', $user = 'intraiut', $pass = 'intraiutsae13100') {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;

        // Création de la connexion PDO
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connexion établie avec succès<br>";
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    // Méthode pour obtenir l'instance unique (singleton)
    public static function getInstance($dbname) {
        if (self::$instance === null) {
            self::$instance = new Database($dbname);
        }
        return self::$instance;
    }

    // Retourner l'instance PDO
    public function getPDO() {
        return $this->pdo;
    }

    public function query($statement) {
        $req = $this->getPDO()->query($statement);
        $datas = $req->fetchAll(PDO::FETCH_OBJ);
        return $datas;
    }
}
