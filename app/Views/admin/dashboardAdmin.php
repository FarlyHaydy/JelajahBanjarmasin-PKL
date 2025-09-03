
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Banjarmasin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
  body {
    background-color: #f8f9fa;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  }
  .sidebar {
    background-color: #343a40;
    min-height: 100vh;
    padding: 1rem;
  }
  .sidebar .nav-link {
    color: #ffffff;
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
  }
  .sidebar .nav-link:hover,
  .sidebar .nav-link.active {
    background-color: #0d6efd;
    color: #ffffff;
  }
  .main-content {
    background-color: #ffffff;
    min-height: 100vh;
    padding: 2rem;
  }
  .content-section {
    display: none;
  }
  .content-section.active {
    display: block;
  }
  .card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
  }
  .table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
  }
  .btn-action {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
  }
  .coordinate-help {
    background-color: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 4px;
    padding: 10px;
    margin-top: 10px;
  }
  .coordinate-help small {
    color: #1976d2;
  }
  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
  }
  .status-badge {
    font-size: 0.75rem;
  }
  .stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
  }
  .stats-card .card-body {
    padding: 1.5rem;
  }
  .stats-number {
    font-size: 2rem;
    font-weight: bold;
  }
  .chart-container {
    position: relative;
    height: 400px;
    margin-bottom: 2rem;
  }
  .export-section {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1.5rem;
  }
  .chart-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
</style>

