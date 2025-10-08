<?php
// Menampilkan pesan sukses/error jika ada
$session = session();

// Pastikan variabel berikut dikirim dari controller:
// $user            = array user saat ini
// $kotaKecamatan   = [ "Kota A" => ["Kec 1","Kec 2",...], ... ]
// $bookmarkCount   = integer jumlah bookmark user

$currentKota = $user['Kota'] ?? '';
$currentKec  = $user['Kecamatan'] ?? '';
$kotaKeys    = array_keys($kotaKecamatan ?? []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Profil - Akun Pengguna</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= base_url('css/profil.css') ?>">

  <style>
    /* Bookmark Count */
    .bookmark-count{
      background:#00d4aa;color:#fff;border-radius:50%;padding:2px 8px;font-size:.8rem;font-weight:bold;margin-left:auto
    }
    .bookmark-section{cursor:pointer;padding:15px;border-radius:8px;transition:background-color .3s ease;display:flex;align-items:center;gap:10px}
    .bookmark-section:hover{background:rgba(255,255,255,.1)}
    
    /* Section Navigation - TAMBAHAN */
    .section-nav {
      display: flex;
      flex-direction: column;
      gap: 5px;
      margin-top: 20px;
    }
    
    .section-nav-item {
      cursor: pointer;
      padding: 15px;
      border-radius: 8px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 10px;
      color: rgba(255, 255, 255, 0.8);
    }
    
    .section-nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
    }
    
    .section-nav-item.active {
      background: #00d4aa;
      color: #fff;
      font-weight: 500;
    }
    
    /* Content Sections - TAMBAHAN */
    .content-section {
      display: none;
    }
    
    .content-section.active {
      display: block;
    }

    /* Bookmark Table Styles - TAMBAHAN */
    .bookmark-table-container {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .bookmark-table-header {
      background: #fff;
      color: #333;
      padding: 20px 30px;
      border-bottom: 1px solid #dee2e6;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .bookmark-table-header h2 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 600;
      color: #333;
    }
    
    .bookmark-stats {
      background: #f8f9fa;
      color: #666;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.85rem;
      border: 1px solid #dee2e6;
    }

    .bookmark-table {
      width: 100%;
      margin: 0;
      border: none;
      background: #fff;
    }
    
    .bookmark-table thead th {
      background: #f8f9fa;
      border: none;
      padding: 15px 20px;
      font-weight: 600;
      color: #495057;
      border-bottom: 1px solid #dee2e6;
      font-size: 0.9rem;
      text-align: left;
    }
    
    .bookmark-table tbody td {
      padding: 15px 20px;
      border-bottom: 1px solid #f1f3f4;
      vertical-align: middle;
      border-left: none;
      border-right: none;
      background: #fff;
    }
    
    .bookmark-table tbody tr:hover {
      background: #f8f9fa;
      transition: background-color 0.2s ease;
    }
    
    .bookmark-table tbody tr:last-child td {
      border-bottom: none;
    }

    .bookmark-card-mini {
      display: block;
    }
    
    .bookmark-image-mini {
      display: none;
    }
    
    .bookmark-info-mini h6 {
      margin: 0 0 4px;
      font-weight: 600;
      color: #333;
      font-size: 0.95rem;
      line-height: 1.3;
    }
    
    .bookmark-info-mini p {
      margin: 0;
      color: #666;
      font-size: 0.8rem;
      line-height: 1.4;
    }
    
    .bookmark-category-badge {
      background: #00d4aa;
      color: #fff;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 500;
      display: inline-block;
      text-align: center;
      min-width: 60px;
    }
    
    .bookmark-actions {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
    }
    
    .bookmark-actions .btn {
      font-size: 0.75rem;
      padding: 5px 10px;
      border-radius: 4px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
      border: 1px solid;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    
    .bookmark-actions .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bookmark-table .empty-bookmarks {
      text-align: center;
      padding: 40px 20px;
      color: #666;
    }
    
    .bookmark-table .empty-bookmarks i {
      font-size: 3rem;
      color: #ccc;
      margin-bottom: 15px;
    }
    
    .bookmark-table .empty-bookmarks h4 {
      margin-bottom: 10px;
      color: #333;
      font-weight: 600;
      font-size: 1.2rem;
    }
    
    .bookmark-table .empty-bookmarks p {
      margin-bottom: 20px;
      color: #666;
      font-size: 1rem;
    }

    .loading-bookmarks {
      text-align: center;
      padding: 40px 20px;
      color: #666;
      background: #fff;
    }
    
    .loading-spinner {
      display: inline-block;
      width: 30px;
      height: 30px;
      border: 3px solid #f3f3f3;
      border-top: 3px solid #007bff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 15px;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .save-btn.loading{background:#6c757d!important;color:#fff!important;border-color:#6c757d!important}
    .save-btn.success{background:#28a745!important;color:#fff!important;border-color:#28a745!important}
    /* Layout kecil */
    @media (max-width:768px){
      .bookmark-modal-content{width:95%;margin:20px}
      .bookmark-modal-header{padding:15px 20px}
      .bookmark-modal-header h3{font-size:1.3rem}
      .bookmark-modal-body{padding:20px}
      .bookmarks-grid{grid-template-columns:1fr;gap:15px}
      .bookmark-actions{flex-direction:column}
      .bookmark-actions .btn{flex:none}
    }
  </style>
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
          <div class="user-name"><?= esc($user['Username']); ?></div>
        </div>

        <!-- Section Navigation - DIUBAH dari bookmark section ke navigation -->
        <div class="section-nav">
          <div class="section-nav-item active" onclick="showSection('profile')" id="nav-profile">
            <div class="nav-icon">
              <i class="fas fa-user"></i>
            </div>
            <span class="nav-text">Profil Saya</span>
          </div>
          
          <div class="section-nav-item" onclick="showSection('bookmarks')" id="nav-bookmarks">
            <div class="nav-icon">
              <i class="fas fa-bookmark"></i>
            </div>
            <span class="nav-text">Bookmark</span>
            <span class="bookmark-count" id="bookmarkCount"><?= (int)($bookmarkCount ?? 0) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Profile Section -->
      <div class="content-section active" id="section-profile">
        <div class="profile-form-container">
          <h1 class="profile-title">Akun Pengguna</h1>

          <?php if ($session->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $session->getFlashdata('success') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php elseif ($session->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= $session->getFlashdata('error') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form id="profileForm" action="<?= base_url('/updateProfil') ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <!-- Username -->
            <div class="form-group">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" value="<?= esc($user['Username']); ?>" readonly>
              <small class="text-muted">Username tidak dapat diubah</small>
            </div>

            <!-- Email -->
            <div class="form-group">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" value="<?= esc($user['Email']); ?>" required>
              <div class="invalid-feedback">Email tidak valid</div>
            </div>

            <!-- Nama Asli -->
            <div class="form-group">
              <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama_asli" class="form-control" value="<?= esc($user['Nama_Asli']); ?>" required>
              <div class="invalid-feedback">Nama lengkap wajib diisi</div>
            </div>

            <!-- Alamat -->
            <div class="form-group">
              <label class="form-label">Alamat <span class="text-danger">*</span></label>
              <input type="text" name="alamat" class="form-control" value="<?= esc($user['Alamat']); ?>" required>
              <div class="invalid-feedback">Alamat wajib diisi</div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="form-group">
              <label class="form-label">Jenis kelamin <span class="text-danger">*</span></label>
              <select name="jenis_kelamin" class="form-select" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-Laki" <?= ($user['Jenis_Kelamin'] ?? '') === 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                <option value="Perempuan" <?= ($user['Jenis_Kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
              </select>
              <div class="invalid-feedback">Jenis kelamin wajib dipilih</div>
            </div>

            <!-- Kota/Kab dan Kecamatan -->
            <div class="row-fields">
              <div class="form-group">
                <label class="form-label">Kota / Kabupaten <span class="text-danger">*</span></label>
                <select name="kota" id="kota" class="form-select" required>
                  <?php
                    if ($currentKota && !in_array($currentKota, $kotaKeys, true)) {
                      echo '<option value="'.esc($currentKota).'">'.esc($currentKota).' (tersimpan)</option>';
                      echo '<option disabled>──────────</option>';
                    } else {
                      echo '<option value="">Pilih Kota/Kabupaten</option>';
                    }

                    foreach ($kotaKeys as $k) {
                        $sel = ($k === $currentKota) ? 'selected' : '';
                        echo '<option value="'.esc($k).'" '.$sel.'>'.esc($k).'</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback">Kota/Kabupaten wajib dipilih</div>
              </div>

              <div class="form-group">
                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                <select name="kecamatan" id="kecamatan" class="form-select" required>
                  <?php
                    if ($currentKota && isset($kotaKecamatan[$currentKota]) && is_array($kotaKecamatan[$currentKota])) {
                        $kecs = $kotaKecamatan[$currentKota];
                        if ($currentKec && !in_array($currentKec, $kecs, true)) {
                            echo '<option value="'.esc($currentKec).'" selected>'.esc($currentKec).' (tersimpan)</option>';
                            echo '<option disabled>──────────</option>';
                        }
                        foreach ($kecs as $kc) {
                            $sel = ($kc === $currentKec) ? 'selected' : '';
                            echo '<option value="'.esc($kc).'" '.$sel.'>'.esc($kc).'</option>';
                        }
                    } else {
                        if ($currentKec) {
                            echo '<option value="'.esc($currentKec).'" selected>'.esc($currentKec).' (tersimpan)</option>';
                        } else {
                            echo '<option value="">Pilih Kecamatan</option>';
                        }
                    }
                  ?>
                </select>
                <div class="invalid-feedback">Kecamatan wajib dipilih</div>
              </div>
            </div>

            <!-- Password -->
            <div class="form-group">
              <label class="form-label">Password Baru</label>
              <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                  <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
              </div>
              <small class="text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</small>
              <div class="invalid-feedback">Password minimal 6 karakter</div>
            </div>

            <!-- Nomor Telepon -->
            <div class="form-group">
              <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
              <input type="tel" name="nomor_telepon" class="form-control" value="<?= esc($user['Nomor_Telepon'] ?? ''); ?>" required>
              <div class="invalid-feedback">Nomor telepon wajib diisi</div>
            </div>
            <button type="submit" class="btn btn-primary save-btn">
              <i class="fas fa-save"></i> Simpan Perubahan
            </button>
          </form>
        </div>
      </div>

      <!-- Bookmark Section - SECTION BARU -->
      <div class="content-section" id="section-bookmarks">
        <div class="bookmark-table-container">
          <div class="bookmark-table-header">
            <h2><i class="fas fa-bookmark me-2"></i>Destinasi Favorit Saya</h2>
            <div class="bookmark-stats">
              <span id="bookmarkCountDisplay"><?= (int)($bookmarkCount ?? 0) ?></span> Destinasi
            </div>
          </div>
          
          <div id="bookmarkContent">
            <!-- Loading state initial -->
            <div class="loading-bookmarks">
              <div class="loading-spinner"></div>
              <p>Memuat bookmark...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    // ========= Preload data kota/kecamatan dari server (tanpa fetch) =========
    const KOTA_KECAMATAN = <?= json_encode($kotaKecamatan ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
    const CURRENT_KOTA   = <?= json_encode($currentKota, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
    const CURRENT_KEC    = <?= json_encode($currentKec, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

    const kotaSelect = document.getElementById('kota');
    const kecSelect  = document.getElementById('kecamatan');

    // Ganti daftar kecamatan saat kota berubah
    kotaSelect.addEventListener('change', () => {
      const kota = kotaSelect.value;
      const kecList = Array.isArray(KOTA_KECAMATAN[kota]) ? KOTA_KECAMATAN[kota] : [];
      kecSelect.innerHTML = '';

      if (!kota) {
        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        return;
      }

      const opt0 = document.createElement('option');
      opt0.value = '';
      opt0.textContent = 'Pilih Kecamatan';
      kecSelect.appendChild(opt0);

      kecList.forEach(kc => {
        const opt = document.createElement('option');
        opt.value = kc;
        opt.textContent = kc;
        kecSelect.appendChild(opt);
      });
    });

    // ========= Section Navigation - FUNGSI BARU =========
    function showSection(sectionName) {
      // Hide all sections
      document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
      });
      
      // Remove active class from all nav items
      document.querySelectorAll('.section-nav-item').forEach(nav => {
        nav.classList.remove('active');
      });
      
      // Show selected section
      document.getElementById(`section-${sectionName}`).classList.add('active');
      document.getElementById(`nav-${sectionName}`).classList.add('active');
      
      // Load bookmarks if bookmark section is selected
      if (sectionName === 'bookmarks') {
        loadBookmarks();
      }
    }

    // ========= Load Bookmarks - FUNGSI BARU =========
    function loadBookmarks() {
      const content = document.getElementById('bookmarkContent');
      content.innerHTML = `
        <div class="loading-bookmarks">
          <div class="loading-spinner"></div>
          <p>Memuat bookmark...</p>
        </div>
      `;

      fetch('<?= base_url('bookmark/user-bookmarks') ?>')
        .then(response => {
          if (!response.ok) throw new Error(response.status);
          return response.json();
        })
        .then(data => {
          if (data.status === 'success') {
            displayBookmarks(data.data);
          } else {
            throw new Error('Gagal memuat data');
          }
        })
        .catch(() => {
          content.innerHTML = `
            <div class="empty-bookmarks">
              <i class="fas fa-exclamation-triangle"></i>
              <h4>Error</h4>
              <p>Gagal memuat data bookmark. Coba lagi nanti.</p>
              <button class="btn btn-primary" onclick="loadBookmarks()">Coba Lagi</button>
            </div>
          `;
        });
    }

    function displayBookmarks(bookmarks) {
  const content = document.getElementById('bookmarkContent');
  
  // Jika tidak ada bookmark
  if (!bookmarks || bookmarks.length === 0) {
    content.innerHTML = `
      <table class="table bookmark-table">
        <thead>
          <tr>
            <th style="width: 40%;">Destinasi Wisata</th>
            <th style="width: 15%;">Kategori</th>
            <th style="width: 25%;">Lokasi</th>
            <th style="width: 20%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="4" class="text-center py-5">
              <div class="empty-bookmarks">
                <i class="far fa-bookmark"></i>
                <h4>Belum Ada Bookmark</h4>
                <p>Mulai jelajahi destinasi dan tambahkan ke favorit Anda.</p>
                <a href="<?= base_url('/') ?>" class="btn btn-primary">Jelajahi Sekarang</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    `;
    return;
  }

  let tableHTML = `
    <table class="table bookmark-table">
      <thead>
        <tr>
          <th style="width: 40%;">Destinasi Wisata</th>
          <th style="width: 15%;">Kategori</th>
          <th style="width: 25%;">Lokasi</th>
          <th style="width: 20%;">Aksi</th>
        </tr>
      </thead>
      <tbody>
  `;

  bookmarks.forEach((bookmark) => {
    // Potong alamat jika terlalu panjang
    const alamat = bookmark.alamat || 'Alamat tidak tersedia';
    const alamatShort = alamat.length > 80 ? alamat.substring(0, 80) + '...' : alamat;
    
    tableHTML += `
      <tr>
        <td>
          <div class="bookmark-card-mini">
            <div class="bookmark-info-mini">
              <h6>${bookmark.nama_wisata || 'Nama tidak tersedia'}</h6>
              <p class="text-muted small mb-0">
                <i class="fas fa-map-marker-alt me-1"></i>
                ${alamatShort}
              </p>
            </div>
          </div>
        </td>
        <td>
          <span class="bookmark-category-badge">${bookmark.nama_kategori || 'Kategori'}</span>
        </td>
        <td>
          <div class="text-muted small">
            <div><i class="fas fa-building me-1"></i>${bookmark.nama_kecamatan || 'Kecamatan tidak tersedia'}</div>
            <div><i class="fas fa-city me-1"></i>${bookmark.nama_kota || 'Kota tidak tersedia'}</div>
          </div>
        </td>
        <td>
          <div class="bookmark-actions">
            <a href="<?= base_url('detail/') ?>${bookmark.wisata_id}" 
               class="btn btn-sm btn-outline-primary" 
               title="Lihat Detail">
              <i class="fas fa-eye"></i> Detail
            </a>
            <button class="btn btn-sm btn-outline-danger ms-1" 
                    onclick="removeBookmark(${bookmark.wisata_id})" 
                    title="Hapus Bookmark">
              <i class="fas fa-trash"></i> Hapus
            </button>
          </div>
        </td>
      </tr>
    `;
  });

  tableHTML += `
      </tbody>
    </table>
  `;

  content.innerHTML = tableHTML;
}

    function removeBookmark(wisataId) {
      if (!confirm('Apakah Anda yakin ingin menghapus bookmark ini?')) return;
      
      const formData = new FormData();
      formData.append('_method', 'DELETE');

      fetch(`<?= base_url('bookmark/remove/') ?>${wisataId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      })
      .then(response => {
        if (!response.ok) throw new Error(response.status);
        return response.json();
      })
      .then(data => {
        if (data.status === 'success') {
          const counter = document.getElementById('bookmarkCount');
          const displayCounter = document.getElementById('bookmarkCountDisplay');
          const current = parseInt(counter.textContent || '0', 10);
          const newCount = Math.max(current - 1, 0);
          
          counter.textContent = newCount;
          displayCounter.textContent = newCount;
          
          loadBookmarks();
          alert('Bookmark berhasil dihapus');
        } else {
          throw new Error('Gagal menghapus');
        }
      })
      .catch(() => {
        alert('Terjadi kesalahan saat menghapus bookmark');
      });
    }

    // Toggle password visibility
    function togglePassword(){
      const pwd = document.getElementById('password');
      const eye = document.getElementById('eyeIcon');
      if (pwd.type === 'password'){ pwd.type = 'text'; eye.className = 'fas fa-eye-slash'; }
      else { pwd.type = 'password'; eye.className = 'fas fa-eye'; }
    }

    // Stub untuk tombol menu mobile (sesuaikan dengan CSS/profil.css-mu)
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    function toggleSidebar(){
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    }

    function closeSidebar(){
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    }

    // Tutup sidebar kalau klik overlay
    overlay.addEventListener('click', closeSidebar);

    // Tutup sidebar dengan tombol ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeSidebar();
      }
    });

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.alert.alert-dismissible').forEach(alert => {
        setTimeout(() => { try{ new bootstrap.Alert(alert).close(); }catch(_){ } }, 5000);
      });
    });

    // Biar fungsi bisa dipanggil inline dari HTML
    window.toggleSidebar = toggleSidebar;
    window.closeSidebar = closeSidebar;
    window.showSection = showSection;
    window.loadBookmarks = loadBookmarks;
    window.removeBookmark = removeBookmark;
    window.togglePassword = togglePassword;
  </script>
</body>
</html>