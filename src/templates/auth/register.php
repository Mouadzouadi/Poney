<?php

use App\Controllers\Auth\AuthForm;

//if is post request
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //get post data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $level = $_POST['level'];
    $weight = $_POST['weight'];
    $error = AuthForm::checkRegisterForm($email, $password, $firstName, $lastName, $address, $phone, $level, $weight);
}

?>

<div class="page">
    <div class="form-container">
        <h2>S'inscrire maintenant</h2>
        
        <form action="#" method="post">
            <div class="input-row">
                <div class="input-container">
                    <input type="text" placeholder="Prénom" name="firstName" required>
                </div>
                <div class="input-container">
                    <input type="text" placeholder="Nom" name="lastName" required>
                </div>
            </div>
            
            <div class="input-container">
                <input type="text" placeholder="Adresse" name="address" required>
            </div>
            
            <div class="input-container">
                <input type="tel" placeholder="Numéro de téléphone" name="phone" maxlength="10" pattern="[0-9]{10}" required>
            </div>
            
            <div class="input-row">
                <div class="input-container">
                    <select name="level" required>
                        <option value="" disabled selected>Niveau</option>
                        <option value="1">Débutant</option>
                        <option value="2">Intermédiaire</option>
                        <option value="3">Avancé</option>
                    </select>
                </div>
                <div class="input-container">
                    <input type="number" placeholder="Poids" min="10" max="50" name="weight" required>
                </div>
            </div>

            <div class="input-container">
                <input type="email" placeholder="Adresse mail" name="email" required>
            </div>

            <div class="input-container">
                <input type="password" placeholder="Mot de passe" name="password" required>
            </div>

            <?php 
            if (isset($error)) {
                echo '<p class="error-message">*' . $error . '</p>';
            }
            ?>
            <button type="submit">S'inscrire</button>
        </form>

        <a href="./index.php?action=login" class="register-link">Déjà un compte ? Connectez-vous</a>
    </div>
</div>