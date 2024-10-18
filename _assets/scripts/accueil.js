alert('JavaScript est bien chargé');
const points = document.querySelectorAll('.event-point');
const tooltip = document.getElementById('tooltip');

points.forEach(point => {
    point.addEventListener('mouseenter', function() {
        // Utilise "aucune info disponnible" comme texte par défaut si `data-event` est vide.
        const eventText = this.dataset.event || 'aucune info disponnible';
        tooltip.textContent = eventText;

        const pointRect = this.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();

        tooltip.style.left = `${pointRect.left + pointRect.width / 2 - tooltipRect.width / 2}px`;
        tooltip.style.top = `${pointRect.bottom + window.scrollY + 10}px`;

        tooltip.style.opacity = '1';
        tooltip.style.transform = 'translateY(0px)';
    });

    point.addEventListener('mouseleave', function() {
        tooltip.style.opacity = '0';
        tooltip.style.transform = 'translateY(-10px)';
    });
});
