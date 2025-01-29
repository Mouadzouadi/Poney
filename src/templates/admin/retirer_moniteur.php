<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_user = $_POST['id_user'];
    App::getApp()->getDB()->unsetMoniteur($id_user);
}

?>

<div class="page" >
    <div class="form-container" style ="margin-top: 10rem;">
        <a href="index.php?action=dashboard"><i class='fas fa-angle-left' style='font-size:24px'></i></a>
        <h1>Retirer un moniteur</h1>
        <form action="#" method="POST">

            <div class="input-container">
                <label for="id_user">Choisir une Personne</label>
                <select id="id_user" name="id_user" required>
                    <?php
                    $allUsers = App::getApp()->getDB()->getAllMoniteurs();
                    foreach ($allUsers as $user) {
                        echo '<option value="' . $user['id_p'] . '">' . $user['nom'] . ' ' . $user['prenom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Retirer le moniteur</button>
        </form>
    </div>
</div>