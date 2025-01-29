<?php

namespace App\Controllers\Planning;

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR');

use DateTime;

/**
 * Classe pour gérer la navigation entre les semaines.
 */
class WeekNavigator
{
    private int $week;
    private int $year;
    private bool $isClient;

    public function __construct(int $week, int $year, bool $isClient = false)
    {
        $this->week = $week;
        $this->year = $year;
        $this->isClient = $isClient;
    }

    /**
     * Récupère les informations de la semaine précédente.
     * Utilisé pour le bouton de navigation vers la semaine précédente.
     */
    public function getPreviousWeek(): array
    {
        $prevWeek = $this->week - 1;
        $prevYear = $this->year;

        if ($prevWeek < 1) {
            $prevWeek = 52;
            $prevYear--;
        }

        return ['week' => $prevWeek, 'year' => $prevYear];
    }

    /**
     * Récupère les informations de la semaine suivante.
     * Utilisé pour le bouton de navigation vers la semaine suivante.
     */
    public function getNextWeek(): array
    {
        $nextWeek = $this->week + 1;
        $nextYear = $this->year;

        if ($nextWeek > 52) {
            $nextWeek = 1;
            $nextYear++;
        }

        return ['week' => $nextWeek, 'year' => $nextYear];
    }

    /**
     * Récupère la date du lundi de la semaine actuelle.
     */
    public function getCurrentMondayDate(): string
    {
        $dateCourante = new DateTime();
        $dateCourante->setISODate($this->year, $this->week);

        return strftime('%d %B %Y', $dateCourante->getTimestamp());
    }

    /**
     * Affiche la navigation entre les semaines.
     */
    public function renderNavigation(): string
    {
        $prev = $this->getPreviousWeek();
        $next = $this->getNextWeek();
        $currentDate = $this->getCurrentMondayDate();

        ob_start();
        if($this->isClient){
            $action = 'coursReserver';
        } else {
            $action = 'planning';
        }

        ?>
        <div class="week-navigation">
            <a class="arrow-btn" href="index.php?action=<?= $action?>&week=<?= $prev['week'] ?>&year=<?= $prev['year'] ?>">◀ Semaine précédente</a>
            <span id="current-week">Semaine <?= $this->week ?>, <?= $currentDate ?></span>
            <a class="arrow-btn" href="index.php?action=<?= $action?>&week=<?= $next['week'] ?>&year=<?= $next['year'] ?>">Semaine suivante ▶</a>
        </div>
        <?php
        return ob_get_clean();
    }
}