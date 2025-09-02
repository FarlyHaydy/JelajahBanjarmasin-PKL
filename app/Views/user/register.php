<?php 
// Menampilkan pesan sukses/error jika ada
$session = session();
?>
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

            <!-- Form Register -->
            <form action="<?= base_url('/registerAction') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" 
                           placeholder="Masukkan Username Anda" 
                           value="<?= old('username') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nama_asli">Nama Asli</label>
                    <input type="text" name="nama_asli" id="nama_asli" class="form-control" 
                           placeholder="Masukkan Nama Asli Anda" 
                           value="<?= old('nama_asli') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                           placeholder="Masukkan Email Anda" 
                           value="<?= old('email') ?>" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" 
                           placeholder="Masukkan Alamat Anda" 
                           value="<?= old('alamat') ?>" required>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki" <?= old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                        <option value="Perempuan" <?= old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="tel" name="telepon" id="telepon" class="form-control" 
                           placeholder="Masukkan nomor telepon Anda" 
                           value="<?= old('telepon') ?>" required>
                </div>

                <!-- Kota dan Kecamatan dalam 1 baris (dinamik via JSON) -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="kota" class="form-label">Kota / Kabupaten</label>
                            <select name="kota" id="kota" class="form-select" required>
                                <option value="">Pilih Kota/Kabupaten</option>
                                <!-- Diisi dinamik dari JSON -->
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-select" required>
                                <option value="">Pilih Kecamatan</option>
                                <!-- Diisi dinamik berdasarkan kota terpilih -->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-container" style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="Masukkan Password Anda" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">Password minimal 6 karakter</small>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="password-input-container" style="position: relative;">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" 
                               placeholder="Konfirmasi Password Anda" required>
                        <button type="button" class="password-toggle" onclick="toggleConfirmPassword()" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
                            <i class="fas fa-eye" id="confirm-password-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">Daftar</button>

                <div class="register-link">
                    Sudah punya akun? <a href="/login">Login Sekarang</a>
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
                        <li><a href="#">Support Centre</a></li>
                        <li><a href="#">Feedback</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/login.js') ?>"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordEye = document.getElementById('password-eye');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordEye.classList.remove('fa-eye');
                passwordEye.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordEye.classList.remove('fa-eye-slash');
                passwordEye.classList.add('fa-eye');
            }
        }
        function toggleConfirmPassword() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const confirmPasswordEye = document.getElementById('confirm-password-eye');
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmPasswordEye.classList.remove('fa-eye');
                confirmPasswordEye.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                confirmPasswordEye.classList.remove('fa-eye-slash');
                confirmPasswordEye.classList.add('fa-eye');
            }
        }

        // ================================
        // Dinamik Kota & Kecamatan (via JSON)
        // ================================
        const DATA_URL   = "<?= base_url('data/kota_kecamatan.json') ?>"; // letak file JSON
        const kotaSelect = document.getElementById('kota');
        const kecSelect  = document.getElementById('kecamatan');

        // Ambil nilai lama (agar repopulate)
        const OLD_KOTA = "<?= esc(old('kota')) ?>";
        const OLD_KEC  = "<?= esc(old('kecamatan')) ?>";

        // Helper: isi <select>
        function fillSelect(selectEl, items, placeholder) {
            selectEl.innerHTML = '';
            const opt0 = document.createElement('option');
            opt0.value = '';
            opt0.textContent = placeholder || 'Pilih';
            selectEl.appendChild(opt0);

            items.forEach(({ value, label }) => {
                const opt = document.createElement('option');
                opt.value = value;
                opt.textContent = label;
                selectEl.appendChild(opt);
            });
        }

        function fillKecamatan(kotaKey, dataJSON, preselect = '') {
            const list = dataJSON[kotaKey] || [];
            const kecItems = list
                .slice()
                .sort((a, b) => a.localeCompare(b, 'id'))
                .map(n => ({ value: n, label: n }));
            fillSelect(kecSelect, kecItems, 'Pilih Kecamatan');
            if (preselect && list.includes(preselect)) {
                kecSelect.value = preselect;
            }
        }

        let _cacheData = {};

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const res = await fetch(DATA_URL, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Gagal memuat data wilayah');
                _cacheData = await res.json();
            } catch (e) {
                console.error(e);
                _cacheData = {};
            }

            // Isi daftar Kota/Kab
            const kotaItems = Object.keys(_cacheData)
                .sort((a, b) => a.localeCompare(b, 'id'))
                .map(name => ({ value: name, label: name }));
            fillSelect(kotaSelect, kotaItems, 'Pilih Kota/Kabupaten');

            // Repopulate jika ada old() dari server
            if (OLD_KOTA && _cacheData[OLD_KOTA]) {
                kotaSelect.value = OLD_KOTA;
                fillKecamatan(OLD_KOTA, _cacheData, OLD_KEC);
            } else {
                fillSelect(kecSelect, [], 'Pilih Kecamatan');
            }

            // Saat kota berubah → isi kecamatan
            kotaSelect.addEventListener('change', () => {
                const kota = kotaSelect.value;
                fillKecamatan(kota, _cacheData, '');
            });
        });

        // Validasi form dasar
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return;
            }
        });
    </script>
</body>
</html>
