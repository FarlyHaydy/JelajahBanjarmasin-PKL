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

// Navigation function for cards
        function navigateToPage(url) {
            console.log('=== NAVIGATION DEBUG ===');
            console.log('Target URL:', url);
            console.log('Current location:', window.location);
            console.log('Origin:', window.location.origin);
            console.log('Pathname:', window.location.pathname);
            
            // Test berbagai format URL
            const testUrls = [
                url, // URL asli
                window.location.origin + url, // Origin + path
                window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + url, // Relative ke current dir
                window.location.protocol + '//' + window.location.host + url // Protocol + host + path
            ];
            
            console.log('Testing URLs:', testUrls);
            
            // Gunakan URL yang paling sederhana dulu
            console.log('Navigating to:', testUrls[0]);
            
            try {
                window.location.href = testUrls[0];
            } catch (error) {
                console.error('Navigation error:', error);
                // Coba URL kedua
                console.log('Trying alternative URL:', testUrls[1]);
                window.location.href = testUrls[1];
            }
        }

        // Test function untuk manual testing
        function testNavigation() {
            console.log('Testing navigation...');
            navigateToPage('/rekreasi');
        }

        // Tambahkan event listener untuk debug
        document.addEventListener('click', function(e) {
            if (e.target.closest('.destination-card')) {
                console.log('Card clicked:', e.target.closest('.destination-card'));
            }
        });

        // Profile dropdown functionality
        function toggleProfileDropdown() {
            const dropdownMenu = document.getElementById('profileDropdownMenu');
            const dropdownArrow = document.getElementById('dropdownArrow');
            
            dropdownMenu.classList.toggle('show');
            dropdownArrow.classList.toggle('rotated');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const dropdownMenu = document.getElementById('profileDropdownMenu');
            const dropdownArrow = document.getElementById('dropdownArrow');
            
            if (profileDropdown && !profileDropdown.contains(event.target)) {
                dropdownMenu.classList.remove('show');
                dropdownArrow.classList.remove('rotated');
            }
        });

        // Logout confirmation
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = '/logout';
            }
        }

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const dropdownMenu = document.getElementById('profileDropdownMenu');
                const dropdownArrow = document.getElementById('dropdownArrow');
                
                if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownArrow.classList.remove('rotated');
                }
            }
        });

        // Navbar scroll effect (dari JS dashboard asli)
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for navigation links (dari JS dashboard asli)
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

        // Add hover effects to destination cards (dari JS dashboard asli)
        document.querySelectorAll('.destination-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Slider functionality (dari JS dashboard asli)
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

        // Initialize slider when DOM is loaded (dari JS dashboard asli)
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