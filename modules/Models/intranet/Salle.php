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

}