</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-white mb-4">
                    <i class="fas fa-user-shield me-2"></i> JelajahBanjarmasin Admin
                </h4>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" onclick="showSection('create')"><i class="fas fa-plus"></i> Buat Postingan</a>
                    <a class="nav-link" href="#" onclick="showSection('edit')"><i class="fas fa-edit"></i> Edit Postingan</a>
                    <a class="nav-link" href="#" onclick="showSection('delete')"><i class="fas fa-trash"></i> Hapus Postingan</a>
                    <a class="nav-link" href="#" onclick="showSection('master')"><i class="fas fa-database"></i> Master Data</a>
                    <a class="nav-link" href="#" onclick="showSection('users')"><i class="fas fa-users"></i> Data Pengguna</a>
                    <a class="nav-link" href="#" onclick="showSection('statistics')"><i class="fas fa-chart-bar"></i> Statistik</a>
                    <hr class="text-white">
                    <a class="nav-link" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Flash Messages -->
                <?php $session = session(); ?>
                <?php if ($session->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $session->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($session->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $session->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Buat Postingan Section -->
                <div id="create-section" class="content-section active">
                    <div class="card">
                        <div class="card-header"><h5><i class="fas fa-plus"></i> Buat Postingan Wisata Baru</h5></div>
                        <div class="card-body">
                            <form action="<?= base_url('admin/wisata/create') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Destinasi</label>
                                        <input type="text" name="nama_wisata" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select name="kategori_id" class="form-select" required>
                                            <option value="">Pilih Kategori</option>
                                            <?php if (isset($kategori)): foreach ($kategori as $kat): ?>
                                                <option value="<?= $kat['kategori_id'] ?>"><?= $kat['nama_kategori'] ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kota</label>
                                        <select name="kota_id" id="kotaSelect" class="form-select" required>
                                            <option value="">Pilih Kota</option>
                                            <?php if (isset($kota)): foreach ($kota as $k): ?>
                                                <option value="<?= $k['kota_id'] ?>"><?= $k['nama_kota'] ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <select name="kecamatan_id" id="kecamatanSelect" class="form-select" required>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Detail Lengkap</label>
                                    <textarea name="detail" class="form-control" rows="4" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fas fa-map-marker-alt text-primary"></i> Latitude (Opsional)</label>
                                        <input type="number" step="any" name="latitude" class="form-control" placeholder="Contoh: -3.316694">
                                        <small class="text-muted">Untuk peta yang lebih akurat (Range: -90 sampai 90)</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fas fa-map-marker-alt text-primary"></i> Longitude (Opsional)</label>
                                        <input type="number" step="any" name="longitude" class="form-control" placeholder="Contoh: 114.590111">
                                        <small class="text-muted">Untuk peta yang lebih akurat (Range: -180 sampai 180)</small>
                                    </div>
                                </div>

                                <div class="coordinate-help">
                                    <h6><i class="fas fa-info-circle"></i> Cara Mendapatkan Koordinat:</h6>
                                    <small>
                                        1. Buka <strong>Google Maps</strong> di browser<br>
                                        2. Cari lokasi destinasi wisata<br>
                                        3. <strong>Klik kanan</strong> pada titik yang tepat<br>
                                        4. Copy koordinat yang muncul (contoh: -3.316694, 114.590111)<br>
                                        5. Masukkan angka pertama ke <strong>Latitude</strong>, angka kedua ke <strong>Longitude</strong>
                                    </small>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="form-label">Gambar (Maksimal 5)</label>
                                    <input type="file" name="gambar[]" class="form-control" multiple accept="image/*" required>
                                </div>

                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Postingan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Postingan Section -->
                <div id="edit-section" class="content-section">
                    <div class="card">
                        <div class="card-header"><h5><i class="fas fa-edit"></i> Edit Postingan Wisata</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Wisata</th>
                                            <th>Kategori</th>
                                            <th>Lokasi</th>
                                            <th>Koordinat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($wisata) && !empty($wisata)): $no = 1; foreach ($wisata as $w): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><strong><?= esc($w['nama_wisata'] ?? 'N/A') ?></strong></td>
                                                <td><span class="badge bg-primary"><?= esc($w['nama_kategori'] ?? 'N/A') ?></span></td>
                                                <td><?= esc($w['nama_kecamatan'] ?? 'N/A') ?>, <?= esc($w['nama_kota'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?php if (!empty($w['latitude']) && !empty($w['longitude'])): ?>
                                                        <span class="badge bg-success"><i class="fas fa-map-marker-alt"></i> Ada</span><br>
                                                        <small class="text-muted"><?= number_format($w['latitude'], 6) ?>, <?= number_format($w['longitude'], 6) ?></small>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning"><i class="fas fa-map-marker"></i> Belum</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm btn-action" onclick="editWisata(<?= $w['wisata_id'] ?? 0 ?>)"><i class="fas fa-edit"></i> Edit</button>
                                                    <a href="<?= base_url('detail/' . ($w['wisata_id'] ?? 0)) ?>" class="btn btn-info btn-sm btn-action" target="_blank"><i class="fas fa-eye"></i> Lihat</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr><td colspan="6" class="text-center"><div class="alert alert-info">Belum ada data wisata. <a href="#" onclick="showSection('create')">Buat postingan baru</a></div></td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hapus Postingan Section -->
                <div id="delete-section" class="content-section">
                    <div class="card">
                        <div class="card-header"><h5><i class="fas fa-trash"></i> Hapus Postingan Wisata</h5></div>
                        <div class="card-body">
                            <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <strong>Peringatan:</strong> Menghapus postingan akan menghilangkan data secara permanen.</div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Wisata</th>
                                            <th>Kategori</th>
                                            <th>Lokasi</th>
                                            <th>Koordinat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($wisata) && !empty($wisata)): $no = 1; foreach ($wisata as $w): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <strong><?= esc($w['nama_wisata'] ?? 'N/A') ?></strong><br>
                                                    <small class="text-muted"><?= substr(esc($w['deskripsi'] ?? ''), 0, 50) ?>...</small>
                                                </td>
                                                <td><span class="badge bg-primary"><?= esc($w['nama_kategori'] ?? 'N/A') ?></span></td>
                                                <td><?= esc($w['nama_kecamatan'] ?? 'N/A') ?>, <?= esc($w['nama_kota'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?php if (!empty($w['latitude']) && !empty($w['longitude'])): ?>
                                                        <span class="badge bg-success"><i class="fas fa-map-marker-alt"></i> Presisi</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning"><i class="fas fa-map-marker"></i> Alamat</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm btn-action" onclick="deleteWisata(<?= $w['wisata_id'] ?? 0 ?>, '<?= esc($w['nama_wisata'] ?? '') ?>')"><i class="fas fa-trash"></i> Hapus</button>
                                                    <a href="<?= base_url('detail/' . ($w['wisata_id'] ?? 0)) ?>" class="btn btn-outline-info btn-sm btn-action" target="_blank"><i class="fas fa-eye"></i> Preview</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr><td colspan="6" class="text-center"><div class="alert alert-info">Belum ada data wisata untuk dihapus.</div></td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Master Data Section -->
                <div id="master-section" class="content-section">
                    <div class="row">
                        <!-- Kategori -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header"><h6><i class="fas fa-tags"></i> Kategori</h6></div>
                                <div class="card-body">
                                    <form action="<?= base_url('admin/kategori/create') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </form>
                                    <?php if (isset($kategori) && !empty($kategori)): ?>
                                        <div class="list-group">
                                            <?php foreach ($kategori as $kat): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= esc($kat['nama_kategori']) ?>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteMaster('kategori', <?= $kat['kategori_id'] ?>, '<?= esc($kat['nama_kategori']) ?>')"><i class="fas fa-trash"></i></button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kota -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header"><h6><i class="fas fa-city"></i> Kota</h6></div>
                                <div class="card-body">
                                    <form action="<?= base_url('admin/kota/create') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_kota" class="form-control" placeholder="Nama Kota" required>
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </form>
                                    <?php if (isset($kota) && !empty($kota)): ?>
                                        <div class="list-group">
                                            <?php foreach ($kota as $k): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= esc($k['nama_kota']) ?>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteMaster('kota', <?= $k['kota_id'] ?>, '<?= esc($k['nama_kota']) ?>')"><i class="fas fa-trash"></i></button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kecamatan -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header"><h6><i class="fas fa-map-marker-alt"></i> Kecamatan</h6></div>
                                <div class="card-body">
                                    <form action="<?= base_url('admin/kecamatan/create') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <select name="kota_id" class="form-select mb-2" required>
                                            <option value="">Pilih Kota</option>
                                            <?php if (isset($kota)): foreach ($kota as $k): ?>
                                                <option value="<?= $k['kota_id'] ?>"><?= $k['nama_kota'] ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_kecamatan" class="form-control" placeholder="Nama Kecamatan" required>
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </form>
                                    <?php if (isset($kecamatan) && !empty($kecamatan)): ?>
                                        <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                                            <?php foreach ($kecamatan as $kec): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= esc($kec['nama_kecamatan']) ?></strong><br>
                                                        <small class="text-muted"><?= esc($kec['nama_kota']) ?></small>
                                                    </div>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteMaster('kecamatan', <?= $kec['kecamatan_id'] ?>, '<?= esc($kec['nama_kecamatan']) ?>')"><i class="fas fa-trash"></i></button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pengguna Section -->
                <div id="users-section" class="content-section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-users"></i> Data Pengguna Terdaftar</h5>
                            <div class="export-section">
                                <button class="btn btn-danger" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Export PDF</button>
                                <button class="btn btn-success" onclick="exportToExcel()"><i class="fas fa-file-excel"></i> Export Excel</button>
                            </div>
                        </div>
                        <div class="card-body">

                            <?php
                            // === Kartu Statistik Baru ===
                            $total_users = isset($users) ? count($users) : 0;
                            $new_users = 0; $male_users = 0; $female_users = 0;
                            if (isset($users)) {
                                $last_month = date('Y-m-d', strtotime('-30 days'));
                                foreach ($users as $u) {
                                    if (!empty($u['created_at']) && $u['created_at'] >= $last_month) { $new_users++; }
                                    if (($u['Jenis_Kelamin'] ?? '') === 'Laki-Laki') { $male_users++; }
                                    if (($u['Jenis_Kelamin'] ?? '') === 'Perempuan') { $female_users++; }
                                }
                            }
                            ?>

                            <!-- Stats Cards -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <div class="stats-number"><?= $total_users ?></div>
                                            <small>Total Pengguna</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                                            <div class="stats-number"><?= $new_users ?></div>
                                            <small>Baru (30 Hari)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-mars fa-2x mb-2"></i>
                                            <div class="stats-number"><?= $male_users ?></div>
                                            <small>Laki-Laki</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-venus fa-2x mb-2"></i>
                                            <div class="stats-number"><?= $female_users ?></div>
                                            <small>Perempuan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Search and Filter -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchUsers" placeholder="Cari nama, username, atau email...">
                                        <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <label class="form-label me-2 mb-0">Filter Gender:</label>
                                        <select class="form-select w-auto" id="filterGender">
                                            <option value="">Semua</option>
                                            <option value="Laki-Laki">Laki-Laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Users Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Avatar</th>
                                            <th>Informasi Pengguna</th>
                                            <th>Kontak</th>
                                            <th>Lokasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($users) && !empty($users)): $no = 1; ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr data-gender="<?= esc($user['Jenis_Kelamin']) ?>">
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white">
                                                            <?= strtoupper(substr($user['Nama_Asli'], 0, 1)) ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong><?= esc($user['Nama_Asli']) ?></strong><br>
                                                            <small class="text-muted">@<?= esc($user['Username']) ?></small><br>
                                                            <span class="badge bg-<?= ($user['Jenis_Kelamin'] === 'Laki-Laki') ? 'info' : 'pink' ?> status-badge">
                                                                <i class="fas fa-<?= ($user['Jenis_Kelamin'] === 'Laki-Laki') ? 'mars' : 'venus' ?>"></i>
                                                                <?= esc($user['Jenis_Kelamin']) ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <i class="fas fa-envelope text-muted"></i> <small><?= esc($user['Email']) ?></small><br>
                                                            <i class="fas fa-phone text-muted"></i> <small><?= esc($user['Nomor_Telepon']) ?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                                            <small><?= esc($user['Kecamatan'] ?? 'N/A') ?>,<br><?= esc($user['Kota'] ?? 'N/A') ?></small><br>
                                                            <small class="text-muted"><?= substr(esc($user['Alamat']), 0, 30) ?>...</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm btn-action" onclick="viewUser(<?= $user['id'] ?>)"><i class="fas fa-eye"></i> Detail</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="7" class="text-center"><div class="alert alert-info"><i class="fas fa-info-circle"></i> Belum ada pengguna terdaftar.</div></td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div id="statistics-section" class="content-section">
                    <h2><i class="fas fa-chart-bar text-primary"></i> Statistik Pengguna</h2>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Tipe Statistik</label>
                                    <select class="form-select" id="chartType" onchange="updateChart()">
                                        <option value="kota">Berdasarkan Kabupaten/Kota</option>
                                        <option value="kecamatan">Berdasarkan Kecamatan</option>
                                        <option value="gender">Berdasarkan Jenis Kelamin</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Chart</label>
                                    <select class="form-select" id="chartStyle" onchange="updateChart()">
                                        <option value="bar">Bar Chart</option>
                                        <option value="pie">Pie Chart</option>
                                        <option value="doughnut">Doughnut Chart</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" onclick="refreshChart()"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                                    <button class="btn btn-success" onclick="exportChart()"><i class="fas fa-download"></i> Export Chart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row"><div class="col-md-12">
                        <div class="card chart-card">
                            <div class="card-header"><h6 class="mb-0" id="chartTitle"><i class="fas fa-chart-bar"></i> Distribusi Pengguna per Kabupaten/Kota</h6></div>
                            <div class="card-body"><div class="chart-container"><canvas id="userStatsChart"></canvas></div></div>
                        </div>
                    </div></div>

                    <div class="card">
                        <div class="card-header"><h6><i class="fas fa-table"></i> Detail Statistik</h6></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="statsTable">
                                    <thead><tr id="statsTableHeader"><th>No</th><th>Kabupaten/Kota</th><th>Jumlah Pengguna</th><th>Persentase</th></tr></thead>
                                    <tbody id="statsTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /main-content -->
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Detail Pengguna</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white mx-auto" style="width: 100px; height: 100px; font-size: 2rem;" id="viewUserAvatar">A</div>
                            <h5 class="mt-3" id="viewUserName">Nama Pengguna</h5>
                            <p class="text-muted" id="viewUserUsername">@username</p>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr><td><strong>Email:</strong></td><td id="viewUserEmail">-</td></tr>
                                <tr><td><strong>Nomor Telepon:</strong></td><td id="viewUserPhone">-</td></tr>
                                <tr><td><strong>Jenis Kelamin:</strong></td><td id="viewUserGender">-</td></tr>
                                <tr><td><strong>Kabupaten/Kota:</strong></td><td id="viewUserCity">-</td></tr>
                                <tr><td><strong>Kecamatan:</strong></td><td id="viewUserDistrict">-</td></tr>
                                <tr><td><strong>Alamat:</strong></td><td id="viewUserAddress">-</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        let userChart = null;

        // Data pengguna (server -> JS)
        const realUsers = [
            <?php if (isset($users) && !empty($users)): ?>
                <?php foreach ($users as $index => $user): ?>
                    {
                        id: <?= $user['id'] ?>,
                        nama: "<?= esc($user['Nama_Asli']) ?>",
                        username: "<?= esc($user['Username']) ?>",
                        email: "<?= esc($user['Email']) ?>",
                        phone: "<?= esc($user['Nomor_Telepon']) ?>",
                        gender: "<?= esc($user['Jenis_Kelamin']) ?>",
                        kota: "<?= esc($user['Kota'] ?? 'N/A') ?>",
                        kecamatan: "<?= esc($user['Kecamatan'] ?? 'N/A') ?>",
                        alamat: "<?= esc($user['Alamat']) ?>",
                        created_at: "<?= $user['created_at'] ?? '' ?>"
                    }<?= $index < count($users) - 1 ? ',' : '' ?>
                <?php endforeach; ?>
            <?php endif; ?>
        ];

        // Navigasi section
        function showSection(sectionName) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            document.getElementById(sectionName + '-section').classList.add('active');
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            event.target.classList.add('active');
            if (sectionName === 'statistics') setTimeout(initializeChart, 100);
        }

        // ====== Statistik & Chart ======
        function getDataByKota() {
            const kotaCount = {};
            realUsers.forEach(u => { if (u.kota && u.kota !== 'N/A') kotaCount[u.kota] = (kotaCount[u.kota] || 0) + 1; });
            const total = Object.values(kotaCount).reduce((a,b)=>a+b,0);
            const chartData = { labels: Object.keys(kotaCount), datasets: [{ label:'Jumlah Pengguna', data:Object.values(kotaCount), backgroundColor:['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#C9CBCF'], borderColor:['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#C9CBCF'], borderWidth:1 }] };
            const tableData = Object.entries(kotaCount).map(([kota, count], i)=>({ no:i+1, name:kota, count, percentage: total? ((count/total)*100).toFixed(1)+'%':'0%' }));
            return { chartData, tableData };
        }
        function getDataByKecamatan() {
            const kecamatanCount = {};
            realUsers.forEach(u => { if (u.kecamatan && u.kecamatan!=='N/A' && u.kota && u.kota!=='N/A'){ const key=`${u.kecamatan}, ${u.kota}`; kecamatanCount[key]=(kecamatanCount[key]||0)+1; }});
            const total = Object.values(kecamatanCount).reduce((a,b)=>a+b,0);
            const chartData = { labels: Object.keys(kecamatanCount), datasets: [{ label:'Jumlah Pengguna', data:Object.values(kecamatanCount), backgroundColor:['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#C9CBCF','#4CAF50','#F44336','#2196F3','#FF9800'], borderColor:['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#C9CBCF','#4CAF50','#F44336','#2196F3','#FF9800'], borderWidth:1 }] };
            const tableData = Object.entries(kecamatanCount).map(([kec, count], i)=>({ no:i+1, name:kec, count, percentage: total? ((count/total)*100).toFixed(1)+'%':'0%' }));
            return { chartData, tableData };
        }
        function getDataByGender() {
            const genderCount = {};
            realUsers.forEach(u => { if (u.gender) genderCount[u.gender]=(genderCount[u.gender]||0)+1; });
            const total = Object.values(genderCount).reduce((a,b)=>a+b,0);
            const chartData = { labels:Object.keys(genderCount), datasets:[{ label:'Jumlah Pengguna', data:Object.values(genderCount), backgroundColor:['#36A2EB','#FF6384'], borderColor:['#36A2EB','#FF6384'], borderWidth:1 }] };
            const tableData = Object.entries(genderCount).map(([g, count], i)=>({ no:i+1, name:g, count, percentage: total? ((count/total)*100).toFixed(1)+'%':'0%' }));
            return { chartData, tableData };
        }
        function initializeChart(){ if (userChart) userChart.destroy(); updateChart(); }
        function updateChart(){
            const chartType = document.getElementById('chartType').value;
            const chartStyle = document.getElementById('chartStyle').value;
            let data, title, headers;
            if (chartType==='kota'){ data=getDataByKota(); title='Distribusi Pengguna per Kabupaten/Kota'; headers=['No','Kabupaten/Kota','Jumlah Pengguna','Persentase']; }
            else if (chartType==='kecamatan'){ data=getDataByKecamatan(); title='Distribusi Pengguna per Kecamatan'; headers=['No','Kecamatan','Jumlah Pengguna','Persentase']; }
            else { data=getDataByGender(); title='Distribusi Pengguna per Jenis Kelamin'; headers=['No','Jenis Kelamin','Jumlah Pengguna','Persentase']; }
            document.getElementById('chartTitle').innerHTML = `<i class="fas fa-chart-bar"></i> ${title}`;
            updateStatsTable(data.tableData, headers);
            createChart(data.chartData, chartStyle, title);
        }
        function createChart(data, style, title){
            const ctx = document.getElementById('userStatsChart').getContext('2d');
            if (userChart) userChart.destroy();
            const options = { responsive:true, maintainAspectRatio:false, plugins:{ title:{ display:true, text:title, font:{ size:16 } }, legend:{ display: style==='pie'||style==='doughnut', position:'right' } } };
            if (style==='bar'){ options.scales={ y:{ beginAtZero:true, ticks:{ stepSize:1 } } }; }
            userChart = new Chart(ctx, { type: style, data, options });
        }
        function updateStatsTable(data, headers){
            const headerRow = document.getElementById('statsTableHeader');
            const tbody = document.getElementById('statsTableBody');
            headerRow.innerHTML = headers.map(h=>`<th>${h}</th>`).join('');
            if (!data.length){ tbody.innerHTML = '<tr><td colspan="4" class="text-center"><div class="alert alert-info">Tidak ada data untuk ditampilkan</div></td></tr>'; return; }
            tbody.innerHTML = data.map(r=>`<tr><td>${r.no}</td><td><strong>${r.name}</strong></td><td><span class="badge bg-primary">${r.count}</span></td><td>${r.percentage}</td></tr>`).join('');
        }
        function refreshChart(){ updateChart(); alert('Data statistik berhasil diperbarui!'); }
        function exportChart(){
            const canvas = document.getElementById('userStatsChart');
            const link = document.createElement('a');
            link.download = `statistik-pengguna-${new Date().toISOString().split('T')[0]}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // Export PDF
        function exportToPDF(){
            if (!realUsers.length){ alert('Data pengguna kosong!'); return; }
            const { jsPDF } = window.jspdf; const doc = new jsPDF();
            doc.setFontSize(20); doc.text('Laporan Data Pengguna', 14, 22);
            doc.setFontSize(12); doc.text(`JelajahBanjarmasin - ${new Date().toLocaleDateString('id-ID')}`, 14, 30);
            const headers = ['No','Nama','Username','Email','Phone','Gender','Kota','Kecamatan'];
            const rows = realUsers.map((u,i)=>[i+1,u.nama,u.username,u.email,u.phone,u.gender,u.kota,u.kecamatan]);
            doc.autoTable({ head:[headers], body:rows, startY:40, styles:{ fontSize:8, cellPadding:2 }, headStyles:{ fillColor:[52,58,64], textColor:255 },
                columnStyles:{ 0:{cellWidth:10},1:{cellWidth:25},2:{cellWidth:20},3:{cellWidth:35},4:{cellWidth:25},5:{cellWidth:15},6:{cellWidth:25},7:{cellWidth:25} }
            });
            const finalY = doc.lastAutoTable.finalY + 10;
            doc.text(`Total Pengguna: ${realUsers.length}`, 14, finalY);
            doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 14, finalY + 7);
            doc.save(`data-pengguna-${new Date().toISOString().split('T')[0]}.pdf`);
            alert('File PDF berhasil diunduh!');
        }

        // Export Excel
        function exportToExcel(){
            if (!realUsers.length){ alert('Data pengguna kosong!'); return; }
            const data = realUsers.map((u,i)=>({ 'No':i+1, 'Nama Lengkap':u.nama, 'Username':u.username, 'Email':u.email, 'Nomor Telepon':u.phone, 'Jenis Kelamin':u.gender, 'Kabupaten/Kota':u.kota, 'Kecamatan':u.kecamatan, 'Alamat':u.alamat }));
            const ws = XLSX.utils.json_to_sheet(data); const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Data Pengguna"); XLSX.writeFile(wb, `data-pengguna-${new Date().toISOString().split('T')[0]}.xlsx`);
            alert('File Excel berhasil diunduh!');
        }

        // View user (modal)
        function viewUser(id){
            const user = realUsers.find(u => u.id == id);
            if (!user){ alert('Data pengguna tidak ditemukan'); return; }
            document.getElementById('viewUserAvatar').textContent = user.nama.charAt(0).toUpperCase();
            document.getElementById('viewUserName').textContent = user.nama;
            document.getElementById('viewUserUsername').textContent = '@' + user.username;
            document.getElementById('viewUserEmail').textContent = user.email;
            document.getElementById('viewUserPhone').textContent = user.phone;
            document.getElementById('viewUserGender').textContent = user.gender;
            document.getElementById('viewUserCity').textContent = user.kota;
            document.getElementById('viewUserDistrict').textContent = user.kecamatan;
            document.getElementById('viewUserAddress').textContent = user.alamat;
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        }

        // Search
        document.getElementById('searchUsers').addEventListener('input', function(){
            const q = this.value.toLowerCase();
            document.querySelectorAll('#usersTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // Filter Gender (robust via data-gender)
        document.getElementById('filterGender').addEventListener('change', function(){
            const val = this.value;
            document.querySelectorAll('#usersTable tbody tr').forEach(row => {
                const g = row.getAttribute('data-gender') || '';
                row.style.display = (val === '' || g === val) ? '' : 'none';
            });
        });

        // Wisata helpers
        document.getElementById('kotaSelect').addEventListener('change', function(){ loadKecamatan(this.value, 'kecamatanSelect'); });
        document.getElementById('editKota')?.addEventListener('change', function(){ loadKecamatan(this.value, 'editKecamatan'); });
        function loadKecamatan(kotaId, targetSelect){
            const kecSelect = document.getElementById(targetSelect);
            kecSelect.innerHTML = '<option value="">Loading...</option>';
            if (kotaId){
                fetch(`<?= base_url('admin/kecamatan/by-kota/') ?>${kotaId}`)
                    .then(r => r.json())
                    .then(data => {
                        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.forEach(k => { kecSelect.innerHTML += `<option value="${k.kecamatan_id}">${k.nama_kecamatan}</option>`; });
                    })
                    .catch(() => { kecSelect.innerHTML = '<option value="">Error loading</option>'; });
            } else {
                kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            }
        }
        function editWisata(id){
            fetch(`<?= base_url('admin/wisata/get/') ?>${id}`)
                .then(r => r.json())
                .then(data => {
                    const w = data.wisata;
                    document.getElementById('editNamaWisata').value = w.nama_wisata;
                    document.getElementById('editAlamat').value = w.alamat;
                    document.getElementById('editKategori').value = w.kategori_id;
                    document.getElementById('editKota').value = w.kota_id;
                    document.getElementById('editDeskripsi').value = w.deskripsi;
                    document.getElementById('editDetail').value = w.detail;
                    document.getElementById('editLatitude').value = w.latitude || '';
                    document.getElementById('editLongitude').value = w.longitude || '';
                    loadKecamatan(w.kota_id, 'editKecamatan');
                    setTimeout(()=>{ document.getElementById('editKecamatan').value = w.kecamatan_id; }, 500);
                    document.getElementById('editForm').action = `<?= base_url('admin/wisata/update/') ?>${id}`;
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                })
                .catch(() => alert('Error loading data wisata'));
        }
        function deleteWisata(id, nama){ if (confirm(`Apakah Anda yakin ingin menghapus wisata "${nama}"?\n\nTindakan ini tidak dapat dibatalkan.`)) { window.location.href = `<?= base_url('admin/wisata/delete/') ?>${id}`; } }
        function deleteMaster(type, id, nama){ if (confirm(`Apakah Anda yakin ingin menghapus ${type} "${nama}"?`)) { window.location.href = `<?= base_url('admin/') ?>${type}/delete/${id}`; } }

        // Auto hide alerts
        setTimeout(()=>{ document.querySelectorAll('.alert.alert-dismissible').forEach(a => (new bootstrap.Alert(a)).close()); }, 5000);

        // Coordinate validation
        function validateCoordinates(){
            const lat = document.querySelector('input[name="latitude"]').value;
            const lng = document.querySelector('input[name="longitude"]').value;
            if (lat && (lat < -90 || lat > 90)){ alert('Latitude harus antara -90 sampai 90'); return false; }
            if (lng && (lng < -180 || lng > 180)){ alert('Longitude harus antara -180 sampai 180'); return false; }
            return true;
        }
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('form').forEach(form => {
                if (form.querySelector('input[name="latitude"]')){
                    form.addEventListener('submit', function(e){ if (!validateCoordinates()) e.preventDefault(); });
                }
            });
        });
    </script>
</body>
</html>