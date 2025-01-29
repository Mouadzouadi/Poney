<?php
namespace App\Controllers\Poney;

use App\Models\Poney\PoneyDB;

class Poney
{
  
    static function getPoneyInfo($poney){
        $i = rand(1, 3); 
        switch ($i) {
            case 1:
                return [
                    "description" => "Petit, mais énergique, " . htmlspecialchars($poney['nom']) . " est un poney joueur qui adore explorer de nouveaux chemins. Sa robe marron chocolat et son caractère espiègle en font le favori des plus jeunes. Pouvant porter jusqu'à " . htmlspecialchars($poney['poids_max']) . " kg du haut de ses " . htmlspecialchars($poney['age']) . " ans.",
                    "image" => "./static/images/poney1.png"
                ];
            case 2:
                return [
                    "description" => "Avec son allure majestueuse, " . htmlspecialchars($poney['nom']) . " inspire confiance et fierté chez ses cavaliers du haut de ses ".htmlspecialchars($poney['age'])." ans. Son pelage noir et sa crinière soyeuse en font un poney très apprécié des cavaliers confirmés. Il peut porter jusqu'à " . htmlspecialchars($poney['poids_max']) . " kg.",
                    "image" => "./static/images/poney2.png"
                ];
            case 3:
                return [
                    "description" => "Doué d'un tempérament calme, " . htmlspecialchars($poney['nom']) . " est parfait pour des balades tranquilles. Son pelage blanc et sa crinière soyeuse en font un poney très apprécié des cavaliers débutants. Il peut porter jusqu'à " . htmlspecialchars($poney['poids_max']) . " kg et a " . htmlspecialchars($poney['age']) . " ans.",
                    "image" => "./static/images/poney3.png"
                ];
            default:
                return [
                    "description" => "Ce poney est unique et spécial.",
                    "image" => "./static/images/poney1.png"
                ];
        }
    }
}   
?>