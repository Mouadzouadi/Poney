<?php

namespace App\Controllers\Planning;

use App;
use PDO;



use PDOException;


if (!class_exists(\App::class)) {

    define('ROOT', $_SERVER['DOCUMENT_ROOT']);

    require ROOT . '/App/App.php';

    App::getApp();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données envoyées en JSON
    $data = json_decode(file_get_contents('php://input'), true);
    // Vérification de l'action demandée
    if (isset($data['action']) && $data['action'] === 'getPoneyDispo') {
        // Traitement pour récupérer les poneys disponibles
        $date = $data['date'];
        $heure = $data['heure'];
        $dateH = $date . ' ' . $heure;
        $poneys = PlanningDB::getPoneyDispo($dateH);

        echo json_encode([
            'success' => true,
            'poneys' => $poneys
        ]);
    }
    elseif (isset($data['id_user'], $data['id_cours'], $data['id_poney'], $data['date'])) {
        // Traitement pour la réservation
        $id_client = $data['id_user'];
        $id_cours = $data['id_cours'];
        $id_poney = $data['id_poney'];
        $date = $data['date'];
        if (PlanningDB::check_user_inscrit($id_cours, $id_client)) {
            echo json_encode(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours']);
        } else {
            $places_restantes = PlanningDB::getPlacesRestantes($id_cours);

            $niveau_client = PlanningDB::getNiveauClient($id_client);
            $niveau_cours = PlanningDB::getNiveauCours($id_cours);

            if ($niveau_client < $niveau_cours) {
                echo json_encode(['success' => false, 'message' => 'Votre niveau est insuffisant pour ce cours']);
            } elseif ($places_restantes > 0) {
                try {
                    PlanningDB::addReservation($id_client, $id_cours, $id_poney, $date);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Réservation effectuée',

                    ]);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de la réservation ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Plus de places disponibles']);
            }
        }
    }
}

class PlanningDB
{



