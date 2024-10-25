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

logoAmu.addEventListener('mouseenter', startAnimation);
