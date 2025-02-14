const logoAmu = document.getElementById('logoAmu');
const departement = document.getElementById('dep');
const menuBtn = document.querySelector('.menuBtn');
const menu = document.getElementById('menu');
const body = document.querySelector('body');
let isAnimating = false;
let isDone = false;


menuBtn.addEventListener('click', () => {
    menu.classList.toggle('active');
    body.classList.toggle('active');
    menuBtn.classList.toggle('active');
});
const toggleAnimation = (addClass, removeClass) => {
    logoAmu.classList.add(addClass);
    logoAmu.classList.remove(removeClass);
};

const startAnimation = () => {
    if (isAnimating) return;

    isAnimating = true;
    toggleAnimation(isDone ? 'animate-done' : 'animate', isDone ? 'animate' : 'animate-done');

    setTimeout(() => {
        isAnimating = false;
    }, 3000);

    isDone = !isDone;
};
document.addEventListener('DOMContentLoaded', () => {
    // Récupération des éléments du DOM
    const menu = document.getElementById('menu'); // Sélectionne le menu
    const menuBtn = document.querySelector('.menuBtn'); // Bouton pour ouvrir le menu
    const closeBtn = document.getElementById('closeMenu'); // Bouton pour fermer le menu

    // Vérifie que les éléments existent avant d'ajouter des écouteurs
    if (menu && menuBtn && closeBtn) {
        // Ouvrir le menu
        menuBtn.addEventListener('click', () => {
            menu.classList.add('active'); // Ajoute la classe "active" pour rendre le menu visible
        });

        // Fermer le menu
        closeBtn.addEventListener('click', () => {
            menu.classList.remove('active'); // Supprime la classe "active" pour cacher le menu
        });
    } else {
        console.error("Menu ou boutons non trouvés dans le DOM.");
    }
});

logoAmu.addEventListener('mouseenter', startAnimation);
