<?php

class Question
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance('intraiut_1');
    }

    public function getQuestionPubliee(){
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM QUESTION WHERE EST_PUBLIEE = 1 ORDER BY DATE_QUESTION DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterQuestion($question){
        $stmt = $this->db->getPDO()->prepare("INSERT INTO QUESTION (CONTENU) VALUES (:question)");
        return $stmt->execute([':question'=>$question]);
    }

    public function repondreQuestion($id, $reponse) {
        $stmt = $this->db->getPDO()->prepare("UPDATE QUESTION SET REPONSE = :reponse WHERE id = :id");
        return $stmt->execute([
            ':reponse' => $reponse,
            ':id' => $id
        ]);
    }

    public function publierQuestion($id) {
        $stmt = $this->db->getPDO()->prepare("UPDATE QUESTION SET EST_PUBLIEE = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getQuestions() {
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM QUESTION ORDER BY DATE_QUESTION DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getQuestionsNonPubliees(){
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM QUESTION WHERE EST_PUBLIEE = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}