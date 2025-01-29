<?php

namespace App\Controllers\Planning\Cours;


/**
 * Classe représentant une case double contenant des cours.
 * Hérite de la classe `cases`.
 */
class CaseDouble extends ConteneurCours {
    public function getDuration() {
        return 2; 
    }

    public function __repr__() {
        $html = "<div class='case-double' style='grid-row: span 2;'>";
        foreach ($this->cours as $cours) {
            $html .= $cours->__repr__();
        }
        $html .= "</div>";
        return $html;
    }
}
?>
