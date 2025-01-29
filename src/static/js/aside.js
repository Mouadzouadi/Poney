// Fonction pour récupérer la couleur de fond d'un élément
function getBackgroundColor(element) {
    return window.getComputedStyle(element).backgroundColor;
}

// Fonction principale pour mettre à jour l'icône du menu
function updateMenuIcon() {
    const menuIcon = document.querySelector('.toggle-aside');

    if (!menuIcon) {
        console.error("L'élément '.toggle-aside' est introuvable !");
        return;
    }

    const rect = menuIcon.getBoundingClientRect();
    const x = rect.left + rect.width / 2; // Position X au centre de l'icône
    const y = rect.top + rect.height / 2; // Position Y au centre de l'icône
    const elementsUnder = document.elementsFromPoint(x, y);

    for (let element of elementsUnder) {
        if (element === menuIcon) continue;

        const bgColor = getBackgroundColor(element);

        if (bgColor && bgColor !== "rgba(0, 0, 0, 0)" && bgColor !== "transparent") {

            const rgb = bgColor.match(/\d+/g);
            if (rgb) {
                const r = parseInt(rgb[0], 10);
                const g = parseInt(rgb[1], 10);
                const b = parseInt(rgb[2], 10);

                const brightness = (0.299 * r) + (0.587 * g) + (0.114 * b);
                menuIcon.src = brightness < 100
                    ? "/static/images/menu.png"
                    : "/static/images/menu_inverted.png";
            }
            break;
        }
    }
}

function adjustTogglePosition() {
    const toggleAside = document.querySelector('.toggle-aside');
    const header = document.querySelector('header');

    if (!toggleAside || !header) {
        console.error("Éléments '.toggle-aside' ou 'header' introuvables !");
        return;
    }

    // Vérifie si le toggle est initialement dans le header
    const headerRect = header.getBoundingClientRect();
    const toggleRect = toggleAside.getBoundingClientRect();
    const toggleInHeader = toggleRect.top <= headerRect.bottom;

    if (toggleInHeader) {
        // Si le toggle est dans le header, positionne-le sous le header
        const headerHeight = header.offsetHeight;
        toggleAside.style.position = 'fixed';
        toggleAside.style.top = `${headerHeight}px`; // Sous le header
        toggleAside.style.left = '10px'; // Marges ajustées
    } else {
        // Si le toggle n'est pas dans le header, place-le en haut à gauche
        toggleAside.style.position = 'fixed';
        toggleAside.style.top = '0px'; // Tout en haut
        toggleAside.style.left = '10px'; // Tout à gauche
    }
}



// Gestion du toggle pour afficher/masquer l'aside
document.querySelector('.toggle-aside').addEventListener('click', function (e) {
    e.stopPropagation();

    const aside = document.querySelector('.client-info');
    aside.classList.toggle('active');
    updateMenuIcon();
});

// Fermer l'aside au clic à l'extérieur
document.addEventListener('click', function (e) {
    const aside = document.querySelector('.client-info');
    const toggleBtn = document.querySelector('.toggle-aside');

    if (aside && aside.classList.contains('active') &&
        !aside.contains(e.target) &&
        !toggleBtn.contains(e.target)) {
        aside.classList.remove('active');
    }
});

// Ajouter les écouteurs d'événements pour appeler les fonctions dans différents contextes
document.addEventListener("DOMContentLoaded", () => {
    updateMenuIcon();
    adjustTogglePosition();

    // Événements liés au scroll et au redimensionnement
    window.addEventListener("scroll", () => {
        updateMenuIcon();
        adjustTogglePosition();
    });

    window.addEventListener("resize", () => {
        updateMenuIcon();
        adjustTogglePosition();
    });

    // Charger l'icône une fois toutes les images chargées
    window.addEventListener("load", () => {
        updateMenuIcon();
        adjustTogglePosition();
    });

    window.addEventListener("click", () => {    
        updateMenuIcon();
        adjustTogglePosition();
    });
    window.addEventListener("mousemove", () => {
        updateMenuIcon();
        adjustTogglePosition();
    });
    
});
