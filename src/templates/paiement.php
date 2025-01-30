<?php

use App\Controllers\Auth\Auth;
use App\Controllers\Planning\PlanningDB;
use App\Views\Flash;


$error_message = '';

?>

<div class="login">
    <div class="login-container">
        
        <h2>Effectuer un paiement - <?= htmlspecialchars($typePaiment) ?></h2>
        <?php
        if (!str_contains($typePaiment, 'Cotisation')): ?>
         <h4>Le <?= htmlspecialchars($date) ?> à <?= htmlspecialchars($heure) ?></h4>
        <?php endif; ?>
        <h4><?= htmlspecialchars($prix) ?> €</h4>
        
        <form action="#" method="post">
            <div class="input-container">
                <input name="cardholder_name" type="text" placeholder="Nom du titulaire" required>
            </div>
            <div class="input-container">
                <input name="card_number" type="text" placeholder="Numéro de carte" maxlength="16" pattern="\d{16}" title="Veuillez entrer un numéro de carte valide (16 chiffres)" required>
            </div>
            <div class="input-row">
                <div class="input-container">
                    <input name="expiry_date" type="text" placeholder="Date d'expiration (MM/AA)" maxlength="5" pattern="\d{2}/\d{2}" title="Format attendu : MM/AA" required>
                </div>
                <div class="input-container">
                    <input name="cvv" type="text" placeholder="CVV" maxlength="3" pattern="\d{3}" title="Le CVV doit comporter 3 chiffres" required>
                </div>
            </div>
            <button type="submit">Confirmer le paiement</button>
        </form>
        <a href="./index.php?action=planning" class="continue-link">Annuler</a>
    </div>
</div>

<?php if (!empty($error_message)): ?>
<div id="error-popup">
    <p><?= htmlspecialchars($error_message) ?></p>
    <button onclick="document.getElementById('error-popup').style.display = 'none';">Fermer</button>
</div>
<?php endif; ?>
