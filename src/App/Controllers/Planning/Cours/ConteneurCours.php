<?php

namespace App\Controllers\Planning\Cours;

/**
 * Classe abstraite représentant une case contenant des cours.
 */
abstract class ConteneurCours {
    protected $cours = [];

    public function addCours($cours) {
        $this->cours[] = $cours;
    }

    public function getCours() {
        return $this->cours;
    }

    public function getNbCours() {
        return count($this->cours);
    }

    public function __repr__() {
        $html = "<div class='case'>";
        foreach ($this->cours as $cours) {
            $html .= $cours->__repr__();
        }
        $html .= "</div>";
        return $html;
    }

    abstract public function getDuration();
}
?>
