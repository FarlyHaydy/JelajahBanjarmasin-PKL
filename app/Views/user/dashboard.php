<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Navigation */
        .navbar {
            background: rgba(0, 0, 0, 0.1) !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.9) !important;
        }

        .navbar-brand {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            font-weight: bold;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 1rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #00d4aa !important;
            transform: translateY(-2px);
        }

        /* Custom styles for profile dropdown */
        .profile-dropdown {
            position: relative;
        }
        
        .profile-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .profile-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 5px;
        }
        
        .profile-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .profile-dropdown-menu a {
            display: block;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .profile-dropdown-menu a:last-child {
            border-bottom: none;
        }
        
        .profile-dropdown-menu a:hover {
            background-color: #f8f9fa;
        }
        
        .profile-dropdown-menu a:first-child {
            border-radius: 8px 8px 0 0;
        }
        
        .profile-dropdown-menu a:last-child {
            border-radius: 0 0 8px 8px;
        }
        
        .profile-dropdown-menu i {
            margin-right: 8px;
            width: 16px;
        }
        
        .logout-btn {
            color: #dc3545 !important;
        }
        
        .logout-btn:hover {
            background-color: #fff5f5 !important;
        }

        /* Animation for dropdown arrow */
        .dropdown-arrow {
            transition: transform 0.3s ease;
        }
        
        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        /* Hero Section */
        .hero-section {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),url('/images/Tampilan_Awal.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            animation: fadeInUp 1s ease;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            max-width: 800px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            animation: fadeInUp 1s ease 0.3s both;
        }

        .hero-tagline {
            font-size: 1.5rem;
            color: #00d4aa;
            margin-bottom: 0.5rem;
            animation: fadeInUp 1s ease 0.1s both;
        }

        /* Destinations Section */
        .destinations-section {
            padding: 5rem 0;
            background: #000;
            color: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #ccc;
            margin-bottom: 0;
        }

        .destination-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
            height: 300px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: stretch;
        }

        .destination-card.pasar-terapung {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7)), url('/images/Pasar_Terapung_Utama2.png');
        }

        .destination-card.musik-panting {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7)), url('/images/Musik_Painting_Utama.png');
        }

        .destination-card.masjid-sabilal {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7)), url('/images/Masjid_Sabilal_Utama.png');
        }

        .destination-card.menara-pandang {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7)), url('/images/Menara_Pandang_Utama2.png');
        }

        .destination-card.masjid-sultan {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7)), url('/images/Masjid_Sabilal_Utama.png');
        }

        .destination-card.pulau-kembang {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7)), url('/images/Pulau_Kembang_Utama.png');
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 212, 170, 0.3);
        }

        .destination-card.large {
            height: 400px;
        }

        .destination-card .card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 2rem;
            color: white;
            margin-top: auto;
        }

        .destination-card h3 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .destination-card p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Explore Banjarmasin */
        .explore-section {
            padding: 2rem 0 5rem 0;
            background: #000;
            color: white;
            text-align: center;
        }

        .explore-section h2 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .slider-container {
            position: relative;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 20px;
        }

        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    
        .slide-1 {
            background-image: url('/images/Menara_Pandang_Utama.png');
        }

        .slide-2 {
            background-image: url('/images/Musik_Painting_Utama.png');
        }

        .slide-3 {
            background-image: url('/images/Masjid_Sabilal_Utama.png');
        }

        .slide-4 {
            background-image: url('/images/Pulau_Kembang_Utama.png');
        }

        /* Navigation arrows */
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            color: #333;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .nav-arrow:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-50%) scale(1.1);
        }

        .nav-arrow.prev {
            left: 20px;
        }

        .nav-arrow.next {
            right: 20px;
        }

        /* Dots indicator */
        .dots-container {
            text-align: center;
            margin-top: 2rem;
        }

        .dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            margin: 0 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .dot.active {
            background: white;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Jelajah Banjarmasin Section */
        .jelajah-section {
            padding: 5rem 0;
            background: #000;
            color: white;
        }

        .jelajah-image {
            height: 400px;
            background-image: url('/images/Tourist_background.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .jelajah-content h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: white;
        }

        .jelajah-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #ccc;
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        /* Footer */
        .footer {
            background: #111;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-logo {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .footer-tagline {
            color: #888;
            margin-bottom: 2rem;
        }

        .footer h5 {
            color: white;
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .footer ul {
            list-style: none;
            padding: 0;
        }

        .footer ul li {
            margin-bottom: 0.5rem;
        }

        .footer ul li a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer ul li a:hover {
            color: #00d4aa;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .destination-card {
                height: 250px;
            }

            .destination-card.large {
                height: 300px;
            }

            .navbar-nav {
                text-align: center;
            }

            .explore-section h2 {
                font-size: 2rem;
            }
            
            .slide {
                height: 300px;
            }
            
            .nav-arrow {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .jelajah-section {
                padding: 3rem 0;
            }
            
            .jelajah-image {
                height: 300px;
                margin-bottom: 3rem;
            }
            
            .jelajah-content h2 {
                font-size: 2rem;
                text-align: center;
            }
            
            .jelajah-content p {
                font-size: 1rem;
                text-align: left;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .destination-card h3 {
                font-size: 1.5rem;
            }

            .destination-card .card-content {
                padding: 1.5rem;
            }

            .jelajah-image {
                height: 250px;
            }
            
            .jelajah-content h2 {
                font-size: 1.8rem;
            }
            
            .jelajah-content p {
                font-size: 0.95rem;
            }
        }
    </style>
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
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('/rekreasi') ?>">Rekreasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/kuliner') ?>">Kuliner</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/religi') ?>">Religi</a>
                    </li>
                    
                    <!-- Profile Dropdown or Login Link -->
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item profile-dropdown">
                            <button class="profile-toggle" onclick="toggleProfileDropdown()">
                                <i class="fas fa-user-circle"></i>
                                <span><?= esc(session()->get('Nama_Asli')) ?? 'Profil' ?></span>
                                <i class="fas fa-chevron-down dropdown-arrow" id="dropdownArrow"></i>
                            </button>
                            <div class="profile-dropdown-menu" id="profileDropdownMenu">
                                <a href="/profil">
                                    <i class="fas fa-user"></i>Lihat Profil
                                </a>
                                <a href="#" onclick="confirmLogout()" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>Logout
                                </a>
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
    
    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="hero-content">
            <div class="hero-tagline">Welcome to Banua</div>
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
                    <div class="destination-card large pasar-terapung" onclick="navigateToPage('/rekreasi')">
                        <div class="card-content">
                            <h3>Pasar Terapung</h3>
                            <p>Pasar Terapung tradisional yang berlokasi di siring</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large musik-panting" onclick="navigateToPage('/kuliner')">
                        <div class="card-content">
                            <h3>Musik Panting</h3>
                            <p>Seni tradisional Kalimantan Selatan yang memukau dengan harmonisasi musik dan cerita</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large masjid-sabilal" onclick="navigateToPage('/religi')">
                        <div class="card-content">
                            <h3>Masjid Sabilal Muhtadin</h3>
                            <p>Masjid raya yang menjadi kebanggaan masyarakat Banjarmasin dengan arsitektur yang megah</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large menara-pandang" onclick="navigateToPage('/rekreasi')">
                        <div class="card-content">
                            <h3>Menara Pandang</h3>
                            <p>Menara yang menawarkan pemandangan kota Banjarmasin dari ketinggian</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large masjid-sultan" onclick="navigateToPage('/religi')">
                        <div class="card-content">
                            <h3>Masjid Sultan Suriansyah</h3>
                            <p>Masjid tertua di Kalimantan Selatan dengan nilai sejarah yang tinggi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large pulau-kembang" onclick="navigateToPage('/kuliner')">
                        <div class="card-content">
                            <h3>Pulau Kembang</h3>
                            <p>Pulau kecil yang menjadi habitat bekantan dan tempat wisata alam yang menarik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Explore dan slider Banjarmasin -->
    <section class="explore-section">
        <div class="container">
            <h2>Destinasi Populer</h2>
            <div class="slider-container">
            <div class="slider-wrapper" id="sliderWrapper">
                <div class="slide slide-1"></div>
                <div class="slide slide-2"></div>
                <div class="slide slide-3"></div>
                <div class="slide slide-4"></div>
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
                <div class="col-lg-2 col-md-4">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#"><i class="fas fa-question-circle"></i> FAQs</a></li>
                        <li><a href="#"><i class="fas fa-comment-dots"></i> Feedback</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
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
    </script>
</body>
</html>