<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/rekreasi.css') ?>">

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
    
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="main-title text-center mb-5">Rekreasi Banjarmasin</h1>
                
                <!-- Card 1: Menara Pandang - Image Left, Text Right -->
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="card-image menara-pandang">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-content">
                            <h3 class="card-title">Menara Pandang</h3>
                            <p class="card-description">
                                Arabic is the official language of Saudi Arabia and the primary language used in all dealings and public transactions. English serves as an informal second language in the Kingdom and is spoken by a large section of its society. All road signs are bilingual, showing information in both Arabic and English.
                            </p>
                            <div class="card-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Musik Panting - Text Left, Image Right -->
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="card-image musik-panting">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="card-content">
                            <h3 class="card-title">Musik Panting</h3>
                            <p class="card-description">
                                Arabic is the official language of Saudi Arabia and the primary language used in all dealings and public transactions. English serves as an informal second language in the Kingdom and is spoken by a large section of its society. All road signs are bilingual, showing information in both Arabic and English.
                            </p>
                            <div class="card-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pasar Terapung - Image Left, Text Right -->
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="card-image pasar-terapung">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-content">
                            <h3 class="card-title">Pasar Terapung</h3>
                            <p class="card-description">
                                Arabic is the official language of Saudi Arabia and the primary language used in all dealings and public transactions. English serves as an informal second language in the Kingdom and is spoken by a large section of its society. All road signs are bilingual, showing information in both Arabic and English.
                            </p>
                            <div class="card-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Masjid Sultan Suriansyah - Text Left, Image Right -->
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="card-image masjid-sultan">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="card-content">
                            <h3 class="card-title">Masjid Sultan Suriansyah</h3>
                            <p class="card-description">
                                Arabic is the official language of Saudi Arabia and the primary language used in all dealings and public transactions. English serves as an informal second language in the Kingdom and is spoken by a large section of its society. All road signs are bilingual, showing information in both Arabic and English.
                            </p>
                            <div class="card-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Jelajah Banjarmasin - Wide Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="wide-card">
                            <div class="wide-card-content">
                                <h3 class="wide-card-title">Jelajah<br>Banjarmasin</h3>
                                <div class="wide-card-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

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
    <script src="<?= base_url('js/dashboard.js') ?>"></script>
    
</body>
</html>