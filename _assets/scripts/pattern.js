const logoAmu = document.getElementById('logoAmu');
const departement = document.getElementById('dep');
let isAnimating = false;
let isDone = false;

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
