<?php

class Intranet
{
    private $db;
    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');  // Connexion unique
    }

}