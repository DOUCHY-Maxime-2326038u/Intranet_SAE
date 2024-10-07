<?php
class Database {
    private $db;

    public function __construct(){
        $this->db = new PDO("mysql:host=mysql-intraiut.alwaysdata.net;dbname=intraiut_1", "intraiut", "intraiutsae13100");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function test(){
        $stmt = $this->db->prepare("SELECT * FROM TEST");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}