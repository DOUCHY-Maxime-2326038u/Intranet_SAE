document.getElementById('playBtn').addEventListener('click', function() {
    document.getElementById('leftBlock').style.transform = 'translateX(-100%)';
    document.getElementById('rightBlock').style.transform = 'translateX(100%)';

    setTimeout(function() {
        document.getElementById('presentation').style.display = 'none';
    }, 1200);
});