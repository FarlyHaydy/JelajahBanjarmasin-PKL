<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="<?= base_url('css/register.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Banjarmasin</a>
        </div>
    </nav>

    <!-- Regis Section -->
    <div class="login-container">
        <div class="login-form">
            <h2>Daftar Akun</h2>
            <form>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" placeholder="Masukkan Username Anda">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" class="form-control" placeholder="Masukkan Email Anda">
                </div>

                <div class="form-group">
                    <label for="email">Alamat</label>
                    <input type="text" id="alamat" class="form-control" placeholder="Masukkan Alamat Anda">
                </div>

                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="tel" id="telepon" class="form-control" placeholder="Masukkan nomor telepon anda">
                </div>

                <!-- Kota dan Kecamatan dalam 1 baris (masih contoh) -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="kota" class="form-label">Kota</label>
                            <select id="kota" class="form-select">
                                <option selected>Banjarmasin</option>
                                <option value="banjarmasin">Banjarmasin</option>
                                <option value="banjarbaru">Banjarbaru</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <select id="kecamatan" class="form-select">
                                <option selected>Banjarmasin Utara</option>
                                <option value="banjarmasin-utara">Banjarmasin Utara</option>
                                <option value="banjarmasin-selatan">Banjarmasin Selatan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="">
                </div>
                
                <button type="submit" class="btn-login">Daftar</button>
                
                <div class="register-link">
                    Sudah punya akun? <a href="#">Login Sekarang</a>
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