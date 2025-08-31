<?php 
// Menampilkan pesan sukses/error jika ada
$session = session();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Akun Pengguna</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/profil.css') ?>">
    <style>
        /* Bookmark Count */
        .bookmark-count { background:#00d4aa;color:#fff;border-radius:50%;padding:2px 8px;font-size:.8rem;font-weight:bold;margin-left:auto }
        .bookmark-section { cursor:pointer;padding:15px;border-radius:8px;transition:background-color .3s ease;display:flex;align-items:center;gap:10px }
        .bookmark-section:hover { background:rgba(255,255,255,.1) }

        /* Bookmark Modal */
        .bookmark-modal{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);z-index:10000;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .3s ease}
        .bookmark-modal.show{opacity:1}
        .bookmark-modal-content{background:#fff;border-radius:15px;max-width:900px;width:90%;max-height:80vh;overflow:hidden;transform:scale(.9);transition:transform .3s ease}
        .bookmark-modal.show .bookmark-modal-content{transform:scale(1)}
        .bookmark-modal-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:20px 30px;display:flex;justify-content:space-between;align-items:center}
        .bookmark-modal-header h3{margin:0;font-size:1.5rem}
        .close-btn{background:none;border:none;color:#fff;font-size:1.5rem;cursor:pointer;padding:5px;transition:transform .3s ease}
        .close-btn:hover{transform:scale(1.1)}
        .bookmark-modal-body{padding:30px;max-height:60vh;overflow-y:auto}

        /* Empty State */
        .empty-bookmarks{text-align:center;padding:40px 20px;color:#666}
        .empty-bookmarks i{font-size:4rem;color:#ddd;margin-bottom:20px}
        .empty-bookmarks h4{margin-bottom:10px;color:#333}
        .empty-bookmarks p{margin-bottom:20px;color:#666}

        /* Bookmarks Grid */
        .bookmarks-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px}
        .bookmark-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,.1);transition:transform .3s ease,box-shadow .3s ease}
        .bookmark-card:hover{transform:translateY(-5px);box-shadow:0 8px 25px rgba(0,0,0,.15)}
        .bookmark-image{height:150px;overflow:hidden}
        .bookmark-image img{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
        .bookmark-card:hover .bookmark-image img{transform:scale(1.05)}
        .bookmark-info{padding:15px}
        .bookmark-info h5{margin:0 0 8px 0;font-weight:600;color:#333;font-size:1.1rem}
        .bookmark-info p{margin:0 0 10px 0;color:#666;font-size:.9rem}
        .bookmark-info p i{color:#00d4aa;margin-right:5px}
        .bookmark-category{background:linear-gradient(135deg,#00d4aa,#00b894);color:#fff;padding:4px 12px;border-radius:20px;font-size:.8rem;font-weight:500;display:inline-block;margin-bottom:15px}
        .bookmark-actions{display:flex;gap:10px}
        .bookmark-actions .btn{flex:1;padding:8px 12px;border:none;border-radius:6px;font-size:.85rem;font-weight:500;text-decoration:none;text-align:center;transition:all .3s ease;cursor:pointer}
        .btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
        .btn-primary:hover{background:linear-gradient(135deg,#5a6fd8,#6a4190);transform:translateY(-1px)}
        .btn-danger{background:linear-gradient(135deg,#ff6b6b,#ee5a24);color:#fff}
        .btn-danger:hover{background:linear-gradient(135deg,#ff5252,#e84118);transform:translateY(-1px)}
        .save-btn.loading{background:#6c757d!important;color:#fff!important;border-color:#6c757d!important}
        .save-btn.success{background:#28a745!important;color:#fff!important;border-color:#28a745!important}

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
                    <div class="user-name"><?= esc($user['Nama_Asli']); ?></div> 
                </div>

                <!-- Bookmark -->
                <div class="bookmark-section" onclick="showBookmarks()">
                    <div class="bookmark-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <span class="bookmark-text">Bookmark</span>
                    <span class="bookmark-count" id="bookmarkCount">0</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
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
                            <option value="Laki-Laki" <?= $user['Jenis_Kelamin'] == 'Laki-Laki' ? 'selected' : ''; ?>>Laki-Laki</option>
                            <option value="Perempuan" <?= $user['Jenis_Kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                        <div class="invalid-feedback">Jenis kelamin wajib dipilih</div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="tel" name="nomor_telepon" class="form-control" value="<?= esc($user['Nomor_Telepon']); ?>" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi</div>
                    </div>

                    <!-- Kota/Kab dan Kecamatan (Dinamik via JSON) -->
                    <div class="row-fields">
                        <div class="form-group">
                            <label class="form-label">Kota / Kabupaten <span class="text-danger">*</span></label>
                            <select name="kota" id="kota" class="form-select" required>
                                <option value="">Pilih Kota/Kabupaten</option>
                                <!-- diisi dinamik -->
                            </select>
                            <div class="invalid-feedback">Kota/Kabupaten wajib dipilih</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <select name="kecamatan" id="kecamatan" class="form-select" required>
                                <option value="">Pilih Kecamatan</option>
                                <!-- diisi dinamik -->
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

                    <!-- Tombol Simpan -->
                    <button type="submit" class="btn btn-primary save-btn">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/profil.js') ?>"></script>
    
    <script>
       // ========================
  // Dinamik Kota/Kecamatan (JSON) + prefill nilai tersimpan
  // ========================
  const DATA_URL   = "<?= base_url('data/kota_kecamatan.json') ?>";
  const kotaSelect = document.getElementById('kota');
  const kecSelect  = document.getElementById('kecamatan');

  // Nilai dari DB (tersimpan saat ini)
  const CURRENT_KOTA = "<?= esc($user['Kota']) ?>";
  const CURRENT_KEC  = "<?= esc($user['Kecamatan']) ?>";

  // 1) Tampilkan dulu nilai tersimpan (sebelum fetch JSON)
  function showSavedFirst() {
    kotaSelect.innerHTML = '';
    const optKota = document.createElement('option');
    optKota.value = CURRENT_KOTA || '';
    optKota.textContent = CURRENT_KOTA ? `${CURRENT_KOTA} (tersimpan)` : 'Pilih Kota/Kabupaten';
    optKota.selected = true;
    kotaSelect.appendChild(optKota);

    kecSelect.innerHTML = '';
    const optKec = document.createElement('option');
    optKec.value = CURRENT_KEC || '';
    optKec.textContent = CURRENT_KEC ? `${CURRENT_KEC} (tersimpan)` : 'Pilih Kecamatan';
    optKec.selected = true;
    kecSelect.appendChild(optKec);
  }

  // Helper isi select generik
  function fillSelect(selectEl, items, placeholder, selectedValue = '') {
    selectEl.innerHTML = '';
    const opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = placeholder || 'Pilih';
    selectEl.appendChild(opt0);

    items.forEach(({ value, label }) => {
      const opt = document.createElement('option');
      opt.value = value;
      opt.textContent = label;
      if (String(value) === String(selectedValue)) opt.selected = true;
      selectEl.appendChild(opt);
    });
  }

  function fillKecamatanFromJSON(kotaKey, dataJSON, preselect = '') {
    const list = dataJSON[kotaKey] || [];
    const kecItems = list.slice().sort((a,b)=>a.localeCompare(b,'id')).map(n => ({ value:n, label:n }));
    fillSelect(kecSelect, kecItems, 'Pilih Kecamatan', preselect);
  }

  let _cacheData = {};

  document.addEventListener('DOMContentLoaded', async () => {
    // Tampilkan nilai tersimpan dulu
    showSavedFirst();

    // Lalu fetch JSON
    try {
      const res = await fetch(DATA_URL, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Gagal memuat data wilayah');
      _cacheData = await res.json();
    } catch (e) {
      console.error(e);
      _cacheData = {};
      // Jika gagal load JSON, tetap biarkan nilai tersimpan tampil, user masih bisa submit tanpa ubah.
      return;
    }

    // 2) Populate dropdown kota dari JSON
    const kotaItems = Object.keys(_cacheData)
      .sort((a,b)=>a.localeCompare(b,'id'))
      .map(name => ({ value:name, label:name }));

    // Apakah kota tersimpan ada di JSON?
    const savedKotaInJSON = CURRENT_KOTA && _cacheData[CURRENT_KOTA];

    if (savedKotaInJSON) {
      // Bangun list normal + set pilihan ke CURRENT_KOTA
      fillSelect(kotaSelect, kotaItems, 'Pilih Kota/Kabupaten', CURRENT_KOTA);
      // Isi kecamatan sesuai kota tersimpan + preselect CURRENT_KEC
      fillKecamatanFromJSON(CURRENT_KOTA, _cacheData, CURRENT_KEC);
    } else {
      // Nama kota lama tidak cocok dengan key JSON → tetap tampilkan opsi (tersimpan) + divider + list JSON
      kotaSelect.innerHTML = '';

      // Opsi nilai tersimpan (tetap sebagai selected)
      const optSaved = document.createElement('option');
      optSaved.value = CURRENT_KOTA || '';
      optSaved.textContent = CURRENT_KOTA ? `${CURRENT_KOTA} (tersimpan)` : 'Pilih Kota/Kabupaten';
      optSaved.selected = true;
      kotaSelect.appendChild(optSaved);

      // Divider (disabled)
      const optDivider = document.createElement('option');
      optDivider.disabled = true;
      optDivider.textContent = '──────────';
      kotaSelect.appendChild(optDivider);

      // Tambahkan semua kota dari JSON
      kotaItems.forEach(({value,label}) => {
        const opt = document.createElement('option');
        opt.value = value;
        opt.textContent = label;
        kotaSelect.appendChild(opt);
      });

      // Untuk kecamatan: pertahankan nilai tersimpan sampai user ganti kota
      kecSelect.innerHTML = '';
      const optKecSaved = document.createElement('option');
      optKecSaved.value = CURRENT_KEC || '';
      optKecSaved.textContent = CURRENT_KEC ? `${CURRENT_KEC} (tersimpan)` : 'Pilih Kecamatan';
      optKecSaved.selected = true;
      kecSelect.appendChild(optKecSaved);
    }

    // 3) Saat kota diubah, isi kecamatan dari JSON (menghilangkan label “tersimpan”)
    kotaSelect.addEventListener('change', () => {
      const kotaKey = kotaSelect.value;
      if (!kotaKey) {
        // reset kecamatan bila user memilih placeholder
        fillSelect(kecSelect, [], 'Pilih Kecamatan', '');
        return;
      }
      fillKecamatanFromJSON(kotaKey, _cacheData, '');
    });

            // Bookmark Counter & Alerts
            loadBookmarkCount();
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }

        // ============ BOOKMARK FUNCTIONS ============
        function loadBookmarkCount() {
            fetch('<?= base_url('wishlist/user-bookmarks') ?>')
              .then(r => { if(!r.ok) throw new Error(r.status); return r.json(); })
              .then(data => {
                  document.getElementById('bookmarkCount').textContent =
                      (data.status === 'success') ? data.count : '0';
              })
              .catch(() => { document.getElementById('bookmarkCount').textContent = '0'; });
        }

        function showBookmarks() {
            fetch('<?= base_url('wishlist/user-bookmarks') ?>')
              .then(r => { if(!r.ok) throw new Error(r.status); return r.json(); })
              .then(data => {
                  if (data.status === 'success') displayBookmarksModal(data.data);
                  else alert('Gagal memuat bookmark');
              })
              .catch(() => alert('Terjadi kesalahan saat memuat bookmark'));
        }

        function displayBookmarksModal(bookmarks) {
            let modalHTML = `
                <div class="bookmark-modal" id="bookmarkModal">
                    <div class="bookmark-modal-content">
                        <div class="bookmark-modal-header">
                            <h3><i class="fas fa-bookmark"></i> Destinasi Favorit Saya</h3>
                            <button onclick="closeBookmarkModal()" class="close-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="bookmark-modal-body">`;
            if (bookmarks.length === 0) {
                modalHTML += `
                    <div class="empty-bookmarks">
                        <i class="far fa-bookmark"></i>
                        <h4>Belum Ada Bookmark</h4>
                        <p>Mulai jelajahi destinasi wisata dan tambahkan ke bookmark favorit Anda!</p>
                        <a href="<?= base_url('/rekreasi') ?>" class="btn btn-primary">Jelajahi Sekarang</a>
                    </div>`;
            } else {
                modalHTML += `<div class="bookmarks-grid">`;
                bookmarks.forEach(b => {
                    const imageUrl = b.primary_image 
                        ? `<?= base_url('uploads/wisata/') ?>${b.primary_image}`
                        : `<?= base_url('images/default-wisata.jpg') ?>`;
                    modalHTML += `
                        <div class="bookmark-card">
                            <div class="bookmark-image">
                                <img src="${imageUrl}" alt="${b.nama_wisata}" onerror="this.src='<?= base_url('images/default-wisata.jpg') ?>'">
                            </div>
                            <div class="bookmark-info">
                                <h5>${b.nama_wisata}</h5>
                                <p><i class="fas fa-map-marker-alt"></i> ${b.nama_kecamatan}, ${b.nama_kota}</p>
                                <span class="bookmark-category">${b.nama_kategori}</span>
                                <div class="bookmark-actions">
                                    <a href="<?= base_url('detail/') ?>${b.wisata_id}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                    <button onclick="removeBookmark(${b.wisata_id})" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>`;
                });
                modalHTML += `</div>`;
            }
            modalHTML += `</div></div></div>`;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            setTimeout(() => document.getElementById('bookmarkModal').classList.add('show'), 100);
        }

        function closeBookmarkModal() {
            const modal = document.getElementById('bookmarkModal');
            if (!modal) return;
            modal.classList.remove('show');
            setTimeout(()=>modal.remove(), 300);
        }

        function removeBookmark(wisataId) {
            if (!confirm('Apakah Anda yakin ingin menghapus bookmark ini?')) return;
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            fetch(`<?= base_url('wishlist/remove/') ?>${wisataId}`, {
                method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:formData
            })
            .then(r => { if(!r.ok) throw new Error(r.status); return r.json(); })
            .then(data => {
                if (data.status === 'success') {
                    closeBookmarkModal(); showBookmarks(); loadBookmarkCount();
                    alert('Bookmark berhasil dihapus');
                } else alert('Gagal menghapus bookmark');
            })
            .catch(()=> alert('Terjadi kesalahan sistem'));
        }

        // Tutup modal jika klik overlay
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('bookmarkModal');
            if (modal && e.target === modal) closeBookmarkModal();
        });
    </script>
</body>
</html>
