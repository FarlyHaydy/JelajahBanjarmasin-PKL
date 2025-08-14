// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Hover effects for recreation cards (mengikuti style dashboard)
document.querySelectorAll('.row:not(:last-child)').forEach(card => {
    const cardImage = card.querySelector('.card-image');
    const cardContent = card.querySelector('.card-content');
    
    card.addEventListener('mouseenter', function() {
        // Menambahkan smooth transition
        if (cardImage) {
            cardImage.style.transition = 'all 0.3s ease';
            cardImage.style.transform = 'translateY(-10px)';
            cardImage.style.boxShadow = '0 20px 40px rgba(0, 212, 170, 0.3)';
        }
        if (cardContent) {
            cardContent.style.transition = 'all 0.3s ease';
            cardContent.style.transform = 'translateY(-10px)';
            cardContent.style.boxShadow = '0 20px 40px rgba(0, 212, 170, 0.3)';
        }
    });
    
    card.addEventListener('mouseleave', function() {
        if (cardImage) {
            cardImage.style.transform = 'translateY(0)';
            cardImage.style.boxShadow = 'none';
        }
        if (cardContent) {
            cardContent.style.transform = 'translateY(0)';
            cardContent.style.boxShadow = 'none';
        }
    });
});

// Hover effect for wide card (mengikuti style dashboard)
document.querySelectorAll('.wide-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
        this.style.transform = 'translateY(-10px) scale(1.02)';
        this.style.boxShadow = '0 20px 40px rgba(0, 212, 170, 0.3)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
        this.style.boxShadow = 'none';
    });
});

// Click handlers for arrows with better functionality
document.querySelectorAll('.card-arrow, .wide-card-arrow').forEach(arrow => {
    arrow.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const cardTitle = this.closest('[class*="col"]').querySelector('h3, .card-title, .wide-card-title').textContent;
        
        // Bisa diganti dengan navigasi ke halaman detail
        console.log(`Navigating to: ${cardTitle}`);
        
        // Contoh: bisa menambahkan smooth scroll ke section detail
        // atau redirect ke halaman khusus
        alert(`Mengunjungi: ${cardTitle}`);
    });
});

// Smooth hover effect untuk card arrow
document.querySelectorAll('.card-arrow').forEach(arrow => {
    arrow.addEventListener('mouseenter', function() {
        this.style.backgroundColor = 'white';
        this.style.transform = 'scale(1.1)';
        this.style.boxShadow = '0 0 20px rgba(255, 255, 255, 0.3)';
        
        const icon = this.querySelector('i');
        if (icon) {
            icon.style.color = '#0a0a0a';
        }
    });
    
    arrow.addEventListener('mouseleave', function() {
        this.style.backgroundColor = 'transparent';
        this.style.transform = 'scale(1)';
        this.style.boxShadow = 'none';
        
        const icon = this.querySelector('i');
        if (icon) {
            icon.style.color = 'white';
        }
    });
});

// Smooth hover effect untuk wide card arrow
document.querySelectorAll('.wide-card-arrow').forEach(arrow => {
    arrow.addEventListener('mouseenter', function() {
        this.style.backgroundColor = 'white';
        this.style.transform = 'scale(1.1)';
        this.style.boxShadow = '0 0 20px rgba(255, 255, 255, 0.3)';
        
        const icon = this.querySelector('i');
        if (icon) {
            icon.style.color = '#667eea';
        }
    });
    
    arrow.addEventListener('mouseleave', function() {
        this.style.backgroundColor = 'transparent';
        this.style.transform = 'scale(1)';
        this.style.boxShadow = 'none';
        
        const icon = this.querySelector('i');
        if (icon) {
            icon.style.color = 'white';
        }
    });
});

// Menambahkan loading animation untuk cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.row:not(:last-child), .wide-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
