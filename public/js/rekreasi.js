// Hover effects for recreation cards
document.querySelectorAll('.row:not(:last-child)').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.transition = 'all 0.3s ease';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Hover effect for wide card
document.querySelector('.wide-card').addEventListener('mouseenter', function() {
    this.style.transform = 'translateY(-5px)';
    this.style.transition = 'all 0.3s ease';
});

document.querySelector('.wide-card').addEventListener('mouseleave', function() {
    this.style.transform = 'translateY(0)';
});

// Click handlers for arrows
document.querySelectorAll('.card-arrow, .wide-card-arrow').forEach(arrow => {
    arrow.addEventListener('click', function() {
        const cardTitle = this.closest('[class*="col"]').querySelector('h3').textContent;
        alert(`Mengunjungi: ${cardTitle}`);
    });
});