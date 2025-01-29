<?php

namespace App\Controllers\Admin;

use App;
use App\Views\Flash;
use App\Controllers\Auth\Auth;

class CourseController {

    /**
     * Vérifie les données du formulaire de création de cours.
     */
    static function checkProgrammedCourseCreationForm($nom_cours, $niveau, $duree, $heure, $jour, $ddd, $ddf, $nb_personnes_max): string
    {
        $error = '';
        if (empty($nom_cours) || empty($niveau) || empty($duree) || empty($heure) || empty($jour) || empty($ddd) || empty($ddf) || empty($nb_personnes_max)) {
            $error = 'Veuillez remplir tous les champs';
        }
        return $error;
    }

    static function createProgrammedCourse($nom_cours, $niveau, $duree, $heure, $jour, $ddd, $ddf, $nb_personnes_max)
    {
        Auth::checkUserIsInstructor();

        $db = App::getApp()->getDB();
        $query = $db->prepare('INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, ddd, ddf, nb_personnes_max) VALUES (:nom_cours, :niveau, :duree, :heure, :jour, :date_debut, :date_fin, :nb_personnes_max)');
        $query->execute(array(':nom_cours' => $nom_cours, ':niveau' => $niveau, ':duree' => $duree, ':heure' => $heure, ':jour' => $jour, ':date_debut' => $ddd, ':date_fin' => $ddf, ':nb_personnes_max' => $nb_personnes_max));

        Flash::popup('Cours créé avec succès !', '/index.php?action=dashboard');
    }

    static function createRealizedCourse($id_cours, $date)
    {
        Auth::checkUserIsInstructor();

        $id_moniteur = Auth::getCurrentUser()['id'];
        $db = App::getApp()->getDB();

        //add hours of id_cours to date
        $stmt = $db->prepare("SELECT heure FROM COURS_PROGRAMME WHERE id_cp = :id_cours");
        $stmt->execute(['id_cours' => $id_cours]);
        $heure = $stmt->fetch(\PDO::FETCH_ASSOC);
        $date .= ' ' . $heure['heure'];

        try {
            $query = $db->prepare('INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR) VALUES (:id_cours, :id_moniteur, :date)');
            $query->execute(array(':id_cours' => $id_cours, ':id_moniteur' => $id_moniteur, ':date' => $date));

            Flash::popup('Cours créé avec succès !', '/index.php?action=dashboard');
        } catch (\PDOException $e) {
            Flash::popup('Erreur lors de la création du cours : ' . $e->getMessage(), '#');
            return;
        }

    }
}