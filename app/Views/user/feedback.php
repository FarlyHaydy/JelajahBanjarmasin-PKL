<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/feedback.css') ?>">

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
    
 <main class="container">
    <h2>Bantu kami jadi lebih baik!</h2>
    <p class="description">
      Masukanmu sangat berarti untuk meningkatkan kualitas informasi dan layanan kami.
      Mohon luangkan sedikit waktu untuk mengisi survei singkat ini.
    </p>

    <form>
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <label for="nama" class="form-label">Nama</label>
          <input type="text" class="form-control" id="nama" value="M. Adi Syahputra" />
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" value="2210817210017@mhs.ulm.ac.id" />
        </div>
        <div class="col-md-6">
          <label for="telepon" class="form-label">Nomor telepon</label>
          <input type="tel" class="form-control" id="telepon" value="081224648255" />
        </div>
        <div class="col-md-6">
          <label for="asal" class="form-label">Asal</label>
          <input type="text" class="form-control" id="asal" value="Banjarmasin" />
        </div>
      </div>

      <div class="feedback-section">
        <h4 class="mb-5 text-center">Nilai layanan kami</h4>

        <div class="mb-4">
          <label class="form-label fw-semibold">Seberapa mudah kamu menemukan informasi yang kamu cari di website ini?</label>
          <div class="rating-stars" aria-label="Rating ease of finding information">
            <span>★</span><span>★</span><span>★</span><span>★</span><span class="star-gray">★</span>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Apakah tampilan dan navigasi website ini nyaman digunakan?</label>
          <div class="rating-stars" aria-label="Rating comfort of website display and navigation">
            <span>★</span><span>★</span><span>★</span><span class="star-gray">★</span><span class="star-gray">★</span>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Apakah informasi yang disajikan terasa lengkap dan akurat?</label>
          <div class="rating-stars" aria-label="Rating completeness and accuracy of information">
            <span>★</span><span>★</span><span>★</span><span class="star-gray">★</span><span class="star-gray">★</span>
          </div>
        </div>

        <div class="mb-4">
          <label for="feedback" class="form-label">Feedback tambahan</label>
          <textarea id="feedback" class="form-control" placeholder="Baik dan sudah cukup..."></textarea>
        </div>

        <button type="submit" class="btn btn-submit">Kirim</button>
      </div>
    </form>
  </main>
    

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