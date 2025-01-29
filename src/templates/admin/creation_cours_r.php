<?php

use App\Controllers\Admin\CourseController;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_cours = explode('&', $_POST['cours'])[0];
    $date = $_POST['date_c'];
    CourseController::createRealizedCourse($id_cours, $date);
}

?>

<div class="page">
    <div class="form-container">
        <a href="index.php?action=dashboard"><i class='fas fa-angle-left' style='font-size:24px'></i></a>
        <h1>Sélectionné un cours</h1>
        <form action="#" method="POST">
<!-- 
            <div class="input-container">
                <label for="niveau">Niveau</label>
                <select id="niveau" name="niveau" required disabled>
                    <option value="" disabled selected>Niveau</option>
                    <option value="1">Débutant</option>
                    <option value="2">Intermédiaire</option>
                    <option value="3">Avancé</option>
                </select>
            </div>

            <label for="heure">Heure</label>
            <input type="time" id="heure" name="heure" required disabled>

            <label for="jour">Jour</label>
            <select id="jour" name="jour" required disabled>
                <option value="" disabled selected>Jour</option>
                <option value="Lundi">Lundi</option>
                <option value="Mardi">Mardi</option>
                <option value="Mercredi">Mercredi</option>
                <option value="Jeudi">Jeudi</option>
                <option value="Vendredi">Vendredi</option>
                <option value="Samedi">Samedi</option>
                <option value="Dimanche">Dimanche</option>
            </select>

            <label for="ddd">Date de début</label>
            <input type="date" id="ddd" name="ddd" required disabled>

            <label for="ddf">Date de fin</label>
            <input type="date" id="ddf" name="ddf" required disabled> -->

            <div class="input-container">
                <label for="cours">Choisir un cours programmé</label>
                <select id="cours" name="cours" required>
                    <?php
                        $allCourses = App::getApp()->getDB()->getAllProgrammedCourses();
                        foreach ($allCourses as $course) {
                            echo '<option value="' . $course['id_cp'] . '&' . $course['jour'] . '">' . $course['nom_cours'] . ' - ' . $course['Ddd'] . ' au ' . $course['Ddf'] . ' - Le ' . $course['jour'] . ' - Niveau ' . $course['niveau'] . ' - Durée : '. $course['heure']+0 . 'h à ' .  $course['heure'] + $course['duree'] . 'h' . '</option>';
                        }
                    ?>
                </select>
            </div>

            <label for="date_c">Date du créneau</label>
            <input type="date" id="date_c" name="date_c" required oninput="validateDay(this)">

            <button type="submit">Ajouter le créneau de cours</button>
        </form>
    </div>
</div>

<script src= "/static/js/creation_cours_realise.js"></script>