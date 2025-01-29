<?php

namespace App\Controllers\Poney;

use App;
use PDO;



use PDOException;


if (!class_exists(\App::class)) {

    define('ROOT', $_SERVER['DOCUMENT_ROOT']);

    require ROOT . '/App/App.php';

    App::getApp();
}


class PoneyDB
{

    static function getPoneyDispo($date)
    {
        $stmt = App::getApp()->getDB()->prepare("
        SELECT id, nom, poids_max, age 
        FROM PONEY 
        WHERE id NOT IN (
            SELECT id_poney 
            FROM RESERVER 
            WHERE dateR = :date
        )
    ");

        $stmt->execute(['date' => $date]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    static function getAllPoneys()
    {
        $stmt = App::getApp()->getDB()->query("SELECT id, nom, poids_max, age FROM PONEY");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function getPoney($id_poney)
    {
        $stmt = App::getApp()->getDB()->prepare("SELECT id, nom, poids_max, age FROM PONEY WHERE id = :id_poney");
        $stmt->execute(['id_poney' => $id_poney]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>