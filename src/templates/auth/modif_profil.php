<?php

use App\Controllers\Auth\AuthForm;
use App\Controllers\Auth\Auth;

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
    $error = AuthForm::checkUpdateForm($email, $password, $firstName, $lastName, $address, $phone, $level, $weight);
}

$user=Auth::getCurrentUserObj();

?>

<div class="page">
    <div class="form-container">
        <h2>Modifier mon profil</h2>
        
        <form action="#" method="post">
            <div class="input-row">
                <div class="input-container">
                    <input type="text" placeholder="Prénom" name="firstName" value="<?=$user->firstName ?>" required>
                </div>
                <div class="input-container">
                    <input type="text" placeholder="Nom" name="lastName" value="<?=$user->lastName?>" required>
                </div>
            </div>
            
            <div class="input-container">
                <input type="text" placeholder="Adresse" name="address" value="<?=$user->address?>" required>
            </div>
            
            <div class="input-container">
                <input type="tel" placeholder="Numéro de téléphone" name="phone" maxlength="10" pattern="[0-9]{10}" value="<?=$user->phone?>" required>
            </div>
            <div class="input-row">
                <div class="input-container">
                    <select name="level" required>
                        
                        <option value="" disabled <?= !isset($user->level) ? 'selected' : '' ?>>Niveau</option>
                        <option value="1" <?= (isset($user->level) && $user->level == '1') ? 'selected' : '' ?>>Débutant</option>
                        <option value="2" <?= (isset($user->level) && $user->level == '2') ? 'selected' : '' ?>>Intermédiaire</option>
                        <option value="3" <?= (isset($user->level) && $user->level == '3') ? 'selected' : '' ?>>Avancé</option>
                    </select>
                </div>
                <div class="input-container">
                    <input type="number" placeholder="Poids" min="10" max="50" name="weight" value="<?=$user->weight?>" required>
                </div>
            </div>

            <div class="input-container">
                <input type="email" placeholder="Adresse mail" name="email" value="<?=$user->email?>" required>
            </div>

            <div class="input-container">
                <input type="password" placeholder="Mot de passe" name="password">
            </div>

            <?php 
            if (isset($error)) {
                echo '<p class="error-message">*' . $error . '</p>';
            }
            ?>
            <button type="submit">Modifier</button>
        </form>

        <a href="./index.php?action=planning" class="register-link" style="color : red;">Retour</a>
    </div>
</div>