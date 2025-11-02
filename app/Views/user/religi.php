<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Religi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/rekreasi_kuliner_religi_newKategori.css') ?>">
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
                    
                    <!-- Dashboard Admin - Hanya untuk Admin -->
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
    
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="main-title text-center mb-5">Wisata Religi Banjarmasin</h1>
                
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
                                <i class="fas fa-mosque fa-4x mb-4" style="color: rgba(255,255,255,0.3);"></i>
                                <h3 style="color: #fff; margin-bottom: 20px;">Belum Ada Destinasi Wisata Religi</h3>
                                <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">
                                    Destinasi wisata religi akan muncul di sini setelah admin menambahkannya.
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