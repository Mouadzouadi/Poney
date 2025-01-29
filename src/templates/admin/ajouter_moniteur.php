<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_user = $_POST['id_user'];
    $salary = $_POST['salary'];
    App::getApp()->getDB()->setMoniteur($id_user, $salary);
}

?>

<div class="page">
    <div class="form-container">
        <a href="index.php?action=dashboard"><i class='fas fa-angle-left' style='font-size:24px'></i></a>
        <h1>Ajouter un moniteur</h1>
        <form action="#" method="POST">

            <div class="input-container">
                <label for="id_user">Choisir une Personne</label>
                <select id="id_user" name="id_user" required>
                    <?php
                    $allUsers = App::getApp()->getDB()->getAllUsers(true);
                    foreach ($allUsers as $user) {
                        echo '<option value="' . $user['id_p'] . '">' . $user['nom'] . ' ' . $user['prenom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <label for="salary">Salaire</label>
            <input type="number" id="salary" name="salary" required>

            <button type="submit">Ajouter le moniteur</button>
        </form>
    </div>
</div>