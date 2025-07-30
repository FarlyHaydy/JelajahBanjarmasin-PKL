<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Banjarmasin</a>
        </div>
    </nav>

    <!-- Login Section -->
    <div class="login-container">
        <div class="login-form">
            <h2>Masuk Akun</h2>
            <form>
                <div class="form-group">
                    <label for="email">Email / Username</label>
                    <input type="text" id="email" class="form-control" placeholder="Masukkan Email atau Username Anda">
                </div>
                
                <div class="form-group">
                    <label for="password">Sandi</label>
                    <input type="password" id="password" class="form-control" placeholder="Masukkan Sandi Anda">
                </div>
                
                <div class="forgot-password">
                    <a href="#">Lupa Sandi?</a>
                </div>
                
                <button type="submit" class="btn-login">Masuk</button>
                
                <div class="register-link">
                    Belum Punya Akun? <a href="#">Daftar Sekarang</a>
                </div>
            </form>
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
    <script src="<?= base_url('js/login.js') ?>"></script>
</body>
</html>