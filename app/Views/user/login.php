<?php 
// Menampilkan pesan sukses/error jika ada
$session = session();
?>
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

            <!-- Menampilkan pesan sukses/error -->
            <?php if ($session->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $session->getFlashdata('success') ?>
                </div>
            <?php elseif ($session->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $session->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form action="<?= base_url('/loginAction') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email">Email / Username</label>
                    <input type="text" name="email" id="email" class="form-control" 
                           placeholder="Masukkan Email atau Username Anda" 
                           value="<?= old('email') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Masukkan Password Anda" required>
                </div>
                
                <div class="forgot-password">
                    <a href="#">Lupa Password?</a>
                </div>
                
                <button type="submit" class="btn-login">Masuk</button>
                <div class="register-link">
                    Belum Punya Akun? <a href="/register">Daftar Sekarang</a>
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
                        <li><a href="<?= base_url('/faq') ?>"><i class="fas fa-question-circle"></i> FAQs</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/login.js') ?>"></script>
</body>
</html>