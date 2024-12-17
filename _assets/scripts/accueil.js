const greenBlocks = document.querySelectorAll('.greenBlock');
const redBlocks = document.querySelectorAll('.purpleBlock');

const animateIn = () => {
    greenBlocks.forEach((block, index) => {
        setTimeout(() => {
            block.style.transform = 'translateX(0)';
        }, index * 200);
    });

    redBlocks.forEach((block, index) => {
        setTimeout(() => {
            block.style.transform = 'translateX(0)';
        }, index * 200);
    });
    setTimeout(() => {
        document.getElementById('playBtn').style.opacity = '1';
    }, 500)

};

const animateOut = () => {
    greenBlocks.forEach((block, index) => {
        setTimeout(() => {
            block.style.transform = 'translateX(-100%)';
        }, index * 200);
    });

    redBlocks.forEach((block, index) => {
        setTimeout(() => {
            block.style.transform = 'translateX(100%)';
        }, index * 200);
    });
    setTimeout(() => {
        document.getElementById('playBtn').style.opacity = '0';
    }, 500)
};

window.onload = animateIn;

document.getElementById('playBtn').addEventListener('click', function() {
    document.getElementById('playBtn').src = '/_assets/img/playingBtn.svg';
    animateOut();

    setTimeout(function() {
        document.getElementById('presentation').style.display = 'none';
        document.getElementById('videoPres').style.display = 'flex';

    }, 2000);
});