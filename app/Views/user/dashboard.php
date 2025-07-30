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
            <a class="navbar-brand" href="#">Banjarmasin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#rekreasi">Rekreasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kuliner">Kuliner</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#religi">Religi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profil">Profil</a>
                    </li>
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
                    <div class="destination-card large pasar-terapung">
                        <div class="card-content">
                            <h3>Pasar Terapung</h3>
                            <p> Pasar Terapung tradisional yang berlokasi di siring</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large musik-panting">
                        <div class="card-content">
                            <h3>Musik Panting</h3>
                            <p>Seni tradisional Kalimantan Selatan yang memukau dengan harmonisasi musik dan cerita</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large masjid-sabilal">
                        <div class="card-content">
                            <h3>Masjid Sabilal Muhtadin</h3>
                            <p>Masjid raya yang menjadi kebanggaan masyarakat Banjarmasin dengan arsitektur yang megah</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large menara-pandang">
                        <div class="card-content">
                            <h3>Menara Pandang</h3>
                            <p>Menara yang menawarkan pemandangan kota Banjarmasin dari ketinggian</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large masjid-sultan">
                        <div class="card-content">
                            <h3>Masjid Sultan Suriansyah</h3>
                            <p>Masjid tertua di Kalimantan Selatan dengan nilai sejarah yang tinggi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card large pulau-kembang">
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
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Facebook</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Support Centre</a></li>
                        <li><a href="#">Feedback</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/dashboard.js') ?>"></script>
    
</body>
</html>