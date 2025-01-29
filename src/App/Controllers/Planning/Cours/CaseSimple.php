<?php

namespace App\Controllers\Planning\Cours;

/**
 * Classe représentant une case simple contenant des cours.
 * Hérite de la classe `ConteneurCours'.
 */
class CaseSimple extends ConteneurCours {
    public function getDuration() {
        return 1;
    }

    public function __repr__() {
        $html = "<div class='case-simple'>";
        foreach ($this->cours as $cours) {
            $html .= $cours->__repr__();
        }
        $html .= "</div>";
        return $html;
    }
}
?>
