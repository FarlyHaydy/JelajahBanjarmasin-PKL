<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">Banjarmasin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/') ?>">Beranda</a>
                </li>
                
                <!-- Dynamic Kategori Menu -->
                <?php if (isset($kategori) && !empty($kategori)): ?>
                    <?php foreach ($kategori as $kat): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/kategori/' . strtolower($kat['nama_kategori'])) ?>">
                                <?= esc($kat['nama_kategori']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback ke kategori static jika tidak ada data -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/rekreasi') ?>">Rekreasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/kuliner') ?>">Kuliner</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/religi') ?>">Religi</a>
                    </li>
                <?php endif; ?>

        <!-- Profile Dropdown atau Login Link -->
        <?php if (session()->get('isLoggedIn')): ?>
            <li class="nav-item profile-dropdown">
                <button class="profile-toggle" onclick="toggleProfileDropdown()">
                    <i class="fas fa-user-circle"></i>
                    <span><?= esc(session()->get('username')) ?? 'Profil' ?></span>
                    <i class="fas fa-chevron-down dropdown-arrow" id="dropdownArrow"></i>
                </button>
                <div class="profile-dropdown-menu" id="profileDropdownMenu">
                    <a href="/profil">
                        <i class="fas fa-user"></i>Lihat Profil
                    </a>
                    
                    <!-- Dashboard Admin - Hanya untuk Admin akan tertampil apabila admin -->
                    <?php if (session()->get('role') === 'admin'): ?>
                        <a href="/admin/dashboard">
                            <i class="fas fa-user-shield"></i>Dashboard Admin
                        </a>
                    <?php endif; ?>
                    
                    <!-- logout buat user saja  -->
                    <?php if (session()->get('role') === 'user'): ?>
                    <a href="#" onclick="confirmLogout()" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                    <?php endif; ?>
                </div>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="/login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
            
    <!-- Section welcome to banua -->
    <section id="beranda" class="hero-section">
        <div class="hero-content">
            <h1>Jelajah Banjarmasin</h1>
            <p>Banjarmasin adalah kota terbesar di provinsi Kalimantan Selatan yang berada di Indonesia dan kota ini pernah menjadi ibu kota provinsi Kalimantan Selatan</p>
        </div>
    </section>

    <!-- Destinations Section -->
    <section id="rekreasi" class="destinations-section">
        <div class="container">
            <div class="section-title">
                <h2>Destinasi Terbaik</h2>
                <p>Jelajahi kota Banjarmasin yang mempesona<br>mulai dari rekreasi, kuliner serta religi</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large patung-bekantan" 
                        onclick="navigateToDetail(9)">
                        <div class="card-content">
                            <h3>Patung Bekantan</h3>
                            <p>Monumen ikonik yang menggambarkan bekantan, satwa khas Kalimantan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large pasar-terapung" 
                        onclick="navigateToDetail(5)">
                        <div class="card-content">
                            <h3>Pasar Terapung</h3>
                            <p>Pasar Terapung tradisional yang berlokasi di siring</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large soto-bang-amat" 
                        onclick="navigateToDetail(6)">
                        <div class="card-content">
                            <h3>Soto Bang Amat</h3>
                            <p>Soto legendaris Banjarmasin dengan cita rasa khas yang menggugah selera.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large menara-pandang" 
                        onclick="navigateToDetail(2)">
                        <div class="card-content">
                            <h3>Menara Pandang</h3>
                            <p>Menara yang menawarkan pemandangan kota Banjarmasin dari ketinggian</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large masjid-sabilal" 
                        onclick="navigateToDetail(12)">
                        <div class="card-content">
                            <h3>Masjid Raya Sabilal Muhtadin</h3>
                            <p>Masjid kebanggaan Banjarmasin, pusat ibadah dengan arsitektur megah dan sejarah yang kaya</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large katedral-keluarga-kudus" 
                        onclick="navigateToDetail(15)">
                        <div class="card-content">
                            <h3>Katedral Keluarga Kudus</h3>
                            <p>Tempat ibadah yang indah dengan arsitektur khas, menjadi simbol spiritualitas di Banjarmasin.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<!-- populer section -->
