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

// Add hover effects to destination cards
document.querySelectorAll('.destination-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Slider functionality
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const sliderWrapper = document.getElementById('sliderWrapper');

function showSlide(index) {
    // Reset jika index melebihi jumlah slide
    if (index >= slides.length) slideIndex = 0;
    if (index < 0) slideIndex = slides.length - 1;
    
    // Geser slider
    if (sliderWrapper) {
        sliderWrapper.style.transform = `translateX(-${slideIndex * 100}%)`;
    }
    
    // Update dots
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === slideIndex);
    });
}

function changeSlide(direction) {
    slideIndex += direction;
    showSlide(slideIndex);
}

function currentSlide(index) {
    slideIndex = index - 1;
    showSlide(slideIndex);
}

// Initialize slider when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize slider jika elemen ada
    if (slides.length > 0) {
        showSlide(slideIndex);
        
        // Auto slide (opsional)
        setInterval(() => {
            slideIndex++;
            showSlide(slideIndex);
        }, 5000); // Ganti slide setiap 5 detik
    }
});