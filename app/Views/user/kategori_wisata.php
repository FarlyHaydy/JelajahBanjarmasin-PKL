<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($kategori) ?> - Banjarmasin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/rekreasi_kuliner_religi.css') ?>">
    <style>
        /* Profile dropdown styles */
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

        .dropdown-arrow {
            transition: transform 0.3s ease;
        }
        
        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        /* Existing styles */
        .no-data-message {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 60px 40px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .card-image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 400px;
            border-radius: 15px;
        }
        
        .navbar-nav .nav-link.active {
            color: #00d4aa !important;
            font-weight: bold;
        }

        .card-arrow {
            transition: all 0.3s ease;
            display: inline-block;
        }

        .card-arrow:hover {
            transform: translateX(5px);
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.9) !important;
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
                    
                    <!-- Dynamic Kategori Menu -->
                    <?php if (isset($kategori_list) && !empty($kategori_list)): ?>
                        <?php foreach ($kategori_list as $kat): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= ($kat['nama_kategori'] == $kategori) ? 'active' : '' ?>" 
                                   href="<?= base_url('/kategori/' . strtolower($kat['nama_kategori'])) ?>">
                                    <?= esc($kat['nama_kategori']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback ke kategori static jika tidak ada data -->
                        <li class="nav-item">
                            <a class="nav-link <?= ($kategori == 'Rekreasi') ? 'active' : '' ?>" href="<?= base_url('/rekreasi') ?>">Rekreasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($kategori == 'Kuliner') ? 'active' : '' ?>" href="<?= base_url('/kuliner') ?>">Kuliner</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($kategori == 'Religi') ? 'active' : '' ?>" href="<?= base_url('/religi') ?>">Religi</a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Profile Dropdown or Login Link -->
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

                                <?php if (session()->get('role') === 'admin'): ?>
                                    <a href="/admin/dashboard">
                                        <i class="fas fa-user-shield"></i>Dashboard Admin
                                    </a>
                                <?php endif; ?>
                                
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
    
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="main-title text-center mb-5"><?= esc($kategori) ?> Banjarmasin</h1>
                
                <?php if (!empty($wisata)): ?>
                    <?php foreach ($wisata as $index => $w): ?>
                        <?php $isEven = ($index % 2 == 0); ?>
                        
                        <?php if ($isEven): ?>
                            <!-- Card: Image Left, Text Right -->
                            <div class="row mb-5 align-items-center">
                                <div class="col-lg-6">
                                     <div class="card-image" style="background-image: url('<?= base_url('uploads/wisata/' . $w['primary_image']) ?>');">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card-content">
                                        <h3 class="card-title"><?= esc($w['nama_wisata']) ?></h3>
                                        <p class="card-description">
                                            <?= esc($w['deskripsi']) ?>
                                        </p>
                                         <a href="<?= base_url('/detail/' . $w['wisata_id']) ?>" 
                                            class="btn-more btn btn-outline-light rounded-pill" 
                                            title="Lihat detail <?= esc($w['nama_wisata']) ?>">
                                            Lihat selengkapnya
                                            </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Card: Text Left, Image Right -->
                            <div class="row mb-5 align-items-center">
                                <div class="col-lg-6 order-lg-2">
                                     <div class="card-image" style="background-image: url('<?= base_url('uploads/wisata/' . $w['primary_image']) ?>');">
                                    </div>
                                </div>
                                <div class="col-lg-6 order-lg-1">
                                    <div class="card-content">
                                        <h3 class="card-title"><?= esc($w['nama_wisata']) ?></h3>
                                        <p class="card-description">
                                            <?= esc($w['deskripsi']) ?>
                                        </p>
                                         <a href="<?= base_url('/detail/' . $w['wisata_id']) ?>" 
                                            class="btn-more btn btn-outline-light rounded-pill" 
                                            title="Lihat detail <?= esc($w['nama_wisata']) ?>">
                                            Lihat selengkapnya
                                            </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- No Data State -->
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="no-data-message">
                                <?php 
                                // Dynamic icon based on category
                                $iconMap = [
                                    'Kuliner' => 'fa-utensils',
                                    'Rekreasi' => 'fa-mountain',
                                    'Religi' => 'fa-mosque',
                                    'Test' => 'fa-star'
                                ];
                                $icon = $iconMap[$kategori] ?? 'fa-map-marker-alt';
                                ?>
                                <i class="fas <?= $icon ?> fa-4x mb-4" style="color: rgba(255,255,255,0.3);"></i>
                                <h3 style="color: #fff; margin-bottom: 20px;">Belum Ada Destinasi <?= esc($kategori) ?></h3>
                                <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">
                                    Destinasi <?= strtolower(esc($kategori)) ?> akan muncul di sini setelah admin menambahkannya.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
                        <li><a href="<?= base_url('/faq') ?>"><i class="fas fa-question-circle"></i> FAQs</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
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

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add loading state to card arrows
        document.querySelectorAll('.card-arrow').forEach(arrow => {
            arrow.addEventListener('click', function(e) {
                // Optional: Add loading state
                const icon = this.querySelector('i');
                icon.classList.remove('fa-arrow-right');
                icon.classList.add('fa-spinner', 'fa-spin');
                
                // Reset after a short delay (will be replaced by page navigation)
                setTimeout(() => {
                    icon.classList.remove('fa-spinner', 'fa-spin');
                    icon.classList.add('fa-arrow-right');
                }, 500);
            });
        });

        // Debug: Log clicks for troubleshooting
        document.querySelectorAll('.card-arrow').forEach(arrow => {
            arrow.addEventListener('click', function(e) {
                console.log('Navigating to:', this.href);
            });
        });

        // Function untuk navigate ke kategori
function navigateToKategori(kategoriName) {
    const kategoriUrl = `/kategori/${kategoriName.toLowerCase()}`;
    window.location.href = kategoriUrl;
}

// Function untuk highlight active kategori di navbar
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

// Jalankan saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    setActiveNavItem();
});
    </script>
</body>
</html>