<section class="explore-section">
    <div class="container">
        <h2>Destinasi Populer</h2>
        <div class="slider-container">
            <div class="slider-wrapper" id="sliderWrapper">
                <div class="slide slide-1" onclick="navigateToDetail(2)"></div>
                <div class="slide slide-2" onclick="navigateToDetail(5)"></div>
                <div class="slide slide-3" onclick="navigateToDetail(6)"></div>
                <div class="slide slide-4" onclick="navigateToDetail(5)"></div>
            </div>   
            
            <!-- Navigation Arrows -->
            <button class="nav-arrow prev" onclick="changeSlide(-1)">❮</button>
            <button class="nav-arrow next" onclick="changeSlide(1)">❯</button>
        </div>
        
        <!-- Dots Indicator -->
        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
        </div>
    </div>
</section>

    <!-- Jelajah Banjarmasin Section -->
    <section class="jelajah-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="jelajah-image"></div>
                </div>
                <div class="col-lg-6">
                    <div class="jelajah-content">
                        <h2>Jelajah Banjarmasin</h2>
                        <p>Banjarmasin adalah kota terbesar di provinsi Kalimantan Selatan yang berada di Indonesia dan kota ini pernah menjadi ibu kota provinsi Kalimantan Selatan. Kota ini terkenal dengan julukan "Kota Seribu Sungai" karena banyaknya sungai yang mengalir di wilayah ini. Banjarmasin memiliki berbagai destinasi wisata menarik mulai dari wisata religi, kuliner khas, hingga wisata alam yang memukau.</p>
                        <p>Jelajahi keindahan Banjarmasin dan rasakan pengalaman tak terlupakan di kota yang kaya akan budaya dan sejarah ini. Dari pasar terapung yang unik hingga masjid-masjid bersejarah, setiap sudut kota ini menyimpan cerita dan pesona tersendiri.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="footer-logo">Banjarmasin</div>
                    <div class="footer-tagline">Welcome to Banua</div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Contact</h5>
                    <ul>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="fab fa-whatsapp"></i> Whatsapp</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> Email</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    
// ===== NAVBAR SCROLL EFFECT =====
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// ===== PROFILE DROPDOWN =====
function toggleProfileDropdown() {
    const dropdownMenu = document.getElementById('profileDropdownMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');
    
    dropdownMenu.classList.toggle('show');
    dropdownArrow.classList.toggle('rotated');
}

// Tutup dropdown saat klik di luar area dropdown
document.addEventListener('click', function(event) {
    const profileDropdown = document.querySelector('.profile-dropdown');
    const dropdownMenu = document.getElementById('profileDropdownMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');
    
    if (profileDropdown && !profileDropdown.contains(event.target)) {
        dropdownMenu.classList.remove('show');
        dropdownArrow.classList.remove('rotated');
    }
});

// Tutup dropdown saat tekan tombol Escape
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

// ===== CONFIRM LOGOUT =====
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        window.location.href = '/logout';
    }
}

// ===== DESTINATION CARDS HOVER EFFECT =====
document.querySelectorAll('.destination-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// ===== SLIDER FUNCTIONALITY =====
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const sliderWrapper = document.getElementById('sliderWrapper');

function showSlide(index) {
    // Reset index jika melebihi jumlah slide
    if (index >= slides.length) slideIndex = 0;
    if (index < 0) slideIndex = slides.length - 1;
    
    // Geser slider
    if (sliderWrapper) {
        sliderWrapper.style.transform = `translateX(-${slideIndex * 100}%)`;
    }
    
    // Update dots indicator
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

// ===== NAVIGATION =====
function navigateToKategori(kategoriName) {
    const kategoriUrl = `/kategori/${kategoriName.toLowerCase()}`;
    window.location.href = kategoriUrl;
}

function navigateToDetail(wisataId) {
    window.location.href = '/detail/' + wisataId;
}

// Highlight active menu di navbar
function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (linkPath === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// ===== INITIALIZE SAAT HALAMAN DIMUAT =====
document.addEventListener('DOMContentLoaded', function() {
    // Set active menu
    setActiveNavItem();
    
    // Initialize slider jika elemen ada
    if (slides.length > 0) {
        showSlide(slideIndex);
        
        // Auto slide setiap 5 detik
        setInterval(() => {
            slideIndex++;
            showSlide(slideIndex);
        }, 5000);
    }
});
    </script>
</body>
</html>