<?php
class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $pdo;

    public  function __construct($host = 'localhost', $user = 'root', $pass = 'root', $dbname) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;

    }
    private function getPDO() {
        $pdo = new PDO("mysql:host=mysql-intraiut.alwaysdata.net;dbname=intraiut_1", "intraiut", "intraiutsae13100");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
        return $pdo;
    }
    public function test($statement) {
        $req = $this->getPDO()->prepare($statement );
        return $datas = $req->fetchAll(PDO::FETCH_ASSOC);
    }
}

