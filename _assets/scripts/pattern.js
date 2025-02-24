// Sélection des éléments nécessaires
const logoAmu = document.getElementById('logoAmu');
const departement = document.getElementById('dep');
const menuBtn = document.querySelector('.menuBtn');
const menu = document.getElementById('menu');
const body = document.querySelector('body');
let isAnimating = false;
let isDone = false;

// Fonction d'animation
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

// Gestion du menu
document.addEventListener('DOMContentLoaded', () => {
    // Toggle du menu en cliquant sur le bouton hamburger
    menuBtn.addEventListener('click', () => {
        menu.classList.toggle('active');
        body.classList.toggle('active');
        menuBtn.classList.toggle('active');
    });

    // Fermer le menu en cliquant en dehors
    document.addEventListener('click', (event) => {
        if (!menu.contains(event.target) && !menuBtn.contains(event.target)) {
            menu.classList.remove('active');
            body.classList.remove('active');
            menuBtn.classList.remove('active');
        }
    });
});

// Animation au survol du logo
logoAmu.addEventListener('mouseenter', startAnimation);
