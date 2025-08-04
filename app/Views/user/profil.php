<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Akun Pengguna</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/profil.css') ?>">
</head>
<body>
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="main-container">
        <!-- Left Sidebar -->
        <div class="left-sidebar" id="sidebar">
            <div class="profile-header">
                <!-- Back Button -->
                <button class="back-btn" onclick="history.back()">
                    <i class="fas fa-chevron-left"></i>
                    <span>Profil</span>
                </button>

                <!-- User Info -->
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-name">Akun Pengguna</div>
                </div>

                <!-- Bookmark -->
                <div class="bookmark-section">
                    <div class="bookmark-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <span class="bookmark-text">Bookmark</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="profile-form-container">
                <h1 class="profile-title">Akun Pengguna</h1>
                
                <form id="profileForm">
                    <!-- Email/Username -->
                    <div class="form-group">
                        <label class="form-label">Email / Username</label>
                        <input type="text" class="form-control" placeholder="M. Adi Syahputra" value="M. Adi Syahputra">
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" placeholder="Sungai Andai" value="Sungai Andai">
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" placeholder="081224648256" value="081224648256">
                    </div>

                    <!-- Kota dan Kecamatan -->
                    <div class="row-fields">
                        <div class="form-group">
                            <label class="form-label">Kota</label>
                            <input type="text" class="form-control" placeholder="Banjarmasin" value="Banjarmasin">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" placeholder="Banjarmasin Utara" value="Banjarmasin Utara">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="password" placeholder="••••••••••" value="password123">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <button type="submit" class="save-btn">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/profil.js') ?>"></script>
    </script>
</body>
</html>