<?php

namespace App\Controllers\Admin;

use App;
use App\Views\Flash;

class PoneyController
{

    static function checkPoneyCreationForm($nom, $age, $poids_max): string
    {
        $error = '';
        if (empty($nom) || empty($age) || empty($poids_max)) {
            $error = 'Veuillez remplir tous les champs';
        }
        return $error;
    }

    static function createPoney($nom, $age, $poids_max)
    {
        $db = App::getApp()->getDB();
        $query = $db->prepare('INSERT INTO PONEY (nom, age, poids_max) VALUES (:nom, :age, :poids_max)');
        $query->execute(array(':nom' => $nom, ':age' => $age, ':poids_max' => $poids_max));
        Flash::popup('Poney créé avec succès !', '/index.php?action=dashboard');
    }
}