    /**
     * Récupère le planning hebdomadaire des cours, incluant les détails
     * des cours réalisés et le nombre de places restantes.
     *
     * @return array Planning hebdomadaire formaté.
     */
    static function getWeeklySchedule()
    {
        $stmt = App::getApp()->getDB()->query("
        SELECT
            cp.id_cp,
            cp.nom_cours,
            cp.niveau,
            cp.duree,
            cp.heure,
            cp.jour,
            cp.Ddd,
            cp.Ddf,
            cp.nb_personnes_max,
            p.nom AS nom_moniteur,
            p.prenom AS prenom_moniteur,
            cr.dateR
        FROM COURS_REALISE cr
        LEFT JOIN COURS_PROGRAMME cp ON cp.id_cp = cr.id_cours
        LEFT JOIN PERSONNE p ON cr.id_moniteur = p.id_p
        LEFT JOIN RESERVER r ON cp.id_cp = r.id_cours
        GROUP BY
            cp.id_cp, cp.jour, cp.heure, cp.nom_cours, cp.niveau, cp.duree, cp.nb_personnes_max, p.nom, p.prenom, cr.dateR
        ORDER BY
            CASE cp.jour
                WHEN 'Lundi' THEN 1
                WHEN 'Mardi' THEN 2
                WHEN 'Mercredi' THEN 3
                WHEN 'Jeudi' THEN 4
                WHEN 'Vendredi' THEN 5
                WHEN 'Samedi' THEN 6
                WHEN 'Dimanche' THEN 7
                ELSE 8
            END,
            cp.heure
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static function getWeeklyScheduleForClient($id_client){
        $stmt = App::getApp()->getDB()->prepare("
        SELECT
            cp.id_cp,
            cp.nom_cours,
            cp.niveau,
            cp.duree,
            cp.heure,
            cp.jour,
            cp.Ddd,
            cp.Ddf,
            cp.nb_personnes_max,
            p.nom AS nom_moniteur,
            p.prenom AS prenom_moniteur,
            cr.dateR
        FROM COURS_REALISE cr
        LEFT JOIN COURS_PROGRAMME cp ON cp.id_cp = cr.id_cours
        LEFT JOIN PERSONNE p ON cr.id_moniteur = p.id_p
        LEFT JOIN RESERVER r ON cp.id_cp = r.id_cours
        WHERE r.id_client = :id_client
        GROUP BY
            cp.id_cp, cp.jour, cp.heure, cp.nom_cours, cp.niveau, cp.duree, cp.nb_personnes_max, p.nom, p.prenom, cr.dateR
        ORDER BY
            CASE cp.jour
                WHEN 'Lundi' THEN 1
                WHEN 'Mardi' THEN 2
                WHEN 'Mercredi' THEN 3
                WHEN 'Jeudi' THEN 4
                WHEN 'Vendredi' THEN 5
                WHEN 'Samedi' THEN 6
                WHEN 'Dimanche' THEN 7
                ELSE 8
            END,
            cp.heure
    ");

        $stmt->execute(['id_client' => $id_client]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static function getWeeklyScheduleForInstructor($id_moniteur){
        $stmt = App::getApp()->getDB()->prepare("
        SELECT
            cp.id_cp,
            cp.nom_cours,
            cp.niveau,
            cp.duree,
            cp.heure,
            cp.jour,
            cp.Ddd,
            cp.Ddf,
            cp.nb_personnes_max,
            p.nom AS nom_moniteur,
            p.prenom AS prenom_moniteur,
            cr.dateR
        FROM COURS_REALISE cr
        LEFT JOIN COURS_PROGRAMME cp ON cp.id_cp = cr.id_cours
        LEFT JOIN PERSONNE p ON cr.id_moniteur = p.id_p
        LEFT JOIN RESERVER r ON cp.id_cp = r.id_cours
        WHERE cr.id_moniteur= :id_moniteur
        GROUP BY
            cp.id_cp, cp.jour, cp.heure, cp.nom_cours, cp.niveau, cp.duree, cp.nb_personnes_max, p.nom, p.prenom, cr.dateR
        ORDER BY
            CASE cp.jour
                WHEN 'Lundi' THEN 1
                WHEN 'Mardi' THEN 2
                WHEN 'Mercredi' THEN 3
                WHEN 'Jeudi' THEN 4
                WHEN 'Vendredi' THEN 5
                WHEN 'Samedi' THEN 6
                WHEN 'Dimanche' THEN 7
                ELSE 8
            END,
            cp.heure
    ");

        $stmt->execute(['id_moniteur' => $id_moniteur]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static function getWeeklyScheduleForPoney($id_poney){
        $stmt = App::getApp()->getDB()->prepare("
        SELECT
            cp.id_cp,
            cp.nom_cours,
            cp.niveau,
            cp.duree,
            cp.heure,
            cp.jour,
            cp.Ddd,
            cp.Ddf,
            cp.nb_personnes_max,
            p.nom AS nom_moniteur,
            p.prenom AS prenom_moniteur,
            cr.dateR
        FROM COURS_REALISE cr
        LEFT JOIN COURS_PROGRAMME cp ON cp.id_cp = cr.id_cours
        LEFT JOIN PERSONNE p ON cr.id_moniteur = p.id_p
        LEFT JOIN RESERVER r ON cp.id_cp = r.id_cours
        WHERE r.id_poney= :id_poney
        GROUP BY
            cp.id_cp, cp.jour, cp.heure, cp.nom_cours, cp.niveau, cp.duree, cp.nb_personnes_max, p.nom, p.prenom, cr.dateR
        ORDER BY
            CASE cp.jour
                WHEN 'Lundi' THEN 1
                WHEN 'Mardi' THEN 2
                WHEN 'Mercredi' THEN 3
                WHEN 'Jeudi' THEN 4
                WHEN 'Vendredi' THEN 5
                WHEN 'Samedi' THEN 6
                WHEN 'Dimanche' THEN 7
                ELSE 8
            END,
            cp.heure
    ");

        $stmt->execute(['id_poney' => $id_poney]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    static function getNiveauCours($id_cours)
    {
        $stmt = App::getApp()->getDB()->prepare("SELECT niveau FROM COURS_PROGRAMME WHERE id_cp = :id_cours");
        $stmt->execute(['id_cours' => $id_cours]);
        $niveau = $stmt->fetch(PDO::FETCH_ASSOC);
        return $niveau['niveau'];
    }

    static function getNiveauClient($id_client)
    {
        $stmt = App::getApp()->getDB()->prepare("SELECT niveau FROM PERSONNE WHERE id_p = :id_client");
        $stmt->execute(['id_client' => $id_client]);
        $niveau = $stmt->fetch(PDO::FETCH_ASSOC);
        return $niveau['niveau'];
    }


    static function addReservation($id_client, $id_cours, $id_poney, $date)
    {
        if (self::getPlacesRestantes($id_cours) > 0) {
            $stmt = App::getApp()->getDB()->prepare("INSERT INTO RESERVER (id_client, id_cours, id_poney, dateR) VALUES (:id_client, :id_cours, :id_poney, :date)");
            $stmt->execute([
                'id_client' => $id_client,
                'id_cours' => $id_cours,
                'id_poney' => $id_poney,
                'date' => $date
            ]);
        }
    }

    static function getPlacesRestantes($id_cours)
    {
        $stmt = App::getApp()->getDB()->prepare("
        SELECT 
            cp.nb_personnes_max - IFNULL(COUNT(r.id_client), 0) AS places_restantes
        FROM 
            COURS_PROGRAMME cp
        LEFT JOIN 
            COURS_REALISE cr ON cp.id_cp = cr.id_cours
        LEFT JOIN 
            RESERVER r ON cr.id_cours = r.id_cours
        WHERE 
            cp.id_cp = :id_cours
        GROUP BY 
            cp.nb_personnes_max
    ");

        $stmt->execute(['id_cours' => $id_cours]);

        $places_restantes = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification de la valeur renvoyée
        if ($places_restantes && isset($places_restantes['places_restantes'])) {
            return (int)$places_restantes['places_restantes'];
        }

        // Retourne 0 si aucune donnée n'est trouvée
        return 0;
    }

    static function check_user_inscrit($id_cours, $id_client)
    {
        try {
            $stmt = App::getApp()->getDB()->prepare("SELECT 1 FROM RESERVER WHERE id_cours = :id_cours AND id_client = :id_client");
            $stmt->execute(['id_cours' => $id_cours, 'id_client' => $id_client]);
    
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    static function getAllPoneys()
    {
        $stmt = App::getApp()->getDB()->query("SELECT id, nom, poids_max, age FROM PONEY");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function getParticipants($id_cours)
    {
        $stmt = App::getApp()->getDB()->prepare("
        SELECT 
            p.nom, p.prenom
        FROM 
            PERSONNE p
        LEFT JOIN 
            RESERVER r ON p.id_p = r.id_client
        WHERE 
            r.id_cours = :id_cours
    ");
    
        $stmt->execute(['id_cours' => $id_cours]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>