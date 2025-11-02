<?php 
// Menampilkan pesan sukses/error jika ada
$session = session();

// $kotaKecamatan harus dikirim dari controller sebagai:
// [ "Nama Kota/Kab" => ["Kecamatan 1","Kecamatan 2", ...], ... ]
$kotaKecamatan = $kotaKecamatan ?? [];
$kotaKeys = array_keys($kotaKecamatan);

// nilai lama dari input (untuk repopulate setelah validasi)
$OLD_KOTA = old('kota');
$OLD_KEC  = old('kecamatan');
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        /* Styling untuk Select2 agar konsisten dengan form-control */
        .select2-container--default .select2-selection--single {
            height: 45px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
            color: #495057 !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px !important;
            right: 10px !important;
        }
        
        .select2-container {
            width: 100% !important;
        }

        /* Hover dan focus state */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default .select2-selection--single:focus {
            border-color: #86b7fe !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }
    </style>
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

                <!-- Kota/Kabupaten dan Kecamatan dalam 1 baris -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kota" class="form-label">Kota / Kabupaten</label>
                            <select name="kota" id="kota" class="form-select" required>
                                <option value="">Pilih Kota/Kabupaten</option>
                                <?php foreach ($kotaKeys as $k): ?>
                                    <option value="<?= esc($k) ?>" <?= ($OLD_KOTA === $k ? 'selected' : '') ?>>
                                        <?= esc($k) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-select" required>
                                <option value="">Pilih Kecamatan</option>
                                <?php
                                  // Jika ada OLD_KOTA yang valid di dataset, render list kecamatannya
                                  if ($OLD_KOTA && isset($kotaKecamatan[$OLD_KOTA]) && is_array($kotaKecamatan[$OLD_KOTA])) {
                                      foreach ($kotaKecamatan[$OLD_KOTA] as $kc) {
                                          $sel = ($OLD_KEC === $kc) ? 'selected' : '';
                                          echo '<option value="'.esc($kc).'" '.$sel.'>'.esc($kc).'</option>';
                                      }
                                  }
                                ?>
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
            </div>
        </div>
    </footer>

    <!-- Script -->
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
        // PRELOAD Kota & Kecamatan (tanpa fetch) 
        // ================================
        const KOTA_KECAMATAN = <?= json_encode($kotaKecamatan, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
        const OLD_KOTA = <?= json_encode($OLD_KOTA, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
        const OLD_KEC  = <?= json_encode($OLD_KEC,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

        const kotaSelect = document.getElementById('kota');
        const kecSelect  = document.getElementById('kecamatan');

        function fillKecamatanOptions(kota, selectedKec) {
            // Destroy Select2 dulu sebelum mengubah options
            $('#kecamatan').select2('destroy');
            
            kecSelect.innerHTML = '';
            const opt0 = document.createElement('option');
            opt0.value = '';
            opt0.textContent = 'Pilih Kecamatan';
            kecSelect.appendChild(opt0);

            const list = Array.isArray(KOTA_KECAMATAN[kota]) ? KOTA_KECAMATAN[kota] : [];
            list.forEach(kc => {
                const opt = document.createElement('option');
                opt.value = kc;
                opt.textContent = kc;
                if (selectedKec && selectedKec === kc) opt.selected = true;
                kecSelect.appendChild(opt);
            });

            // Inisialisasi Select2 kembali setelah mengisi kecamatan
            $('#kecamatan').select2({
                placeholder: "Pilih Kecamatan",
                allowClear: true,
            });
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi Select2 untuk dropdown Kota
            $('#kota').select2({
                placeholder: "Pilih Kota/Kabupaten",
                allowClear: true,
            });

            // Inisialisasi Select2 untuk dropdown Kecamatan
            $('#kecamatan').select2({
                placeholder: "Pilih Kecamatan",
                allowClear: true,
            });

            // Jika OLD_KOTA ada, isi kecamatan-nya
            if (OLD_KOTA && KOTA_KECAMATAN[OLD_KOTA]) {
                fillKecamatanOptions(OLD_KOTA, OLD_KEC || '');
            }
        });

        // Saat kota berubah → isi kecamatan sesuai pilihan
        $('#kota').on('change', function() {
            const kota = $(this).val();
            if (kota) {
                fillKecamatanOptions(kota, '');
            } else {
                // Jika kota dikosongkan, reset kecamatan
                $('#kecamatan').select2('destroy');
                kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                $('#kecamatan').select2({
                    placeholder: "Pilih Kecamatan",
                    allowClear: true,
                });
            }
        });
    </script>

</body>
</html>