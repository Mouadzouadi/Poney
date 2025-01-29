<?php 
use App\Controllers\Poney\PoneyDB;
use App\Controllers\Poney\Poney;
$poneys = PoneyDB::getAllPoneys();



?>


<!-- Section avec l'image de fond, h1 et paragraphe -->
<section class="hero">
    <div class="hero-content">
        <h1 class="presentation-title">BIENVENUE CHEZ LES PETITS CAVALIERS DE L’IUT !</h1>
        <p>Découvrez la joie de l’équitation, une aventure d’évasion unique et de complicité avec nos poneys, idéale pour petits et grands, le tout dans un cadre convivial, chaleureux et en pleine nature !</p>
        <a href="index.php?action=register" class="btn">Rejoignez-nous !</a>
    </div>
</section>

<!-- Première section avec texte à gauche et image à droite -->
<section class="section-with-image-right">
    <div class="text1">
        <h1>LA VIE DANS NOTRE CLUB</h1>
        <p class="p1">Plongez dans un univers équestre unique où passion, nature et complicité se rencontrent. Situé en plein cœur de la campagne, le Club de Poney Évasion est l’endroit idéal pour tous les amoureux des chevaux, petits et grands.</p>
        <p class="p1">Que vous soyez débutant ou cavalier confirmé, notre club vous propose une large gamme d'activités adaptées à tous les niveaux.</p>
        <p class="p1">Nos moniteurs passionnés et diplômés vous accompagnent à chaque étape, que ce soit pour des balades en pleine nature, des cours d’apprentissage ou des stages intensifs pour les plus ambitieux.</p>
    </div>
    <div class="image-container">
        <img class="image2" src="./static/images/image2.png" alt="image2">
    </div>
    

</section>
<div class="button-image-right">
    <a href="#" class="btn">En savoir plus</a>

</div>
<!-- Section du carrousel des poneys -->
<div class="section-poneys">
    <h1 class="titre-poneys">Présentation de nos poneys</h1>
    <div class="poney-carousel">
        <button class="carousel-btn prev-btn" onclick="movePoney(-1)">&#10094;</button>
        <div class="carousel-track-container">
            <div class="carousel-track">
            <?php foreach ($poneys as $poney): 
                    $poneyInfo = Poney::getPoneyInfo($poney);
                ?>
                    <div class="poney-box"> 
                        <!-- Image dynamique -->
                        <img src="<?= htmlspecialchars($poneyInfo['image']) ?>" alt="Image de <?= htmlspecialchars($poney['nom']) ?>">
                        
                        <!-- Description dynamique -->
                        <div class="poney-description">
                            <h2>
                                <?= htmlspecialchars($poney['nom']) ?>
                            </h2>
                            <p>
                                <?= htmlspecialchars($poneyInfo['description']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
                <!-- Exemple de poney -->
                <div class="poney-box">
                    
            </div>
        </div>
        <button class="carousel-btn next-btn" onclick="movePoney(1)">&#10095;</button>
        <script src="./static/js/carousel.js"></script>
    </div>
</div>