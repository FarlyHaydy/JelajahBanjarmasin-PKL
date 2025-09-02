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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        .debug-info {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #000;
            color: #0f0;
            padding: 10px;
            font-family: monospace;
            font-size: 11px;
            max-width: 250px;
            z-index: 9999;
            border-radius: 5px;
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
    </style>
</head>
<body>
    <!-- Debug Info -->
    <!-- <div class="debug-info">
        <strong>DEBUG:</strong><br>
        Wisata: <?= isset($wisata) ? count($wisata) : '0' ?><br>
        Kategori: <?= isset($kategori) ? count($kategori) : '0' ?><br>
        Kota: <?= isset($kota) ? count($kota) : '0' ?><br>
        Kecamatan: <?= isset($kecamatan) ? count($kecamatan) : '0' ?><br>
        Users: <?= isset($users) ? count($users) : '0' ?>
    </div> -->

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-white mb-4">
                    <i class="fas fa-user-shield me-2"></i> JelajahBanjarmasin Admin
                </h4>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" onclick="showSection('create')">
                        <i class="fas fa-plus"></i> Buat Postingan
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('edit')">
                        <i class="fas fa-edit"></i> Edit Postingan
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('delete')">
                        <i class="fas fa-trash"></i> Hapus Postingan
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('master')">
                        <i class="fas fa-database"></i> Master Data
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('users')">
                        <i class="fas fa-users"></i> Data Pengguna
                    </a>
                    <hr class="text-white">
                    <a class="nav-link" href="<?= base_url('logout') ?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Success/Error Messages -->
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
                        <div class="card-header">
                            <h5><i class="fas fa-plus"></i> Buat Postingan Wisata Baru</h5>
                        </div>
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
                                            <?php if (isset($kategori)): ?>
                                                <?php foreach ($kategori as $kat): ?>
                                                    <option value="<?= $kat['kategori_id'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kota</label>
                                        <select name="kota_id" id="kotaSelect" class="form-select" required>
                                            <option value="">Pilih Kota</option>
                                            <?php if (isset($kota)): ?>
                                                <?php foreach ($kota as $k): ?>
                                                    <option value="<?= $k['kota_id'] ?>"><?= $k['nama_kota'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
                                        <label class="form-label">
                                            <i class="fas fa-map-marker-alt text-primary"></i> Latitude (Opsional)
                                        </label>
                                        <input type="number" step="any" name="latitude" class="form-control" placeholder="Contoh: -3.316694">
                                        <small class="text-muted">Untuk peta yang lebih akurat (Range: -90 sampai 90)</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-map-marker-alt text-primary"></i> Longitude (Opsional)
                                        </label>
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

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Postingan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Postingan Section -->
                <div id="edit-section" class="content-section">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-edit"></i> Edit Postingan Wisata</h5>
                        </div>
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
                                        <?php if (isset($wisata) && !empty($wisata)): ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($wisata as $w): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><strong><?= esc($w['nama_wisata'] ?? 'N/A') ?></strong></td>
                                                    <td>
                                                        <span class="badge bg-primary"><?= esc($w['nama_kategori'] ?? 'N/A') ?></span>
                                                    </td>
                                                    <td><?= esc($w['nama_kecamatan'] ?? 'N/A') ?>, <?= esc($w['nama_kota'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <?php if (!empty($w['latitude']) && !empty($w['longitude'])): ?>
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-map-marker-alt"></i> Ada
                                                            </span>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?= number_format($w['latitude'], 6) ?>, <?= number_format($w['longitude'], 6) ?>
                                                            </small>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-map-marker"></i> Belum
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm btn-action" onclick="editWisata(<?= $w['wisata_id'] ?? 0 ?>)">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <a href="<?= base_url('detail/' . ($w['wisata_id'] ?? 0)) ?>" class="btn btn-info btn-sm btn-action" target="_blank">
                                                            <i class="fas fa-eye"></i> Lihat
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-info">
                                                        Belum ada data wisata. <a href="#" onclick="showSection('create')">Buat postingan baru</a>
                                                    </div>
                                                </td>
                                            </tr>
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
                        <div class="card-header">
                            <h5><i class="fas fa-trash"></i> Hapus Postingan Wisata</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Peringatan:</strong> Menghapus postingan akan menghilangkan data secara permanen.
                            </div>
                            
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
                                        <?php if (isset($wisata) && !empty($wisata)): ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($wisata as $w): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <strong><?= esc($w['nama_wisata'] ?? 'N/A') ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?= substr(esc($w['deskripsi'] ?? ''), 0, 50) ?>...</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary"><?= esc($w['nama_kategori'] ?? 'N/A') ?></span>
                                                    </td>
                                                    <td><?= esc($w['nama_kecamatan'] ?? 'N/A') ?>, <?= esc($w['nama_kota'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <?php if (!empty($w['latitude']) && !empty($w['longitude'])): ?>
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-map-marker-alt"></i> Presisi
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-map-marker"></i> Alamat
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm btn-action" onclick="deleteWisata(<?= $w['wisata_id'] ?? 0 ?>, '<?= esc($w['nama_wisata'] ?? '') ?>')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                        <a href="<?= base_url('detail/' . ($w['wisata_id'] ?? 0)) ?>" class="btn btn-outline-info btn-sm btn-action" target="_blank">
                                                            <i class="fas fa-eye"></i> Preview
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-info">
                                                        Belum ada data wisata untuk dihapus.
                                                    </div>
                                                </td>
                                            </tr>
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
                                <div class="card-header">
                                    <h6><i class="fas fa-tags"></i> Kategori</h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('admin/kategori/create') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <?php if (isset($kategori) && !empty($kategori)): ?>
                                        <div class="list-group">
                                            <?php foreach ($kategori as $kat): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= esc($kat['nama_kategori']) ?>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteMaster('kategori', <?= $kat['kategori_id'] ?>, '<?= esc($kat['nama_kategori']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
                                <div class="card-header">
                                    <h6><i class="fas fa-city"></i> Kota</h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('admin/kota/create') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_kota" class="form-control" placeholder="Nama Kota" required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <?php if (isset($kota) && !empty($kota)): ?>
                                        <div class="list-group">
                                            <?php foreach ($kota as $k): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= esc($k['nama_kota']) ?>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteMaster('kota', <?= $k['kota_id'] ?>, '<?= esc($k['nama_kota']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
                                <div class="card-header">
                                    <h6><i class="fas fa-map-marker-alt"></i> Kecamatan</h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('admin/kecamatan/create') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <select name="kota_id" class="form-select mb-2" required>
                                            <option value="">Pilih Kota</option>
                                            <?php if (isset($kota)): ?>
                                                <?php foreach ($kota as $k): ?>
                                                    <option value="<?= $k['kota_id'] ?>"><?= $k['nama_kota'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_kecamatan" class="form-control" placeholder="Nama Kecamatan" required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <?php if (isset($kecamatan) && !empty($kecamatan)): ?>
                                        <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                                            <?php foreach ($kecamatan as $kec): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= esc($kec['nama_kecamatan']) ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?= esc($kec['nama_kota']) ?></small>
                                                    </div>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteMaster('kecamatan', <?= $kec['kecamatan_id'] ?>, '<?= esc($kec['nama_kecamatan']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
                        <div class="card-header">
                            <h5><i class="fas fa-users"></i> Data Pengguna Terdaftar</h5>
                        </div>
                        <div class="card-body">
                            <!-- Stats Cards -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <div class="stats-number"><?= isset($users) ? count($users) : '0' ?></div>
                                            <small>Total Pengguna</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-check fa-2x mb-2"></i>
                                            <div class="stats-number"><?php
                                                $active_users = 0;
                                                if (isset($users)) {
                                                    foreach ($users as $user) {
                                                        if (($user['status'] ?? 'active') === 'active') {
                                                            $active_users++;
                                                        }
                                                    }
                                                }
                                                echo $active_users;
                                            ?></div>
                                            <small>Pengguna Aktif</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-times fa-2x mb-2"></i>
                                            <div class="stats-number"><?php
                                                $inactive_users = 0;
                                                if (isset($users)) {
                                                    foreach ($users as $user) {
                                                        if (($user['status'] ?? 'active') === 'inactive') {
                                                            $inactive_users++;
                                                        }
                                                    }
                                                }
                                                echo $inactive_users;
                                            ?></div>
                                            <small>Pengguna Nonaktif</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                                            <div class="stats-number"><?php
                                                $new_users = 0;
                                                if (isset($users)) {
                                                    $last_month = date('Y-m-d', strtotime('-30 days'));
                                                    foreach ($users as $user) {
                                                        if (isset($user['created_at']) && $user['created_at'] >= $last_month) {
                                                            $new_users++;
                                                        }
                                                    }
                                                }
                                                echo $new_users;
                                            ?></div>
                                            <small>Baru (30 Hari)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Search and Filter -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchUsers" placeholder="Cari nama, username, atau email...">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <label class="form-label me-2 mb-0">Filter Status:</label>
                                        <select class="form-select w-auto" id="filterStatus">
                                            <option value="">Semua</option>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Nonaktif</option>
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
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($users) && !empty($users)): ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white">
                                                            <?= strtoupper(substr($user['Nama_Asli'], 0, 1)) ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong><?= esc($user['Nama_Asli']) ?></strong>
                                                            <br>
                                                            <small class="text-muted">@<?= esc($user['Username']) ?></small>
                                                            <br>
                                                            <span class="badge bg-<?= ($user['Jenis_Kelamin'] === 'Laki-Laki') ? 'info' : 'pink' ?> status-badge">
                                                                <i class="fas fa-<?= ($user['Jenis_Kelamin'] === 'Laki-Laki') ? 'mars' : 'venus' ?>"></i>
                                                                <?= esc($user['Jenis_Kelamin']) ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <i class="fas fa-envelope text-muted"></i>
                                                            <small><?= esc($user['Email']) ?></small>
                                                            <br>
                                                            <i class="fas fa-phone text-muted"></i>
                                                            <small><?= esc($user['Nomor_Telepon']) ?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                                            <small>
                                                                <?= esc($user['Kecamatan'] ?? 'N/A') ?>,<br>
                                                                <?= esc($user['Kota'] ?? 'N/A') ?>
                                                            </small>
                                                            <br>
                                                            <small class="text-muted"><?= substr(esc($user['Alamat']), 0, 30) ?>...</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success status-badge">
                                                            <i class="fas fa-check-circle"></i> Aktif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm btn-action" onclick="editUser(<?= $user['id'] ?>)">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="btn btn-info btn-sm btn-action" onclick="viewUser(<?= $user['id'] ?>)">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        <button class="btn btn-danger btn-sm btn-action" onclick="deleteUser(<?= $user['id'] ?>, '<?= esc($user['Nama_Asli']) ?>')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle"></i>
                                                        Belum ada pengguna terdaftar.
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Destinasi</label>
                                <input type="text" name="nama_wisata" id="editNamaWisata" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" id="editKategori" class="form-select" required>
                                    <?php if (isset($kategori)): ?>
                                        <?php foreach ($kategori as $kat): ?>
                                            <option value="<?= $kat['kategori_id'] ?>"><?= $kat['nama_kategori'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kota</label>
                                <select name="kota_id" id="editKota" class="form-select" required>
                                    <?php if (isset($kota)): ?>
                                        <?php foreach ($kota as $k): ?>
                                            <option value="<?= $k['kota_id'] ?>"><?= $k['nama_kota'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kecamatan</label>
                                <select name="kecamatan_id" id="editKecamatan" class="form-select" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" id="editAlamat" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Detail</label>
                            <textarea name="detail" id="editDetail" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt text-primary"></i> Latitude (Opsional)
                                </label>
                                <input type="number" step="any" name="latitude" id="editLatitude" class="form-control" placeholder="Contoh: -3.316694">
                                <small class="text-muted">Untuk peta yang lebih akurat</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt text-primary"></i> Longitude (Opsional)
                                </label>
                                <input type="number" step="any" name="longitude" id="editLongitude" class="form-control" placeholder="Contoh: 114.590111">
                                <small class="text-muted">Dapatkan koordinat dari Google Maps</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Gambar Baru (Opsional)</label>
                            <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="Username" id="editUsername" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="Nama_Asli" id="editNamaAsli" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="Email" id="editEmail" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="Nomor_Telepon" id="editNomorTelepon" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="Jenis_Kelamin" id="editJenisKelamin" class="form-select" required>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kota</label>
                                <input type="text" name="Kota" id="editKotaUser" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" name="Kecamatan" id="editKecamatanUser" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="Alamat" id="editAlamatUser" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white mx-auto" style="width: 100px; height: 100px; font-size: 2rem;" id="viewUserAvatar">
                                A
                            </div>
                            <h5 class="mt-3" id="viewUserName">Nama Pengguna</h5>
                            <p class="text-muted" id="viewUserUsername">@username</p>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td id="viewUserEmail">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Telepon:</strong></td>
                                    <td id="viewUserPhone">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin:</strong></td>
                                    <td id="viewUserGender">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Kota:</strong></td>
                                    <td id="viewUserCity">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Kecamatan:</strong></td>
                                    <td id="viewUserDistrict">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td id="viewUserAddress">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/Hide sections
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionName + '-section').classList.add('active');
            
            // Update nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        // Load kecamatan based on kota
        document.getElementById('kotaSelect').addEventListener('change', function() {
            loadKecamatan(this.value, 'kecamatanSelect');
        });
        
        document.getElementById('editKota').addEventListener('change', function() {
            loadKecamatan(this.value, 'editKecamatan');
        });
        
        function loadKecamatan(kotaId, targetSelect) {
            const kecamatanSelect = document.getElementById(targetSelect);
            kecamatanSelect.innerHTML = '<option value="">Loading...</option>';
            
            if (kotaId) {
                fetch(`<?= base_url('admin/kecamatan/by-kota/') ?>${kotaId}`)
                    .then(response => response.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.forEach(kecamatan => {
                            kecamatanSelect.innerHTML += `<option value="${kecamatan.kecamatan_id}">${kecamatan.nama_kecamatan}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kecamatanSelect.innerHTML = '<option value="">Error loading</option>';
                    });
            } else {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            }
        }

        // Edit wisata
        function editWisata(id) {
            fetch(`<?= base_url('admin/wisata/get/') ?>${id}`)
                .then(response => response.json())
                .then(data => {
                    const wisata = data.wisata;
                    
                    document.getElementById('editNamaWisata').value = wisata.nama_wisata;
                    document.getElementById('editAlamat').value = wisata.alamat;
                    document.getElementById('editKategori').value = wisata.kategori_id;
                    document.getElementById('editKota').value = wisata.kota_id;
                    document.getElementById('editDeskripsi').value = wisata.deskripsi;
                    document.getElementById('editDetail').value = wisata.detail;
                    
                    // Set koordinat jika ada
                    document.getElementById('editLatitude').value = wisata.latitude || '';
                    document.getElementById('editLongitude').value = wisata.longitude || '';
                    
                    // Load kecamatan dan set nilai
                    loadKecamatan(wisata.kota_id, 'editKecamatan');
                    setTimeout(() => {
                        document.getElementById('editKecamatan').value = wisata.kecamatan_id;
                    }, 500);
                    
                    document.getElementById('editForm').action = `<?= base_url('admin/wisata/update/') ?>${id}`;
                    
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading data wisata');
                });
        }

        // Delete wisata
        function deleteWisata(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus wisata "${nama}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                window.location.href = `<?= base_url('admin/wisata/delete/') ?>${id}`;
            }
        }

        // Delete master data
        function deleteMaster(type, id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus ${type} "${nama}"?`)) {
                window.location.href = `<?= base_url('admin/') ?>${type}/delete/${id}`;
            }
        }

        // ==================== USER MANAGEMENT FUNCTIONS ====================
        
        // Edit user
        function editUser(id) {
            fetch(`<?= base_url('admin/user/get/') ?>${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    const user = data.user;
                    
                    document.getElementById('editUsername').value = user.Username;
                    document.getElementById('editNamaAsli').value = user.Nama_Asli;
                    document.getElementById('editEmail').value = user.Email;
                    document.getElementById('editNomorTelepon').value = user.Nomor_Telepon;
                    document.getElementById('editJenisKelamin').value = user.Jenis_Kelamin;
                    document.getElementById('editKotaUser').value = user.Kota || '';
                    document.getElementById('editKecamatanUser').value = user.Kecamatan || '';
                    document.getElementById('editAlamatUser').value = user.Alamat;
                    
                    document.getElementById('editUserForm').action = `<?= base_url('admin/user/update/') ?>${id}`;
                    
                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading data pengguna');
                });
        }

        // View user details
        function viewUser(id) {
            fetch(`<?= base_url('admin/user/get/') ?>${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    const user = data.user;
                    
                    // Set avatar
                    document.getElementById('viewUserAvatar').textContent = user.Nama_Asli.charAt(0).toUpperCase();
                    
                    // Set user info
                    document.getElementById('viewUserName').textContent = user.Nama_Asli;
                    document.getElementById('viewUserUsername').textContent = '@' + user.Username;
                    document.getElementById('viewUserEmail').textContent = user.Email;
                    document.getElementById('viewUserPhone').textContent = user.Nomor_Telepon;
                    document.getElementById('viewUserGender').textContent = user.Jenis_Kelamin;
                    document.getElementById('viewUserCity').textContent = user.Kota || '-';
                    document.getElementById('viewUserDistrict').textContent = user.Kecamatan || '-';
                    document.getElementById('viewUserAddress').textContent = user.Alamat;
                    
                    new bootstrap.Modal(document.getElementById('viewUserModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading data pengguna');
                });
        }

        // Delete user
        function deleteUser(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${nama}"?\n\nTindakan ini akan menghapus semua data termasuk wishlist pengguna dan tidak dapat dibatalkan.`)) {
                window.location.href = `<?= base_url('admin/user/delete/') ?>${id}`;
            }
        }

        // Search users
        document.getElementById('searchUsers').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const userData = row.textContent.toLowerCase();
                if (userData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filter by status
        document.getElementById('filterStatus').addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                if (filterValue === '') {
                    row.style.display = '';
                } else {
                    const statusBadge = row.querySelector('.badge');
                    if (statusBadge) {
                        const isActive = statusBadge.textContent.includes('Aktif');
                        if ((filterValue === 'active' && isActive) || (filterValue === 'inactive' && !isActive)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                }
            });
        });

        // Auto hide alerts
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.classList.contains('alert-dismissible')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Coordinate validation
        function validateCoordinates() {
            const latitude = document.querySelector('input[name="latitude"]').value;
            const longitude = document.querySelector('input[name="longitude"]').value;
            
            if (latitude && (latitude < -90 || latitude > 90)) {
                alert('Latitude harus antara -90 sampai 90');
                return false;
            }
            
            if (longitude && (longitude < -180 || longitude > 180)) {
                alert('Longitude harus antara -180 sampai 180');
                return false;
            }
            
            return true;
        }

        // Add validation to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                if (form.querySelector('input[name="latitude"]')) {
                    form.addEventListener('submit', function(e) {
                        if (!validateCoordinates()) {
                            e.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>