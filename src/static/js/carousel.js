let currentIndex = 0;

function movePoney(direction) {
    const track = document.querySelector('.carousel-track');
    const boxes = document.querySelectorAll('.poney-box');
    const totalPoneys = boxes.length-1;

    // Met à jour l'index
    currentIndex += direction;

    // Boucle au début ou à la fin si on dépasse
    if (currentIndex < 0) {
        currentIndex = totalPoneys - 1;
    } else if (currentIndex >= totalPoneys) {
        currentIndex = 0;
    }

    // Applique la translation pour afficher le bon poney
    const offset = -currentIndex * 100;
    track.style.transform = `translateX(${offset}%)`;
}
