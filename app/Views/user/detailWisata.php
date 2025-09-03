<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($wisata['nama_wisata']) ?> - Detail Wisata</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: white;
            background: #111;
            padding-top: 80px;
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

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Hero Image Section */
        .hero-section {
            position: relative;
            margin-bottom: 30px;
        }

        .hero-image-container {
            position: relative;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .hero-overlay {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 2;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .bookmark-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.9);
            width: 45px;
            height: 45px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            z-index: 2;
        }

        .bookmark-btn:hover {
            background: white;
            transform: scale(1.05);
        }

        .bookmark-btn i {
            font-size: 1.1rem;
            color: #333;
        }

        /* Gallery Thumbnails */
        .gallery-section {
            margin-bottom: 40px;
        }

        .gallery-row {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .thumbnail-container {
            flex: 1;
        }

        .thumbnail {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .thumbnail:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .thumbnail.active {
            border-color: white;
        }

        /* Content Section */
        .content-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        /* Detail Section */
        .detail-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: white;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.9);
        }

        .detail-icon {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            margin-top: 2px;
        }

        .detail-icon i {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .detail-content {
            flex: 1;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .detail-content strong {
            color: white;
        }

        /* Description Section */
        .description-section {
            margin-top: 50px;
        }

        .description-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: white;
        }

        .description-section p {
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            text-align: justify;
        }

        /* Full Width Map Section - Simplified */
        .location-section-fullwidth {
            margin-top: 50px;
            /* Break out of container - full width */
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .fullwidth-map-container {
            width: 100%;
            height: 300px;
            position: relative;
        }

        .map-iframe-fullwidth {
            width: 100%;
            height: 100%;
            border: none;
        }

        .direction-buttons-fullwidth {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding: 25px 20px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn-direction-fullwidth {
            background: #00d4aa;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-direction-fullwidth:hover {
            background: #00b89a;
            color: white;
            transform: translateY(-1px);
        }

        .btn-direction-fullwidth i {
            font-size: 1rem;
        }

        /* Sidebar */
        .sidebar {
            padding-left: 20px;
        }

        .sidebar-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .sidebar-card h3 {
            color: white;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        /* Modal Styles */
        .modal-content {
            background: #222;
            border: none;
            border-radius: 12px;
        }

        .modal-header {
            background: #333;
            border-bottom: 1px solid #444;
            color: white;
        }

        .modal-body {
            padding: 0;
        }

        .modal-footer {
            background: #333;
            border-top: 1px solid #444;
        }

        .btn-close {
            filter: invert(1);
        }

        .modal-gallery {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .modal-thumbnail {
            width: 70px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .modal-thumbnail:hover {
            transform: scale(1.05);
            border-color: #00d4aa;
        }

        /* Footer */
        .footer {
            background: #111;
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 60px;
            border-top: 1px solid #333;
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

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            color: #333;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            border-left: 4px solid #28a745;
        }

        .notification-error {
            border-left: 4px solid #dc3545;
        }

        .notification i {
            font-size: 1.2rem;
        }

        .notification-success i {
            color: #28a745;
        }

        .notification-error i {
            color: #dc3545;
        }

        /* Bookmark Button Enhancement */
        .bookmark-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .content-section {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .sidebar {
                padding-left: 0;
            }

            .fullwidth-map-container {
                height: 250px;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .main-container {
                padding: 20px 15px;
            }
            
            .hero-image-container {
                height: 300px;
            }
            
            .gallery-row {
                gap: 10px;
            }
            
            .thumbnail {
                height: 80px;
            }
            
            .hero-overlay {
                top: 20px;
                left: 20px;
            }
            
            .bookmark-btn {
                top: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
            }

            .direction-buttons-fullwidth {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                padding: 20px;
            }

            .btn-direction-fullwidth {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }

            .fullwidth-map-container {
                height: 200px;
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

    <!-- Main Container -->
    <div class="main-container">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-image-container">
                <?php if (!empty($images)): ?>
                    <img src="<?= base_url('uploads/wisata/' . $images[0]['nama_file']) ?>" 
                         alt="<?= esc($wisata['nama_wisata']) ?>" 
                         class="hero-image" 
                         id="mainImage" 
                         onclick="showImageModal(this.src)">
                <?php else: ?>
                    <img src="<?= base_url('images/default-wisata.jpg') ?>" 
                         alt="<?= esc($wisata['nama_wisata']) ?>" 
                         class="hero-image" 
                         id="mainImage">
                <?php endif; ?>
                
                <div class="hero-overlay">
                    <h1 class="hero-title"><?= esc($wisata['nama_wisata']) ?></h1>
                </div>
                <button class="bookmark-btn" onclick="toggleBookmark(<?= $wisata['wisata_id'] ?>)" id="bookmarkBtn">
                    <i class="far fa-bookmark" id="bookmarkIcon"></i>
                </button>
            </div>
        </div>

        <!-- Gallery Section -->
        <?php if (!empty($images) && count($images) > 1): ?>
        <div class="gallery-section">
            <div class="gallery-row">
                <?php foreach (array_slice($images, 0, 5) as $index => $image): ?>
                    <div class="thumbnail-container">
                        <img src="<?= base_url('uploads/wisata/' . $image['nama_file']) ?>" 
                             alt="<?= esc($wisata['nama_wisata']) ?> <?= $index + 1 ?>" 
                             class="thumbnail <?= $index === 0 ? 'active' : '' ?>"
                             data-image="<?= base_url('uploads/wisata/' . $image['nama_file']) ?>"
                             onclick="changeMainImage(this)">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Left Column - Detail & Description -->
            <div class="main-content">
                <!-- Detail Section -->
                <div class="detail-section">
                    <h2>Detail</h2>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <strong>Alamat:</strong> <?= esc($wisata['alamat']) ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="detail-content">
                            <strong>Lokasi:</strong> <?= esc($wisata['nama_kecamatan']) ?>, <?= esc($wisata['nama_kota']) ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="detail-content">
                            <strong>Kategori:</strong> <?= esc($wisata['nama_kategori']) ?>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="description-section">
                    <h2>Deskripsi</h2>
                    <p><?= nl2br(esc($wisata['detail'])) ?></p>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="sidebar">
                <div class="sidebar-card">
                    <h3>Informasi Tambahan</h3>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="detail-content">
                            <strong>Total Foto:</strong> <?= count($images) ?> gambar
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="detail-content">
                            <strong>Bagikan:</strong>
                            <div class="mt-2">
                                <a href="#" class="btn btn-sm btn-primary me-1">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-info me-1">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-success">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigasi ke kategori -->
                <div class="sidebar-card">
                    <h3>Jelajahi Lebih</h3>
                    <a href="<?= base_url('/' . strtolower($wisata['nama_kategori'])) ?>" class="btn btn-outline-light w-100 mb-2">
                        <i class="fas fa-list"></i> Lihat Semua <?= esc($wisata['nama_kategori']) ?>
                    </a>
                    <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-light w-100">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Width Map Section - No Header -->
    <div class="location-section-fullwidth">
        <div class="fullwidth-map-container">
            <?php if (!empty($wisata['latitude']) && !empty($wisata['longitude'])): ?>
                <iframe class="map-iframe-fullwidth"
                    src="https://www.google.com/maps?q=<?= $wisata['latitude'] ?>,<?= $wisata['longitude'] ?>&hl=id&z=16&output=embed"
                    allowfullscreen>
                </iframe>
            <?php else: ?>
                <iframe class="map-iframe-fullwidth"
                    src="https://www.google.com/maps?q=<?= urlencode($wisata['alamat'] . ', ' . $wisata['nama_kecamatan'] . ', ' . $wisata['nama_kota']) ?>&hl=id&z=16&output=embed"
                    allowfullscreen>
                </iframe>
            <?php endif; ?>
        </div>

        <div class="direction-buttons-fullwidth">
            <?php if (!empty($wisata['latitude']) && !empty($wisata['longitude'])): ?>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= $wisata['latitude'] ?>,<?= $wisata['longitude'] ?>" 
                   target="_blank" class="btn-direction-fullwidth">
                    <i class="fab fa-google"></i> Buka di Google Maps
                </a>
                <a href="https://maps.apple.com/?q=<?= $wisata['latitude'] ?>,<?= $wisata['longitude'] ?>" 
                   target="_blank" class="btn-direction-fullwidth">
                    <i class="fab fa-apple"></i> Buka di Apple Maps
                </a>
                <a href="https://waze.com/ul?ll=<?= $wisata['latitude'] ?>,<?= $wisata['longitude'] ?>" 
                   target="_blank" class="btn-direction-fullwidth">
                    <i class="fas fa-route"></i> Buka di Waze
                </a>
            <?php else: ?>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($wisata['alamat'] . ', ' . $wisata['nama_kecamatan'] . ', ' . $wisata['nama_kota']) ?>" 
                   target="_blank" class="btn-direction-fullwidth">
                    <i class="fab fa-google"></i> Buka di Google Maps
                </a>
                <a href="https://maps.apple.com/?q=<?= urlencode($wisata['alamat'] . ', ' . $wisata['nama_kecamatan'] . ', ' . $wisata['nama_kota']) ?>" 
                   target="_blank" class="btn-direction-fullwidth">
                    <i class="fab fa-apple"></i> Buka di Apple Maps
                </a>
                <a href="https://waze.com/ul?q=<?= urlencode($wisata['alamat'] . ', ' . $wisata['nama_kecamatan'] . ', ' . $wisata['nama_kota']) ?>" 
                   target="_blank" class="btn-direction-fullwidth">
                    <i class="fas fa-route"></i> Buka di Waze
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= esc($wisata['nama_wisata']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="" class="w-100">
                </div>
                <?php if (!empty($images) && count($images) > 1): ?>
                <div class="modal-footer">
                    <div class="modal-gallery">
                        <?php foreach ($images as $image): ?>
                            <img src="<?= base_url('uploads/wisata/' . $image['nama_file']) ?>" 
                                 alt="<?= esc($wisata['nama_wisata']) ?>" 
                                 class="modal-thumbnail"
                                 onclick="changeModalImage('<?= base_url('uploads/wisata/' . $image['nama_file']) ?>')">
                        <?php endforeach; ?>
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
        
        // Change main image
        function changeMainImage(thumbnail) {
            const mainImage = document.getElementById('mainImage');
            const newImageSrc = thumbnail.getAttribute('data-image');
            
            mainImage.src = newImageSrc;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }

        // Show image modal
        function showImageModal(imageSrc) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        // Change modal image
        function changeModalImage(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
        }

        // Toggle bookmark with AJAX - PERBAIKAN CSRF
        function toggleBookmark(wisataId) {
            const bookmarkBtn = document.getElementById('bookmarkBtn');
            const bookmarkIcon = document.getElementById('bookmarkIcon');
            
            console.log('Toggle bookmark called for wisata ID:', wisataId);
            
            // Disable button sementara
            bookmarkBtn.disabled = true;
            
            // Create FormData for proper POST request
            const formData = new FormData();
            formData.append('wisata_id', wisataId);
            
            fetch('<?= base_url('wishlist/toggle') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.status === 'success') {
                    // Update icon
                    bookmarkIcon.className = data.icon;
                    
                    // Show notification
                    showNotification(data.message, 'success');
                } else if (data.status === 'error') {
                    showNotification(data.message, 'error');
                    
                    // Redirect ke login jika diperlukan
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan sistem', 'error');
            })
            .finally(() => {
                // Re-enable button
                bookmarkBtn.disabled = false;
            });
        }

        // Show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Check bookmark status on page load - UPDATED
        document.addEventListener('DOMContentLoaded', function() {
            const wisataId = <?= $wisata['wisata_id'] ?>;
            
            console.log('Checking bookmark status for wisata ID:', wisataId);
            
            // Set initial bookmark status from server if available
            <?php if (isset($isBookmarked) && $isBookmarked): ?>
                console.log('Initial bookmark status: bookmarked');
                const bookmarkIcon = document.getElementById('bookmarkIcon');
                bookmarkIcon.className = 'fas fa-bookmark';
            <?php else: ?>
                console.log('Initial bookmark status: not bookmarked');
            <?php endif; ?>
            
            // Double check with server for current status
            fetch(`<?= base_url('wishlist/check-status/') ?>${wisataId}`)
                .then(response => {
                    console.log('Check status response:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Check status data:', data);
                    const bookmarkIcon = document.getElementById('bookmarkIcon');
                    bookmarkIcon.className = data.icon;
                })
                .catch(error => {
                    console.log('Error checking bookmark status:', error);
                    // Keep the initial state if check fails
                });
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
    </script>
</body>
</html>