<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($wisata['nama_wisata']) ?> - Detail Wisata</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/detailWisata.css') ?>">
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
        <?php 
            $lat = $wisata['latitude'];
            $lng = $wisata['longitude'];
            $dest = $lat . ',' . $lng; 
        ?>
        <a class="btn-direction-fullwidth" target="_blank"
           href="https://www.google.com/maps/dir/?api=1&destination=<?= $dest ?>&travelmode=driving">
            <i class="fas fa-car"></i> Rute Mobil
        </a>
        <a class="btn-direction-fullwidth" target="_blank"
           href="https://www.google.com/maps/dir/?api=1&destination=<?= $dest ?>&travelmode=walking">
            <i class="fas fa-person-walking"></i> Rute Jalan Kaki
        </a>
        <a class="btn-direction-fullwidth" target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=<?= $dest ?>">
            <i class="fas fa-map-location-dot"></i> Buka di Google Maps
        </a>
    <?php else: ?>
        <?php 
            $alamatFull = trim($wisata['alamat'] . ', ' . $wisata['nama_kecamatan'] . ', ' . $wisata['nama_kota']);
            $alamatQ = urlencode($alamatFull);
        ?>
        <a class="btn-direction-fullwidth" target="_blank"
           href="https://www.google.com/maps/dir/?api=1&destination=<?= $alamatQ ?>&travelmode=driving">
            <i class="fas fa-car"></i> Rute Mobil
        </a>
        <a class="btn-direction-fullwidth" target="_blank"
           href="https://www.google.com/maps/dir/?api=1&destination=<?= $alamatQ ?>&travelmode=walking">
            <i class="fas fa-person-walking"></i> Rute Jalan Kaki
        </a>
        <a class="btn-direction-fullwidth" target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=<?= $alamatQ ?>">
            <i class="fas fa-map-location-dot"></i> Buka di Google Maps
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
            
            fetch('<?= base_url('bookmark/toggle') ?>', {
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
            fetch(`<?= base_url('bookmark/check-status/') ?>${wisataId}`)
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