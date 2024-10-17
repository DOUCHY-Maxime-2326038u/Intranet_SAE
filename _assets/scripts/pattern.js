const logoAmu = document.getElementById('logoAmu');
let isAnimating = false;
let isDone = false
const startAnimation = () => {
    if (isAnimating) return;
    if (isDone){
        isAnimating = true;
        logoAmu.classList.replace('animate', 'animate-done');
        setTimeout(() => {
        
            isAnimating = false;
        }, 1500);
        
    }
    else{
        isAnimating = true;
        logoAmu.classList.remove('animate-done');
        logoAmu.classList.add('animate');
        setTimeout(() => {
        
            isAnimating = false;
        }, 1500);
    }
    isDone = !isDone;
    
};

const resetAnimation = () => {
    if (!isAnimating) logoAmu.classList.remove('animate-done');
};

logoAmu.addEventListener('mouseenter', startAnimation);
logoAmu.addEventListener('mouseleave', resetAnimation);
