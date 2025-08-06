<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/FAQ.css') ?>">

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
    

    <section class="faq-section">
    <div class="container">
        <h2 class="faq-title">Pertanyaan yang sering ditanyakan</h2>
        
        <div class="faq-container">
            <div class="accordion" id="faqAccordion">
                
                <!-- FAQ Item 1 -->
                <div class="faq-item">
                    <button class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
                        <span>Bagaimana cara menyimpan Destinasi Wisata</span>
                        <i class="fas fa-chevron-right faq-arrow"></i>
                    </button>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="faq-content">
                                Anda dapat menyimpan destinasi wisata favorit dengan cara login ke akun Anda, kemudian klik ikon bookmark atau simpan pada halaman destinasi yang ingin Anda simpan. Destinasi yang tersimpan dapat diakses melalui menu "Destinasi Tersimpan" di profil Anda.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item">
                    <button class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                        <span>Bagaimana cara menyimpan Destinasi Wisata</span>
                        <i class="fas fa-chevron-right faq-arrow"></i>
                    </button>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="faq-content">
                                Untuk menyimpan destinasi wisata, pastikan Anda sudah memiliki akun dan dalam keadaan login. Setelah itu, buka halaman destinasi wisata yang ingin disimpan dan klik tombol "Simpan" atau ikon hati yang tersedia. Destinasi tersebut akan otomatis tersimpan dalam daftar favorit Anda.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item">
                    <button class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                        <span>Bagaimana cara menyimpan Destinasi Wisata</span>
                        <i class="fas fa-chevron-right faq-arrow"></i>
                    </button>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="faq-content">
                                Sistem penyimpanan destinasi wisata memungkinkan Anda untuk mengorganisir tempat-tempat yang ingin dikunjungi. Cukup klik tombol simpan pada setiap destinasi, dan Anda dapat mengaksesnya kapan saja melalui menu profil. Fitur ini sangat berguna untuk merencanakan perjalanan wisata Anda di Banjarmasin.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item">
                    <button class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                        <span>Bagaimana cara menyimpan Destinasi Wisata</span>
                        <i class="fas fa-chevron-right faq-arrow"></i>
                    </button>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="faq-content">
                                Menyimpan destinasi wisata sangat mudah dilakukan. Setelah menemukan destinasi yang menarik, klik ikon bookmark atau tombol "Tambah ke Favorit". Semua destinasi yang disimpan akan tampil dalam dashboard pribadi Anda dan dapat diakses tanpa perlu mencari ulang.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item">
                    <button class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                        <span>Bagaimana cara menyimpan Destinasi Wisata</span>
                        <i class="fas fa-chevron-right faq-arrow"></i>
                    </button>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="faq-content">
                                Fitur penyimpanan destinasi dirancang untuk memberikan kemudahan dalam merencanakan wisata. Dengan menyimpan destinasi favorit, Anda dapat membuat itinerary perjalanan yang lebih terorganisir dan tidak akan kehilangan informasi tentang tempat-tempat menarik yang ingin dikunjungi di Banjarmasin.
                            </div>
                        </div>
                    </div>
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
    <script src="<?= base_url('js/dashboard.js') ?>"></script>
    
</body>
</html>