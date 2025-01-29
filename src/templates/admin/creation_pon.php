<?php

use App\Controllers\Admin\PoneyController;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nom_cours = $_POST['nom_cours'];
    $age = $_POST['age'];
    $maxweight = $_POST['maxweight'];

    $error = PoneyController::checkPoneyCreationForm($nom_cours, $age, $maxweight);

    if (empty($error)) {
        PoneyController::createPoney($nom_cours, $age, $maxweight);
    }
}

?>

<div class="page">
    <div class="form-container">
        <a href="index.php?action=dashboard"><i class='fas fa-angle-left' style='font-size:24px'></i></a>
        <h1>Informations du poney</h1>
        <form action="#" method="POST">
            <label for="nom_cours">Nom du poney</label>
            <input type="text" id="nom_cours" name="nom_cours" maxlength="42" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" min="1" max="25" required>

            <label for="maxweight">Poids maximal</label>
            <input type="number" id="maxweight" name="maxweight" min="10" max="50" required>

            <button type="submit">Créer le poney</button>
        </form>
    </div>
</div>