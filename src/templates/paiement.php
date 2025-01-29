<?php

use App\Controllers\Auth\Auth;
use App\Controllers\Planning\PlanningDB;
use App\Views\Flash;

$typePaiment = $_GET['type'];
$prix = $_GET['prix'];
$heure = $_GET['heure'];
$date = $_GET['date'];
$id_cours = $_GET['id_cours'];
$id_poney = $_GET['id_poney'];
$id_client = Auth::getCurrentUser()['id'];
$niveau = $_GET['niveau'];

$error_message = '';

// Si la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = Auth::getCurrentUserObj();
        if(str_contains($typePaiment, 'Cotisation')){
            $user->setCotisation();
        }else{
            checkBookingConditions($id_client, $id_cours, $user, $niveau);
            submitBooking($id_client, $id_cours, $id_poney, $date, $heure);
        }
        Flash::popup('Votre paiement a bien été effectué', 'index.php?action=planning');
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }




}


function checkBookingConditions($id_client, $id_cours, $user,$niveau)
{

    if ($user->getLevel() < $niveau) {
        throw new Exception('Votre niveau est trop bas pour vous inscrire à ce cours.');
    }

    if (PlanningDB::check_user_inscrit($id_cours, $id_client)) {
        throw new Exception('Vous êtes déjà inscrit à ce cours.');
    }


    if (PlanningDB::getPlacesRestantes($id_cours) <= 0 && !str_contains($typePaiment, 'Cotisation')) {
        throw new Exception('Il n\'y a plus de places disponibles pour ce cours.');
    }

    if (!$user->checkEstPaye()) {
        throw new Exception('Votre cotisation n\'est pas encore payée.');
    }


}

function submitBooking($id_client, $id_cours, $id_poney, $date, $heure)
{
    $dateH = $date . ' ' . $heure;
    PlanningDB::addReservation($id_client, $id_cours, $id_poney, $dateH);
}

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
