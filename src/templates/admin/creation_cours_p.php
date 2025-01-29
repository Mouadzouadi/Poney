<?php

use App\Controllers\Admin\CourseController;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom_cours = $_POST['nom_cours'];
    $niveau = $_POST['niveau'];
    $duree = $_POST['duree'];
    $heure = $_POST['heure'];
    $jour = $_POST['jour'];
    $ddd = $_POST['ddd'];
    $ddf = $_POST['ddf'];
    $nb_personnes_max = $_POST['nb_personnes_max'];
    $error = CourseController::checkProgrammedCourseCreationForm($nom_cours, $niveau, $duree, $heure, $jour, $ddd, $ddf, $nb_personnes_max);

    if (empty($error)) {
        CourseController::createProgrammedCourse($nom_cours, $niveau, $duree, $heure, $jour, $ddd, $ddf, $nb_personnes_max);
    }
}

?>

<div class="page">
    <div class="form-container">
        <a href="index.php?action=dashboard"><i class='fas fa-angle-left' style='font-size:24px'></i></a>
        <h1>Informations du cours</h1>
        <form action="#" method="POST">
            <label for="nom_cours">Nom du cours</label>
            <input type="text" id="nom_cours" name="nom_cours" maxlength="42" required>

            <div class="input-container">
                <label for="niveau">Niveau</label>
                <select id="niveau" name="niveau" required>
                    <option value="" disabled selected>Niveau</option>
                    <option value="1">Débutant</option>
                    <option value="2">Intermédiaire</option>
                    <option value="3">Avancé</option>
                </select>
            </div>

            <label for="duree">Durée (en heures)</label>
            <input type="number" id="duree" name="duree" min="1" max="2" required>

            <label for="heure">Heure de début</label>
            <input type="time" id="heure" name="heure" required>

            <label for="jour">Jour</label>
            <select id="jour" name="jour" required>
                <option value="Lundi">Lundi</option>
                <option value="Mardi">Mardi</option>
                <option value="Mercredi">Mercredi</option>
                <option value="Jeudi">Jeudi</option>
                <option value="Vendredi">Vendredi</option>
                <option value="Samedi">Samedi</option>
                <option value="Dimanche">Dimanche</option>
            </select>

            <label for="ddd">Date de début</label>
            <input type="date" id="ddd" name="ddd" required>

            <label for="ddf">Date de fin</label>
            <input type="date" id="ddf" name="ddf" required>

            <label for="nb_personnes_max">Nombre maximal de personnes</label>
            <input type="number" id="nb_personnes_max" name="nb_personnes_max" min="1" max="10" required>

            <button type="submit">Créer le cours</button>
        </form>
    </div>
</div>