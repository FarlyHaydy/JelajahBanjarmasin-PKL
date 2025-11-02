<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Banjarmasin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/adminDashboard.css') ?>">

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
                    <a class="nav-link" href="#" onclick="showSection('destinasi')"><i class="fas fa-map"></i> Data Destinasi Wisata</a>
                    <a class="nav-link" href="#" onclick="showSection('import')"><i class="fas fa-upload"></i> Import Destinasi Wisata</a>
                    <a class="nav-link" href="#" onclick="showSection('users')"><i class="fas fa-users"></i> Data Pengguna</a>
                    <a class="nav-link" href="#" onclick="showSection('statistics')"><i class="fas fa-chart-bar"></i> Statistik Pengguna</a>
                    <a class="nav-link" href="<?= base_url('/') ?>"><i class="fas fa-home"></i> Beranda</a>

                    <hr class="text-white">
                    <a id="logoutLink" class="nav-link" href="<?= base_url('logout') ?>">
                    <i class="fas fa-sign-out-alt"></i> Logout
                    </a>

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
                            <input type="file" name="gambar[]" id="createGambar" class="form-control" multiple accept="image/*" required>
                            <small class="text-muted">Pilih maksimal 5 gambar</small>
                            <div id="createImagePreview" class="mt-2"></div>
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
                                                <td><?= esc($w['nama_kategori'] ?? 'N/A') ?></span></td>
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
                                                <td><?= esc($w['nama_kategori'] ?? 'N/A') ?></span></td>
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
                                                    <button class="btn btn-danger btn-sm btn-action" onclick="deleteWisata(<?= $w['wisata_id'] ?? 0 ?>, '<?= esc($w['nama_wisata'] ?? '') ?>')"><i class="fas fa-trash"></i> Hapus</button>
                                                    <a href="<?= base_url('detail/' . ($w['wisata_id'] ?? 0)) ?>" class="btn btn-info btn-sm btn-action" target="_blank"><i class="fas fa-eye"></i> Lihat</a>
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

                <!-- Modal Edit Wisata -->
                 <!-- Modal Edit Wisata -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form id="editForm" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Form Data Wisata -->
                            <div class="mb-3">
                                <label class="form-label">Nama Wisata</label>
                                <input type="text" id="editNamaWisata" name="nama_wisata" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" id="editAlamat" name="alamat" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select id="editKategori" name="kategori_id" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php if (isset($kategori)): foreach ($kategori as $kat): ?>
                                            <option value="<?= $kat['kategori_id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota</label>
                                    <select id="editKota" name="kota_id" class="form-select" required>
                                        <option value="">Pilih Kota</option>
                                        <?php if (isset($kota)): foreach ($kota as $k): ?>
                                            <option value="<?= $k['kota_id'] ?>"><?= esc($k['nama_kota']) ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kecamatan</label>
                                    <select id="editKecamatan" name="kecamatan_id" class="form-select" required>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Singkat</label>
                                <textarea id="editDeskripsi" name="deskripsi" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Detail Lengkap</label>
                                <textarea id="editDetail" name="detail" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-map-marker-alt text-primary"></i> Latitude</label>
                                    <input type="number" step="any" id="editLatitude" name="latitude" class="form-control" placeholder="Contoh: -3.316694">
                                    <small class="text-muted">Range: -90 sampai 90</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-map-marker-alt text-primary"></i> Longitude</label>
                                    <input type="number" step="any" id="editLongitude" name="longitude" class="form-control" placeholder="Contoh: 114.590111">
                                    <small class="text-muted">Range: -180 sampai 180</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Gallery Management -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-images"></i> Kelola Gambar</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Current Images -->
                                    <div id="galleryContainer" class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Gambar saat ini:</small>
                                            <span id="imageCount" class="badge bg-secondary">0/5</span>
                                        </div>
                                        <div id="currentImages" class="current-images-container">
                                            <!-- Images will be loaded here -->
                                        </div>
                                    </div>
                                    
                                    <!-- Upload New Image -->
                                    <div class="upload-section">
                                        <label class="form-label"><i class="fas fa-plus"></i> Tambah Gambar Baru</label>
                                        <div class="input-group mb-2">
                                            <input type="file" id="newImage" class="form-control" accept="image/*">
                                            <button type="button" class="btn btn-primary" onclick="uploadNewImage()">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Maksimal 5 gambar per wisata</small>
                                    </div>
                            <div class="alert alert-info mb-3">
                            <small>
                                <i class="fas fa-info-circle"></i> 
                                <strong>Perubahan gambar akan disimpan ketika Anda menekan "Simpan Perubahan Data"</strong><br>
                                • Gambar yang ditandai akan dihapus<br>
                                • Gambar baru akan ditambahkan<br>
                                • Primary image akan diubah
                            </small>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                                    <span><?= esc($kat['nama_kategori']) ?></span>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" onclick="editKategori(<?= $kat['kategori_id'] ?>, '<?= esc($kat['nama_kategori']) ?>')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteMaster('kategori', <?= $kat['kategori_id'] ?>, '<?= esc($kat['nama_kategori']) ?>')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
                                    <span><?= esc($k['nama_kota']) ?></span>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" onclick="editKota(<?= $k['kota_id'] ?>, '<?= esc($k['nama_kota']) ?>')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteMaster('kota', <?= $k['kota_id'] ?>, '<?= esc($k['nama_kota']) ?>')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
                        <select name="kota_id" id="filterKotaKecamatan" class="form-select mb-2" onchange="filterKecamatanByKota()" required>
                            <option value="">Pilih Kota</option>
                            <?php if (isset($kota)): foreach ($kota as $k): ?>
                                <option value="<?= $k['kota_id'] ?>"><?= esc($k['nama_kota']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <div class="input-group mb-3">
                            <input type="text" name="nama_kecamatan" class="form-control" placeholder="Nama Kecamatan" required>
                            <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i></button>
                        </div>
                    </form>
                    
                    <!-- List kecamatan -->
                    <div id="kecamatanList" class="list-group" style="max-height: 300px; overflow-y: auto; display: none;">
                        <?php if (isset($kecamatan) && !empty($kecamatan)): ?>
                            <?php foreach ($kecamatan as $kec): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center" data-kota-id="<?= $kec['kota_id'] ?>">
                                    <div>
                                        <strong><?= esc($kec['nama_kecamatan']) ?></strong><br>
                                        <small class="text-muted"><?= esc($kec['nama_kota']) ?></small>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" onclick="editKecamatan(<?= $kec['kecamatan_id'] ?>, '<?= addslashes(esc($kec['nama_kecamatan'])) ?>', <?= $kec['kota_id'] ?>, '<?= addslashes(esc($kec['nama_kota'])) ?>')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteMaster('kecamatan', <?= $kec['kecamatan_id'] ?>, '<?= addslashes(esc($kec['nama_kecamatan'])) ?>')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /row -->
</div> <!-- /master-section -->

                <!-- Data Destinasi Wisata Section -->
<div id="destinasi-section" class="content-section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-map"></i> Data Destinasi Wisata</h5>
            <div class="export-section">
                <button class="btn btn-danger" onclick="exportDestinasiToPDF()"><i class="fas fa-file-pdf"></i> Export PDF</button>
            </div>
        </div>
        <div class="card-body">
            <!-- Pencarian dan Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchDestinasi" placeholder="Cari nama destinasi...">
                        <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <label class="form-label me-2 mb-0">Filter Kategori:</label>
                        <select class="form-select w-auto" id="filterKategori">
                            <option value="">Semua</option>
                            <?php if (isset($kategori)): foreach ($kategori as $kat): ?>
                                <option value="<?= $kat['kategori_id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabel Destinasi Wisata -->
            <!-- Tabel Destinasi Wisata -->
        <div class="table-responsive">
            <table class="table table-hover" id="destinasiTable">
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
                        <tr data-kategori-id="<?= esc($w['kategori_id']) ?>">
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($w['nama_wisata'] ?? 'N/A') ?></strong></td>
                            <td><?= esc($w['nama_kategori'] ?? 'N/A') ?></td>
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
                                <a href="<?= base_url('detail/' . ($w['wisata_id'] ?? 0)) ?>" class="btn btn-info btn-sm btn-action" target="_blank"><i class="fas fa-eye"></i> Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center"><div class="alert alert-info">Belum ada data destinasi wisata.</div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<div class="content-section" id="import-section">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-upload"></i> Import Destinasi Wisata</h5>
        </div>
        <div class="card-body">
            <!-- Tampilkan pesan error/success jika ada -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
           
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Form untuk upload file XLSX -->
            <form action="<?= base_url('admin/wisata/import') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="file_xlsx" class="form-label">Pilih File Excel (XLSX)</label>
                    <input type="file" name="file_xlsx" id="file_xlsx" class="form-control" accept=".xlsx,.xls" required>
                    <small class="text-muted d-block mt-1">
                        <strong>Format Excel:</strong> 
                        <a href="<?= base_url('admin/wisata/download-template') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i> Download Template XLSX
                        </a>
                    </small>
                    <small class="text-muted d-block mt-1">
                        <strong>Kolom yang diperlukan:</strong><br>
                        Nama Wisata | Alamat | Kategori_ID | Kota_ID | Kecamatan_ID | Deskripsi | Detail | Latitude | Longitude
                    </small>
                    <small class="text-muted d-block mt-1">
                        <strong>Contoh data:</strong><br>
                        Pantai Indah | Jl. Pantai No. 1 | 1 | 1 | 1 | Pantai yang indah | Detail lengkap pantai... | -6.123456 | 106.789012
                    </small>
                    <small class="text-muted d-block mt-1">
                        Ukuran maksimal: 10MB
                    </small>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Import Data
                </button>
            </form>
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
                                <!-- <div class="col-md-3">
                                    <div class="card stats-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                                            <div class="stats-number"><?= $new_users ?></div>
                                            <small>Baru (30 Hari)</small>
                                        </div>
                                    </div>
                                </div> -->
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
                                                        <div class="user-avatar bg-secondary d-flex align-items-center justify-content-center text-white">
                                                            <?= strtoupper(substr($user['Nama_Asli'], 0, 1)) ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong><?= esc($user['Nama_Asli']) ?></strong><br>
                                                            <small class="text-muted">@<?= esc($user['Username']) ?></small><br>
                                                            <span class="badge <?= ($user['Jenis_Kelamin'] === 'Laki-Laki') ? 'bg-info' : 'bg-danger' ?> status-badge">
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
                    <h2><i class="fas fa-chart-bar"></i> Statistik Pengguna</h2>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Tipe Statistik</label>
                                    <select class="form-select" id="chartType" onchange="updateChart()">
                                        <option value="" selected>Pilih Statistik</option>
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
                        <div class="card-header"><h6 class="mb-0" id="chartTitle"><i class="fas fa-chart-bar"></i> Pilih Tipe Statistik</h6></div>
                        <div class="card-body"><div class="chart-container"><canvas id="userStatsChart"></canvas></div></div>
                    </div>
                    </div></div>

                    <div class="card">
                        <div class="card-header"><h6><i class="fas fa-table"></i> Detail Statistik</h6></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="statsTable">
                                <thead><tr id="statsTableHeader"><th>No</th><th>Kategori</th><th>Jumlah</th><th>Persentase</th></tr></thead>
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
                            <div class="user-avatar bg-secondary d-flex align-items-center justify-content-center text-white mx-auto" style="width: 100px; height: 100px; font-size: 2rem;" id="viewUserAvatar">A</div>
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

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editKategoriForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" id="editNamaKategori" name="nama_kategori" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kota -->
    <div class="modal fade" id="editKotaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editKotaForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Kota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kota</label>
                            <input type="text" id="editNamaKota" name="nama_kota" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kecamatan -->
    <div class="modal fade" id="editKecamatanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editKecamatanForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Kecamatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kecamatan</label>
                            <input type="text" id="editNamaKecamatan" name="nama_kecamatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <select id="editKotaKecamatan" name="kota_id" class="form-select" required>
                                <option value="">Pilih Kota</option>
                                <?php if (isset($kota)): foreach ($kota as $k): ?>
                                    <option value="<?= $k['kota_id'] ?>"><?= esc($k['nama_kota']) ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
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
    
        function initializeChart() { 
    if (userChart) {
        userChart.destroy();
        userChart = null;
    }
    
    // Set initial empty state
    document.getElementById('chartTitle').innerHTML = '<i class="fas fa-chart-bar"></i> Pilih Tipe Statistik';
    
    // ✅ RESET HEADER TABEL KE STATE AWAL
    const headerRow = document.getElementById('statsTableHeader');
    headerRow.innerHTML = '<th>No</th><th>Kategori</th><th>Jumlah</th><th>Persentase</th>';
    
    // Reset body tabel
    document.getElementById('statsTableBody').innerHTML = '<tr><td colspan="4" class="text-center"><div class="alert alert-info"><i class="fas fa-info-circle"></i> Silakan pilih tipe statistik terlebih dahulu</div></td></tr>';
}
    function updateChart(){
    const chartType = document.getElementById('chartType').value;
    const chartStyle = document.getElementById('chartStyle').value;
    
    // Jika belum pilih statistik, reset ke state awal
    if (!chartType || chartType === '') {
        // ✅ RESET CHART TITLE
        document.getElementById('chartTitle').innerHTML = '<i class="fas fa-chart-bar"></i> Pilih Tipe Statistik';
        
        // ✅ RESET HEADER TABEL
        const headerRow = document.getElementById('statsTableHeader');
        headerRow.innerHTML = '<th>No</th><th>Kategori</th><th>Jumlah</th><th>Persentase</th>';
        
        // ✅ RESET BODY TABEL
        document.getElementById('statsTableBody').innerHTML = '<tr><td colspan="4" class="text-center"><div class="alert alert-info"><i class="fas fa-info-circle"></i> Silakan pilih tipe statistik terlebih dahulu</div></td></tr>';
        
        // ✅ DESTROY CHART
        if (userChart) {
            userChart.destroy();
            userChart = null;
        }
        return; // Stop execution
    }
    
    let data, title, headers;
    if (chartType==='kota'){ 
        data=getDataByKota(); 
        title='Distribusi Pengguna per Kabupaten/Kota'; 
        headers=['No','Kabupaten/Kota','Jumlah Pengguna','Persentase']; 
    }
    else if (chartType==='kecamatan'){ 
        data=getDataByKecamatan(); 
        title='Distribusi Pengguna per Kecamatan'; 
        headers=['No','Kecamatan','Jumlah Pengguna','Persentase']; 
    }
    else if (chartType==='gender'){ 
        data=getDataByGender(); 
        title='Distribusi Pengguna per Jenis Kelamin'; 
        headers=['No','Jenis Kelamin','Jumlah Pengguna','Persentase']; 
    }
    
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
        let currentWisataId = null;
let galleryChanges = {
    toDelete: [],
    toSetPrimary: null,
    newImages: []
};

function editWisata(id) {
    currentWisataId = id;
    
    // Reset gallery changes
    galleryChanges = {
        toDelete: [],
        toSetPrimary: null,
        newImages: []
    };
    
    const url = "<?= site_url('admin/wisata/get') ?>/" + id;

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(async (r) => {
            if (!r.ok) {
                const text = await r.text();
                console.error('GET gagal:', r.status, text);
                throw new Error('HTTP ' + r.status);
            }
            return r.json();
        })
        .then(({ status, wisata, message }) => {
            if (status !== 'success' || !wisata) {
                console.error('Response tidak valid:', message);
                alert('Data wisata tidak valid');
                return;
            }

            // isi form modal
            document.getElementById('editNamaWisata').value = wisata.nama_wisata || '';
            document.getElementById('editAlamat').value     = wisata.alamat || '';
            document.getElementById('editKategori').value   = wisata.kategori_id || '';
            document.getElementById('editKota').value       = wisata.kota_id || '';
            document.getElementById('editDeskripsi').value  = wisata.deskripsi || '';
            document.getElementById('editDetail').value     = wisata.detail || '';
            document.getElementById('editLatitude').value   = wisata.latitude ?? '';
            document.getElementById('editLongitude').value  = wisata.longitude ?? '';

            // load kecamatan berdasar kota lalu set valuenya
            loadKecamatan(wisata.kota_id, 'editKecamatan');
            setTimeout(() => {
                document.getElementById('editKecamatan').value = wisata.kecamatan_id || '';
            }, 300);

            // set action update
            document.getElementById('editForm').action = "<?= site_url('admin/wisata/update') ?>/" + id;

            // Load gallery images
            loadGalleryImages(id);

            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch((e) => {
            console.error(e);
            alert('Error loading data wisata');
        });
}

// Load gallery images dengan status changes
function loadGalleryImages(wisataId) {
    fetch(`<?= base_url('admin/wisata/') ?>${wisataId}/gallery`)
        .then(r => r.json())
        .then(images => {
            const container = document.getElementById('currentImages');
            const countElement = document.getElementById('imageCount');
            
            if (!images || images.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-3"><i class="fas fa-image fa-2x mb-2"></i><br>Belum ada gambar</div>';
                countElement.textContent = '0/5';
                return;
            }

            let html = '';
            images.forEach(image => {
                const isPrimary = image.is_primary == 1;
                const isMarkedForDelete = galleryChanges.toDelete.includes(image.galeri_id);
                const isNewPrimary = galleryChanges.toSetPrimary === image.galeri_id;
                
                html += `
                    <div class="image-item ${isPrimary || isNewPrimary ? 'primary' : ''} ${isMarkedForDelete ? 'marked-for-delete' : ''}" 
                         data-image-id="${image.galeri_id}">
                        ${(isPrimary || isNewPrimary) ? '<span class="primary-badge"><i class="fas fa-star"></i> Primary</span>' : ''}
                        ${isMarkedForDelete ? '<span class="delete-badge"><i class="fas fa-trash"></i> Akan Dihapus</span>' : ''}
                        <img src="<?= base_url('uploads/wisata/') ?>${image.nama_file}" 
                             class="image-preview" 
                             alt="Gambar wisata">
                        <div class="image-actions">
                            ${!isPrimary && !isMarkedForDelete ? `
                                <button class="btn btn-success btn-sm" onclick="markAsPrimary(${image.galeri_id})" title="Set Primary">
                                    <i class="fas fa-star"></i>
                                </button>
                            ` : ''}
                            ${!isMarkedForDelete ? `
                                <button class="btn btn-danger btn-sm" onclick="markForDelete(${image.galeri_id})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : `
                                <button class="btn btn-warning btn-sm" onclick="unmarkForDelete(${image.galeri_id})" title="Batal Hapus">
                                    <i class="fas fa-undo"></i>
                                </button>
                            `}
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            updateImageCount();
        })
        .catch(error => {
            console.error('Error loading gallery:', error);
            document.getElementById('currentImages').innerHTML = '<div class="text-center text-danger py-3">Error loading images</div>';
        });
}

// Update image count dengan memperhitungkan changes
function updateImageCount() {
    // Hanya hitung gambar yang TIDAK ditandai untuk dihapus
    const currentItems = document.querySelectorAll('.image-item:not(.marked-for-delete):not(.new-image)').length;
    const newImagesCount = galleryChanges.newImages.length;
    const totalCount = currentItems + newImagesCount;
    
    document.getElementById('imageCount').textContent = `${totalCount}/5`;
    
    // Update warning jika melebihi batas
    if (totalCount > 5) {
        document.getElementById('imageCount').classList.add('bg-danger');
    } else {
        document.getElementById('imageCount').classList.remove('bg-danger');
    }
}

// Mark image untuk dihapus
function markForDelete(galeriId) {
    if (!galleryChanges.toDelete.includes(galeriId)) {
        galleryChanges.toDelete.push(galeriId);
    }
    
    // Update UI
    const imageItem = document.querySelector(`.image-item[data-image-id="${galeriId}"]`);
    if (imageItem) {
        imageItem.classList.add('marked-for-delete');
        imageItem.querySelector('.delete-badge')?.remove();
        imageItem.insertAdjacentHTML('afterbegin', '<span class="delete-badge"><i class="fas fa-trash"></i> Akan Dihapus</span>');
        
        // Update actions
        const actions = imageItem.querySelector('.image-actions');
        actions.innerHTML = `
            <button class="btn btn-warning btn-sm" onclick="unmarkForDelete(${galeriId})" title="Batal Hapus">
                <i class="fas fa-undo"></i>
            </button>
        `;
    }
    
    updateImageCount();
}

// Batal mark untuk dihapus
function unmarkForDelete(galeriId) {
    galleryChanges.toDelete = galleryChanges.toDelete.filter(id => id !== galeriId);
    
    // Update UI
    const imageItem = document.querySelector(`.image-item[data-image-id="${galeriId}"]`);
    if (imageItem) {
        imageItem.classList.remove('marked-for-delete');
        imageItem.querySelector('.delete-badge')?.remove();
        
        const isPrimary = galleryChanges.toSetPrimary === galeriId || imageItem.classList.contains('primary');
        const actions = imageItem.querySelector('.image-actions');
        actions.innerHTML = `
            ${!isPrimary ? `
                <button class="btn btn-success btn-sm" onclick="markAsPrimary(${galeriId})" title="Set Primary">
                    <i class="fas fa-star"></i>
                </button>
            ` : ''}
            <button class="btn btn-danger btn-sm" onclick="markForDelete(${galeriId})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        `;
    }
    
    updateImageCount();
}

// Mark image sebagai primary
function markAsPrimary(galeriId) {
    galleryChanges.toSetPrimary = galeriId;
    
    // Update UI - remove primary dari semua, lalu set yang baru
    document.querySelectorAll('.image-item').forEach(item => {
        item.classList.remove('primary');
        item.querySelector('.primary-badge')?.remove();
    });
    
    const imageItem = document.querySelector(`.image-item[data-image-id="${galeriId}"]`);
    if (imageItem && !imageItem.classList.contains('marked-for-delete')) {
        imageItem.classList.add('primary');
        imageItem.insertAdjacentHTML('afterbegin', '<span class="primary-badge"><i class="fas fa-star"></i> Primary Baru</span>');
        
        // Update actions
        const actions = imageItem.querySelector('.image-actions');
        actions.innerHTML = `
            <button class="btn btn-warning btn-sm" onclick="unmarkAsPrimary(${galeriId})" title="Batal Primary">
                <i class="fas fa-times"></i>
            </button>
            <button class="btn btn-danger btn-sm" onclick="markForDelete(${galeriId})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        `;
    }
}

// Batal mark sebagai primary
function unmarkAsPrimary(galeriId) {
    galleryChanges.toSetPrimary = null;
    
    // Update UI
    const imageItem = document.querySelector(`.image-item[data-image-id="${galeriId}"]`);
    if (imageItem) {
        imageItem.classList.remove('primary');
        imageItem.querySelector('.primary-badge')?.remove();
        
        const actions = imageItem.querySelector('.image-actions');
        actions.innerHTML = `
            <button class="btn btn-success btn-sm" onclick="markAsPrimary(${galeriId})" title="Set Primary">
                <i class="fas fa-star"></i>
            </button>
            <button class="btn btn-danger btn-sm" onclick="markForDelete(${galeriId})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        `;
    }
}

// Upload new image - hanya tambah ke queue
function uploadNewImage() {
    const fileInput = document.getElementById('newImage');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Pilih file gambar terlebih dahulu');
        return;
    }

    // PERBAIKAN: Hitung dengan benar hanya gambar yang tidak dihapus
    const currentItems = document.querySelectorAll('.image-item:not(.marked-for-delete):not(.new-image)').length;
    const totalCount = currentItems + galleryChanges.newImages.length;
    
    if (totalCount >= 5) {
        alert('Maksimal 5 gambar per wisata');
        return;
    }

    // Create preview and add to newImages queue
    const reader = new FileReader();
    reader.onload = function(e) {
        const newImageId = 'new-' + Date.now();
        galleryChanges.newImages.push({
            id: newImageId,
            file: file,
            preview: e.target.result
        });

        // Add to UI
        const container = document.getElementById('currentImages');
        const newImageHTML = `
            <div class="image-item new-image" data-image-id="${newImageId}">
                <span class="new-badge"><i class="fas fa-plus"></i> Baru</span>
                <img src="${e.target.result}" 
                     class="image-preview" 
                     alt="Gambar baru">
                <div class="image-actions">
                    <button class="btn btn-success btn-sm" onclick="markNewAsPrimary('${newImageId}')" title="Set Primary">
                        <i class="fas fa-star"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="removeNewImage('${newImageId}')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newImageHTML);
        updateImageCount();
        
        // Reset file input
        fileInput.value = '';
    };
    
    reader.readAsDataURL(file);
}

// Mark new image as primary
function markNewAsPrimary(newImageId) {
    galleryChanges.toSetPrimary = newImageId;
    
    // Update UI
    document.querySelectorAll('.image-item').forEach(item => {
        item.classList.remove('primary');
        item.querySelector('.primary-badge')?.remove();
    });
    
    const imageItem = document.querySelector(`.image-item[data-image-id="${newImageId}"]`);
    if (imageItem) {
        imageItem.classList.add('primary');
        imageItem.querySelector('.new-badge')?.remove();
        imageItem.insertAdjacentHTML('afterbegin', '<span class="primary-badge"><i class="fas fa-star"></i> Primary Baru</span>');
    }
}

// Remove new image from queue
function removeNewImage(newImageId) {
    galleryChanges.newImages = galleryChanges.newImages.filter(img => img.id !== newImageId);
    if (galleryChanges.toSetPrimary === newImageId) {
        galleryChanges.toSetPrimary = null;
    }
    
    // Remove from UI
    const imageItem = document.querySelector(`.image-item[data-image-id="${newImageId}"]`);
    if (imageItem) {
        imageItem.remove();
    }
    
    updateImageCount();
}

// Process gallery changes ketika form disubmit
function processGalleryChanges() {
    return new Promise((resolve, reject) => {
        const promises = [];
        
        // 1. Process deletions
        galleryChanges.toDelete.forEach(galeriId => {
            const formData = new FormData();
            formData.append('galeri_id', galeriId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            
            promises.push(
                fetch(`<?= base_url('admin/wisata/') ?>${currentWisataId}/gallery/delete`, {
                    method: 'POST',
                    body: formData
                }).then(r => r.json())
            );
        });
        
        // 2. Process new images
        galleryChanges.newImages.forEach(newImage => {
            const formData = new FormData();
            formData.append('gambar', newImage.file);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            
            promises.push(
                fetch(`<?= base_url('admin/wisata/') ?>${currentWisataId}/gallery/upload`, {
                    method: 'POST',
                    body: formData
                }).then(r => r.json())
            );
        });
        
        // 3. Process primary image change (if any)
        if (galleryChanges.toSetPrimary && !galleryChanges.toSetPrimary.toString().startsWith('new-')) {
            const formData = new FormData();
            formData.append('galeri_id', galleryChanges.toSetPrimary);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            
            promises.push(
                fetch(`<?= base_url('admin/wisata/') ?>${currentWisataId}/gallery/set-primary`, {
                    method: 'POST',
                    body: formData
                }).then(r => r.json())
            );
        }
        
        // Execute all promises
        if (promises.length > 0) {
            Promise.all(promises)
                .then(results => {
                    console.log('Gallery changes processed:', results);
                    resolve(true);
                })
                .catch(error => {
                    console.error('Error processing gallery changes:', error);
                    reject(error);
                });
        } else {
            resolve(true); // No changes to process
        }
    });
}

// Update form submit handler - TANPA LOADING SPINNER
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // TANPA LOADING STATE - langsung process
            processGalleryChanges()
                .then(() => {
                    // Submit form secara normal (akan trigger redirect dan flash message)
                    this.submit();
                })
                .catch(error => {
                    console.error('Gallery processing error:', error);
                    alert('Error memproses perubahan gambar: ' + error.message);
                });
        });
    }
});

      function deleteWisata(id, nama){ if (confirm(`Apakah Anda yakin ingin menghapus wisata "${nama}"?\n\nTindakan ini tidak dapat dibatalkan.`)) { window.location.href = `<?= base_url('admin/wisata/delete/') ?>${id}`; } }

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

        // ====== Enhanced Master Data Functions ======

        // Edit Kategori
        function editKategori(id, nama) {
            document.getElementById('editNamaKategori').value = nama;
            document.getElementById('editKategoriForm').action = '<?= base_url('admin/kategori/update/') ?>' + id;
            new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
        }

        // Edit Kota
        function editKota(id, nama) {
            document.getElementById('editNamaKota').value = nama;
            document.getElementById('editKotaForm').action = '<?= base_url('admin/kota/update/') ?>' + id;
            new bootstrap.Modal(document.getElementById('editKotaModal')).show();
        }

        // Edit Kecamatan
        function editKecamatan(id, nama, kotaId, namaKota) {
            document.getElementById('editNamaKecamatan').value = nama;
            document.getElementById('editKotaKecamatan').value = kotaId;
            document.getElementById('editKecamatanForm').action = '<?= base_url('admin/kecamatan/update/') ?>' + id;
            new bootstrap.Modal(document.getElementById('editKecamatanModal')).show();
        }

        // Enhanced delete function with better confirmation
        function deleteMaster(type, id, nama) {
            const typeNames = {
                'kategori': 'kategori',
                'kota': 'kota',
                'kecamatan': 'kecamatan'
            };
            
            const typeName = typeNames[type] || type;
            
            if (confirm(`Apakah Anda yakin ingin menghapus ${typeName} "${nama}"?\n\nPeringatan: Data yang terkait dengan ${typeName} ini mungkin akan terpengaruh.`)) {
                // Show loading state
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Redirect to delete
                window.location.href = `<?= base_url('admin/') ?>${type}/delete/${id}`;
            }
        }


        // Add enter key support for quick editing
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.matches('#editNamaKategori, #editNamaKota, #editNamaKecamatan')) {
                e.target.closest('form').querySelector('button[type="submit"]').click();
            }
        });
    
    function exportDestinasiToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(20);
    doc.text('Laporan Data Destinasi Wisata', 14, 22);
    doc.setFontSize(12);
    doc.text(`JelajahBanjarmasin - ${new Date().toLocaleDateString('id-ID')}`, 14, 30);

    const headers = ['No', 'Nama Wisata', 'Kategori', 'Lokasi', 'Koordinat'];
    const rows = [];

    // Gather rows from the table
    document.querySelectorAll('#destinasiTable tbody tr').forEach((row, index) => {
        const cells = row.querySelectorAll('td');
        rows.push([
            index + 1,
            cells[1].textContent,
            cells[2].textContent,
            cells[3].textContent,
            cells[4].textContent
        ]);
    });

    // Define custom styles for table headers and rows
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 40,
        styles: {
            fontSize: 8,
            cellPadding: 2
        },
        headStyles: {
            fillColor: [45, 52, 54], // Dark grey color (adjusted to match the header in data pengguna PDF)
            textColor: 255, // White text color
            fontSize: 10,
            halign: 'center'
        },
        alternateRowStyles: {
            fillColor: [240, 240, 240] // Light grey background for alternating rows
        }
    });

    const finalY = doc.lastAutoTable.finalY + 10;
    doc.text(`Total Destinasi: ${rows.length}`, 14, finalY);
    doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 14, finalY + 7);
    doc.save(`data-destinasi-wisata-${new Date().toISOString().split('T')[0]}.pdf`);
    alert('File PDF berhasil diunduh!');
}

document.getElementById('searchDestinasi').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#destinasiTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

document.getElementById('filterKategori').addEventListener('change', function() {
    const kategoriId = this.value;  // Ambil ID kategori yang dipilih
    document.querySelectorAll('#destinasiTable tbody tr').forEach(row => {
        const kategori = row.getAttribute('data-kategori-id');  // Ambil kategori ID dari data atribut
        // Jika kategori kosong (semua kategori) atau kategori sesuai, tampilkan baris
        row.style.display = (kategoriId === '' || kategori === kategoriId) ? '' : 'none';
    });
});

//show section for user klik
function showSection(sectionName) {
    document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
    document.getElementById(sectionName + '-section').classList.add('active');
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    event.target.classList.add('active');
    if (sectionName === 'import') setTimeout(initializeChart, 100); // Menambahkan pemeriksaan untuk section import
}

// Fungsi filter kecamatan (FINAL VERSION)
function filterKecamatanByKota() {
    const selectedKotaId = document.getElementById('filterKotaKecamatan').value;
    const kecamatanList = document.getElementById('kecamatanList');

    
    if (selectedKotaId === '') {
        kecamatanList.style.display = 'none';
        return;
    }
    
    kecamatanList.style.display = 'block';
    
    const allItems = kecamatanList.querySelectorAll('.list-group-item');
    let visibleCount = 0;
    
    allItems.forEach(item => {
        if (item.classList.contains('empty-message')) {
            item.remove();
            return;
        }
        
        const itemKotaId = item.getAttribute('data-kota-id');
        
        if (itemKotaId === selectedKotaId) {
            item.style.display = ''; // ← UBAH JADI KOSONG (biar ikut class asli)
            item.classList.remove('d-none'); // Pastikan tidak ada class d-none
            visibleCount++;
        } else {
            item.style.display = 'none';
            item.classList.add('d-none'); // Tambah class d-none
        }
    });
    
    if (visibleCount === 0) {
        const emptyMsg = document.createElement('div');
        emptyMsg.className = 'list-group-item text-center text-muted empty-message';
        emptyMsg.textContent = 'Belum ada kecamatan untuk kota ini';
        kecamatanList.appendChild(emptyMsg);
    }
}

// Reset saat load
document.addEventListener('DOMContentLoaded', function() {
    const kecamatanList = document.getElementById('kecamatanList');
    const kotaSelect = document.getElementById('filterKotaKecamatan');
    
    if (kotaSelect) {
        kotaSelect.selectedIndex = 0;
    }
    
    if (kecamatanList) {
        kecamatanList.style.display = 'none';
        // Reset semua item display
        kecamatanList.querySelectorAll('.list-group-item').forEach(item => {
            item.style.display = '';
            item.classList.remove('d-none');
        });
    }
});

// ========== TAMBAHKAN INI ==========
    // Handle fragment URL untuk auto-switch section ketika terjadi redirect
    const hash = window.location.hash;
    if (hash) {
        const sectionName = hash.replace('#', '').replace('-section', '');
        console.log('Detected hash:', hash, 'Section:', sectionName);
        
        // Daftar section yang valid
        const validSections = ['create', 'edit', 'delete', 'master', 'destinasi', 'import', 'users', 'statistics'];
        
        if (validSections.includes(sectionName)) {
            // Hide semua section
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            
            // Show section yang dituju
            const targetSection = document.getElementById(sectionName + '-section');
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            // Update active state pada sidebar
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('onclick')?.includes(`'${sectionName}'`)) {
                    link.classList.add('active');
                }
            });
            
            // Scroll smooth ke section
            setTimeout(() => {
                targetSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }


  // Konfirmasi sederhana saat klik Logout
  document.addEventListener('DOMContentLoaded', function () {
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
      logoutLink.addEventListener('click', function (e) {
        const ok = confirm('Apakah Anda yakin ingin logout?');
        if (!ok) {
          e.preventDefault();
        }
      });
    }
  });
  // Validasi maksimal 5 gambar pada form Buat Postingan
const createGambarInput = document.getElementById('createGambar');
if (createGambarInput) {
    createGambarInput.addEventListener('change', function(e) {
        const files = e.target.files;
        const preview = document.getElementById('createImagePreview');
        
        // Validasi jumlah file
        if (files.length > 5) {
            alert('Maksimal 5 gambar yang diunggah');
            e.target.value = ''; // Reset input
            preview.innerHTML = '';
            return;
        }
        
        // Tampilkan preview gambar
        preview.innerHTML = '';
        if (files.length > 0) {
            preview.innerHTML = `<div class="alert alert-info"><i class="fas fa-images"></i> ${files.length} gambar dipilih</div>`;
            
            // Tampilkan nama file
            let fileList = '<small class="text-muted">File yang dipilih:<br>';
            for (let i = 0; i < files.length; i++) {
                fileList += `${i + 1}. ${files[i].name}<br>`;
            }
            fileList += '</small>';
            preview.innerHTML += fileList;
        }
    });
}

    </script>
</body>
</html>