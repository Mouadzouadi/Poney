<?php
use App\Controllers\Poney\PoneyDB;
  
$poneys = PoneyDB::getAllPoneys();
?>

<div class="page">
    <div class="form-container">
        <h1 style="margin-bottom:1rem">Dashboard - Moniteur</h1>
        <button onclick="window.location='index.php?action=creation_cours';">Créer un cours programmé</button>
        <button onclick="window.location='index.php?action=creation_cours_realise';">Ajouter un créneau de cours</button>
        <button onclick="window.location='index.php?action=creation_poney';">Ajouter un poney</button>
        
        <!-- Bouton pour afficher la liste déroulante -->
        <button id="show-poney-form" >Voir le planning d'un poney</button>

        <!-- Section cachée au départ -->
        <div id="poney-form" style="display: none; margin-top: 1rem;">
            <form action="index.php?action=planningPoney" method="get">
                <input type="hidden" name="action" value="planningPoney">
                <label for="poney">Choisir un poney :</label>
                <select name="poney" id="poney" required>
                    <option value="" disabled selected>-- Sélectionnez un poney --</option>
                    <?php foreach ($poneys as $poney): ?>
                        
                        <option value="<?= $poney['id'] ?>"><?= $poney['nom'] ?>, <?=$poney['age']?> ans, <?=$poney['poids_max']?> kg Maximum</option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" style="margin-top: 1rem;">Voir le planning</button>
            </form>
        </div>

        <?php

        use App\Controllers\Auth\Auth;

        if (Auth::isAdmin()) {
        ?>
        <h1 style="margin-bottom:1rem;margin-top:2rem;">Dashboard - Admin</h1>
        <button onclick="window.location='index.php?action=ajouter_moniteur';">Ajouter un moniteur</button>
        <button onclick="window.location='index.php?action=retirer_moniteur';">Retirer un moniteur</button>
        <?php
        }
        ?>
        
    </div>
    <script>
    document.getElementById('show-poney-form').addEventListener('click', function() {
        const form = document.getElementById('poney-form');
        form.style.display = 'block';
        this.style.display = 'none';
    });
    </script>
</div>