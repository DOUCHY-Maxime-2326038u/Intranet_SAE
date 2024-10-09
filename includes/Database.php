<?php

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $pdo = null;

    public  function __construct($dbname, $host = 'mysql-intraiut.alwaysdata.net', $user = 'intraiut', $pass = 'intraiutsae13100') {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;

    }
    private function getPDO() {
        //voir si cette condition est exécutée
        if ($this->pdo === null) {
            echo "Création de la connexion PDO<br>";
            try {
                $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connexion établie avec succès<br>";
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        } else {
            // voir si la connexion existe déjà
            echo "Connexion PDO déjà établie<br>";
        }
        return $this->pdo;
    }
    public function query($statement) {
        $req = $this->getPDO()->query($statement);
        $datas = $req->fetchAll(PDO::FETCH_OBJ);
        return $datas;
    }
}

