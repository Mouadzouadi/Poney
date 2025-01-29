<?php

namespace App\Database;

use App\Views\Flash;

abstract class Database implements IDatabase
{

    public function query($statement) {
        $pdo = $this->getPDO();
        return $pdo->query($statement);
    }

    public function execute($statement) {
        $pdo = $this->getPDO();
        return $pdo->exec($statement);
    }

    public function prepare($statement, $options = []) {
        $pdo = $this->getPDO();
        return $pdo->prepare($statement, $options);
    }

    public function loadContents() {
        if (!$this->databaseExists()) {
            $this->createDatabase();
        }
    }

    public function createDatabase() {
        $this->execute(file_get_contents(ROOT . '/static/data/creaPon.sql'));
        $this->execute(file_get_contents(ROOT . '/static/data/insPon.sql'));
    }

    public function getAllProgrammedCourses() {
        $query = $this->prepare('SELECT * FROM COURS_PROGRAMME');
        $query->execute();
        return $query->fetchAll();
    }

    public function getAllUsers($excludeInstructors = false) {
        //an instructor is a user with salary
        $query = $this->prepare('SELECT * FROM PERSONNE' . ($excludeInstructors ? ' WHERE salaire IS NULL' : ''));
        $query->execute();
        return $query->fetchAll();
    }

    public function getAllMoniteurs() {
        $query = $this->prepare('SELECT * FROM PERSONNE WHERE salaire IS NOT NULL');
        $query->execute();
        return $query->fetchAll();
    }

    public function setMoniteur($id_p, $salaire) {
        try {
            $query = $this->prepare('UPDATE PERSONNE SET salaire = :salaire WHERE id_p = :id_p');
            $query->execute(array(':id_p' => $id_p, ':salaire' => $salaire));
            Flash::popup('Moniteur ajouté avec succès !', '/index.php?action=dashboard');
        }
        catch (\PDOException $e) {
            Flash::popup("Erreur lors de l'ajout du moniteur : " . $e->getMessage(), '#');
            return;
        }
    }

    public function unsetMoniteur($id_p) {
        try {
            $query = $this->prepare('UPDATE PERSONNE SET salaire = NULL WHERE id_p = :id_p');
            $query->execute(array(':id_p' => $id_p));
            Flash::popup('Moniteur retiré avec succès !', '/index.php?action=dashboard');
        }
        catch (\PDOException $e) {
            Flash::popup("Erreur lors du retrait du moniteur : " . $e->getMessage(), '#');
            return;
        }
    }
}