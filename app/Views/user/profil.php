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
  Bookmark Count .bookmark-count {
    background: #00d4aa;
    color: #fff;
    border-radius: 50%;
    padding: 2px 8px;
    font-size: 0.8rem;
    font-weight: bold;
    margin-left: auto;
  }
  .bookmark-section {
    cursor: pointer;
    padding: 15px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .bookmark-section:hover {
    background: rgba(255, 255, 255, 0.1);
  }

  /* Bookmark Modal */
  .bookmark-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  .bookmark-modal.show {
    opacity: 1;
  }
  .bookmark-modal-content {
    background: #fff;
    border-radius: 15px;
    max-width: 900px;
    width: 90%;
    max-height: 80vh;
    overflow: hidden;
    transform: scale(0.9);
    transition: transform 0.3s ease;
  }
  .bookmark-modal.show .bookmark-modal-content {
    transform: scale(1);
  }
  .bookmark-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .bookmark-modal-header h3 {
    margin: 0;
    font-size: 1.5rem;
  }
  .close-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    transition: transform 0.3s ease;
  }
  .close-btn:hover {
    transform: scale(1.1);
  }
  .bookmark-modal-body {
    padding: 30px;
    max-height: 60vh;
    overflow-y: auto;
  }

  /* Empty State */
  .empty-bookmarks {
    text-align: center;
    padding: 40px 20px;
    color: #666;
  }
  .empty-bookmarks i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 20px;
  }
  .empty-bookmarks h4 {
    margin-bottom: 10px;
    color: #333;
  }
  .empty-bookmarks p {
    margin-bottom: 20px;
    color: #666;
  }

  /* Bookmarks Grid */
  .bookmarks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
  }
  .bookmark-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .bookmark-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }
  .bookmark-image {
    height: 150px;
    overflow: hidden;
  }
  .bookmark-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  .bookmark-card:hover .bookmark-image img {
    transform: scale(1.05);
  }
  .bookmark-info {
    padding: 15px;
  }
  .bookmark-info h5 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
  }
  .bookmark-info p {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 0.9rem;
  }
  .bookmark-info p i {
    color: #00d4aa;
    margin-right: 5px;
  }
  .bookmark-category {
    background: linear-gradient(135deg, #00d4aa, #00b894);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-block;
    margin-bottom: 15px;
  }
  .bookmark-actions {
    display: flex;
    gap: 10px;
  }
  .bookmark-actions .btn {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
  }
  .btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a4190);
    transform: translateY(-1px);
  }
  .btn-danger {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: #fff;
  }
  .btn-danger:hover {
    background: linear-gradient(135deg, #ff5252, #e84118);
    transform: translateY(-1px);
  }
  .save-btn.loading {
    background: #6c757d !important;
    color: #fff !important;
    border-color: #6c757d !important;
  }
  .save-btn.success {
    background: #28a745 !important;
    color: #fff !important;
    border-color: #28a745 !important;
  }

  @media (max-width: 768px) {
    .bookmark-modal-content {
      width: 95%;
      margin: 20px;
    }
    .bookmark-modal-header {
      padding: 15px 20px;
    }
    .bookmark-modal-header h3 {
      font-size: 1.3rem;
    }
    .bookmark-modal-body {
      padding: 20px;
    }
    .bookmarks-grid {
      grid-template-columns: 1fr;
      gap: 15px;
    }
    .bookmark-actions {
      flex-direction: column;
    }
    .bookmark-actions .btn {
      flex: none;
    }
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
/* =========================
   KONFIG & ELEMEN DOM
========================= */
const COMBINED_URL = "<?= base_url('data/kota_kecamatan.json') ?>"; // opsi file gabungan
const KAB_DIR      = "<?= base_url('data/kabupaten') ?>";           // folder kabupaten/*.json
const KEC_DIR      = "<?= base_url('data/kecamatan') ?>";           // folder kecamatan/{id}.json

const kotaSelect = document.getElementById('kota');
const kecSelect  = document.getElementById('kecamatan');

// Nilai tersimpan di DB
const CURRENT_KOTA = "<?= esc($user['Kota']) ?>";
const CURRENT_KEC  = "<?= esc($user['Kecamatan']) ?>";

/* =========================
   UTILITAS
========================= */
const norm = (s) => (s || '').trim().replace(/\s+/g, ' ');

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

function showSavedFirst() {
  // tampilkan “nilai tersimpan” agar user melihat data mereka sebelum JSON selesai di-load
  kotaSelect.innerHTML = '';
  const ok = document.createElement('option');
  ok.value = CURRENT_KOTA || '';
  ok.textContent = CURRENT_KOTA ? `${CURRENT_KOTA} (tersimpan)` : 'Pilih Kota/Kabupaten';
  ok.selected = true;
  kotaSelect.appendChild(ok);

  kecSelect.innerHTML = '';
  const okc = document.createElement('option');
  okc.value = CURRENT_KEC || '';
  okc.textContent = CURRENT_KEC ? `${CURRENT_KEC} (tersimpan)` : 'Pilih Kecamatan';
  okc.selected = true;
  kecSelect.appendChild(okc);
}

/* =========================
   MODE GABUNGAN
========================= */
async function tryLoadCombined() {
  try {
    const r = await fetch(COMBINED_URL, { headers:{ 'Accept':'application/json' }});
    if (!r.ok) return null; // file tidak ada / 404 → biarkan fallback
    const data = await r.json();
    // validasi minimal: harus objek { "Kota": [ "Kec1", ... ] }
    if (!data || Array.isArray(data) || typeof data !== 'object') return null;
    return data;
  } catch (e) {
    return null; // error jaringan / parse → fallback
  }
}

/* =========================
   MODE PER-FILE
========================= */
async function loadKabupatenAll() {
  // Sesuaikan daftar file kabupaten di server-mu (boleh dikurangi kalau tidak ada)
  const FILES_KAB = [
    '11.json','12.json','13.json','14.json','15.json','16.json','17.json','18.json','19.json','21.json',
    '31.json','32.json','33.json','34.json','35.json','36.json','51.json','52.json','53.json','61.json',
    '62.json','63.json','64.json','65.json','71.json','72.json','73.json','74.json','75.json','76.json',
    '81.json','82.json','91.json','92.json','94.json','1101.json','1102.json','1103.json','1104.json','1105.json',
    '1106.json','1107.json','1108.json','1109.json','1110.json','1111.json','1112.json','1113.json','1114.json','1115.json',
    '1116.json','1117.json','1118.json','1171.json','1172.json','1173.json','1174.json','1175.json','1201.json','1202.json',
    '1203.json','1204.json','1205.json','1206.json','1207.json','1208.json','1209.json','1210.json','1211.json','1212.json',
    '1213.json','1214.json','1215.json','1216.json','1217.json','1218.json','1219.json','1220.json','1221.json','1222.json',
    '1223.json','1224.json','1225.json','1271.json','1272.json','1273.json','1274.json','1275.json','1276.json','1277.json',
    '1278.json','1301.json','1302.json','1303.json','1304.json','1305.json','1306.json','1307.json','1308.json','1309.json',
    '1310.json','1311.json','1312.json','1371.json','1372.json','1373.json','1374.json','1375.json','1376.json','1377.json',
    '1401.json','1402.json','1403.json','1404.json','1405.json','1406.json','1407.json','1408.json','1409.json','1410.json',
    '1471.json','1472.json','1501.json','1502.json','1503.json','1504.json','1505.json','1506.json','1507.json','1508.json',
    '1509.json','1571.json','1572.json','1601.json','1602.json','1603.json','1604.json','1605.json','1606.json','1607.json',
    '1608.json','1609.json','1610.json','1611.json','1612.json','1613.json','1671.json','1672.json','1673.json','1674.json',
    '1701.json','1702.json','1703.json','1704.json','1705.json','1706.json','1707.json','1708.json','1709.json','1771.json',
    '1801.json','1802.json','1803.json','1804.json','1805.json','1806.json','1807.json','1808.json','1809.json','1810.json',
    '1811.json','1812.json','1813.json','1871.json','1872.json','1901.json','1902.json','1903.json','1904.json','1905.json',
    '1906.json','1971.json','2101.json','2102.json','2103.json','2104.json','2105.json','2171.json','2172.json','3101.json',
    '3171.json','3172.json','3173.json','3174.json','3175.json','3201.json','3202.json','3203.json','3204.json','3205.json',
    '3206.json','3207.json','3208.json','3209.json','3210.json','3211.json','3212.json','3213.json','3214.json','3215.json',
    '3216.json','3217.json','3218.json','3271.json','3272.json','3273.json','3274.json','3275.json','3276.json','3277.json',
    '3278.json','3279.json','3301.json','3302.json','3303.json','3304.json','3305.json','3306.json','3307.json','3308.json',
    '3309.json','3310.json','3311.json','3312.json','3313.json','3314.json','3315.json','3316.json','3317.json','3318.json',
    '3319.json','3320.json','3321.json','3322.json','3323.json','3324.json','3325.json','3326.json','3327.json','3328.json',
    '3329.json','3371.json','3372.json','3373.json','3374.json','3375.json','3376.json','3401.json','3402.json','3403.json',
    '3404.json','3471.json','3501.json','3502.json','3503.json','3504.json','3505.json','3506.json','3507.json','3508.json',
    '3509.json','3510.json','3511.json','3512.json','3513.json','3514.json','3515.json','3516.json','3517.json','3518.json',
    '3519.json','3520.json','3521.json','3522.json','3523.json','3524.json','3525.json','3526.json','3527.json','3528.json',
    '3529.json','3571.json','3572.json','3573.json','3574.json','3575.json','3576.json','3577.json','3578.json','3579.json',
    '3601.json','3602.json','3603.json','3604.json','3671.json','3672.json','3673.json','3674.json','5101.json','5102.json',
    '5103.json','5104.json','5105.json','5106.json','5107.json','5108.json','5171.json','5201.json','5202.json','5203.json',
    '5204.json','5205.json','5206.json','5207.json','5208.json','5271.json','5272.json','5301.json','5302.json','5303.json',
    '5304.json','5305.json','5306.json','5307.json','5308.json','5309.json','5310.json','5311.json','5312.json','5313.json',
    '5314.json','5315.json','5316.json','5317.json','5318.json','5319.json','5320.json','5321.json','5371.json','6101.json',
    '6102.json','6103.json','6104.json','6105.json','6106.json','6107.json','6108.json','6109.json','6110.json','6111.json',
    '6112.json','6171.json','6172.json','6201.json','6202.json','6203.json','6204.json','6205.json','6206.json','6207.json',
    '6208.json','6209.json','6210.json','6211.json','6212.json','6213.json','6271.json','6301.json','6302.json','6303.json',
    '6304.json','6305.json','6306.json','6307.json','6308.json','6309.json','6310.json','6311.json','6371.json','6372.json',
    '6401.json','6402.json','6403.json','6407.json','6408.json','6409.json','6411.json','6471.json','6472.json','6474.json',
    '6501.json','6502.json','6503.json','6504.json','6571.json','7101.json','7102.json','7103.json','7104.json','7105.json',
    '7106.json','7107.json','7108.json','7109.json','7110.json','7111.json','7171.json','7172.json','7173.json','7174.json',
    '7201.json','7202.json','7203.json','7204.json','7205.json','7206.json','7207.json','7208.json','7209.json','7210.json',
    '7211.json','7212.json','7271.json','7301.json','7302.json','7303.json','7304.json','7305.json','7306.json','7307.json',
    '7308.json','7309.json','7310.json','7311.json','7312.json','7313.json','7314.json','7315.json','7316.json','7317.json',
    '7318.json','7322.json','7324.json','7326.json','7371.json','7372.json','7373.json','7401.json','7402.json','7403.json',
    '7404.json','7405.json','7406.json','7407.json','7408.json','7409.json','7410.json','7411.json','7412.json','7413.json',
    '7414.json','7415.json','7471.json','7472.json','7501.json','7502.json','7503.json','7504.json','7505.json','7511.json',
    '7601.json','7602.json','7603.json','7604.json','7605.json','7606.json','8101.json','8102.json','8103.json','8104.json',
    '8105.json','8106.json','8107.json','8108.json','8109.json','8171.json','8172.json','8201.json','8202.json','8203.json',
    '8204.json','8205.json','8206.json','8207.json','8208.json','8271.json','8272.json','9101.json','9102.json','9103.json',
    '9104.json','9105.json','9106.json','9107.json','9108.json','9109.json','9110.json','9111.json','9112.json','9113.json',
    '9114.json','9115.json','9116.json','9117.json','9118.json','9119.json','9120.json','9121.json','9122.json','9123.json',
    '9124.json','9125.json','9126.json','9127.json','9128.json','9171.json','9201.json','9202.json','9203.json','9204.json',
    '9205.json','9206.json','9207.json','9208.json','9209.json','9210.json','9211.json','9212.json','9271.json'
  ];
  let all = [];
  for (const f of FILES_KAB) {
    try {
      const r = await fetch(`${KAB_DIR}/${f}`, { headers:{ 'Accept':'application/json' }});
      if (r.ok) {
        const arr = await r.json();
        if (Array.isArray(arr)) all = all.concat(arr);
      }
    } catch (e) {}
  }
  return all;
}

async function loadKecamatanByKabId(kabId) {
  try {
    const r = await fetch(`${KEC_DIR}/${kabId}.json`, { headers:{ 'Accept':'application/json' }});
    if (!r.ok) return [];
    const arr = await r.json();
    return Array.isArray(arr) ? arr : [];
  } catch (e) { return []; }
}

/* =========================
   INISIALISASI SETELAH REGION READY
========================= */
function afterRegionInit() {
  // Auto-hide alerts
  document.querySelectorAll('.alert.alert-dismissible').forEach(alert => {
    setTimeout(() => {
      try { new bootstrap.Alert(alert).close(); } catch (_) {}
    }, 5000);
  });
  // Bookmark counter
  loadBookmarkCount();
}

/* =========================
   BOOTSTRAP: DOMContentLoaded
========================= */
document.addEventListener('DOMContentLoaded', async () => {
  // tampilkan nilai tersimpan lebih dulu
  showSavedFirst();

  // Coba mode gabungan
  const combined = await tryLoadCombined();
  if (combined) {
    const kotaItems = Object.keys(combined)
      .sort((a,b)=>a.localeCompare(b,'id'))
      .map(name => ({ value:name, label:name }));

    // Jika kota tersimpan ada di JSON, langsung pre-fill kecamatannya
    if (CURRENT_KOTA && combined[CURRENT_KOTA]) {
      fillSelect(kotaSelect, kotaItems, 'Pilih Kota/Kabupaten', CURRENT_KOTA);
      const kecItems = (combined[CURRENT_KOTA] || [])
        .slice().sort((a,b)=>a.localeCompare(b,'id'))
        .map(n => ({ value:n, label:n }));
      fillSelect(kecSelect, kecItems, 'Pilih Kecamatan', CURRENT_KEC);
    } else {
      // tampilkan divider + semua kota
      kotaSelect.innerHTML = '';
      const optSaved = document.createElement('option');
      optSaved.value = CURRENT_KOTA || '';
      optSaved.textContent = CURRENT_KOTA ? `${CURRENT_KOTA} (tersimpan)` : 'Pilih Kota/Kabupaten';
      optSaved.selected = true;
      kotaSelect.appendChild(optSaved);

      const optDivider = document.createElement('option');
      optDivider.disabled = true;
      optDivider.textContent = '──────────';
      kotaSelect.appendChild(optDivider);

      kotaItems.forEach(({value,label}) => {
        const opt = document.createElement('option');
        opt.value = value; opt.textContent = label;
        kotaSelect.appendChild(opt);
      });
      // biarkan kecamatan “tersimpan” sampai user ganti kota
    }

    kotaSelect.addEventListener('change', () => {
      const kotaKey = kotaSelect.value;
      if (!kotaKey) { fillSelect(kecSelect, [], 'Pilih Kecamatan'); return; }
      const kecItems = (combined[kotaKey] || [])
        .slice().sort((a,b)=>a.localeCompare(b,'id'))
        .map(n => ({ value:n, label:n }));
      fillSelect(kecSelect, kecItems, 'Pilih Kecamatan');
    });

    afterRegionInit();
    return; // selesai di mode gabungan
  }

  // Fallback ke mode per-file
  const kotaNameToId = new Map();
  const kabAll = await loadKabupatenAll();

  const kotaItems2 = kabAll
    .filter(it => it && it.id && (it.nama || it.name))
    .map(it => ({ id:String(it.id), name: norm(it.nama || it.name) }))
    .sort((a,b)=>a.name.localeCompare(b.name,'id'));

  kotaItems2.forEach(it => kotaNameToId.set(it.name, it.id));

  if (CURRENT_KOTA && kotaNameToId.has(CURRENT_KOTA)) {
    // pre-fill kota & kecamatan sesuai data tersimpan
    fillSelect(
      kotaSelect,
      kotaItems2.map(it => ({ value:it.name, label:it.name })),
      'Pilih Kota/Kabupaten',
      CURRENT_KOTA
    );

    const kabId = kotaNameToId.get(CURRENT_KOTA);
    const kecArr = await loadKecamatanByKabId(kabId);
    const kecItems = kecArr
      .filter(it => it && (it.nama || it.name))
      .map(it => norm(it.nama || it.name))
      .sort((a,b)=>a.localeCompare(b,'id'))
      .map(n => ({ value:n, label:n }));
    fillSelect(kecSelect, kecItems, 'Pilih Kecamatan', CURRENT_KEC);
  } else {
    // tampilkan nilai tersimpan + divider + semua kota
    kotaSelect.innerHTML = '';
    const optSaved = document.createElement('option');
    optSaved.value = CURRENT_KOTA || '';
    optSaved.textContent = CURRENT_KOTA ? `${CURRENT_KOTA} (tersimpan)` : 'Pilih Kota/Kabupaten';
    optSaved.selected = true;
    kotaSelect.appendChild(optSaved);

    const optDivider = document.createElement('option');
    optDivider.disabled = true;
    optDivider.textContent = '──────────';
    kotaSelect.appendChild(optDivider);

    kotaItems2.forEach(it => {
      const opt = document.createElement('option');
      opt.value = it.name; opt.textContent = it.name;
      kotaSelect.appendChild(opt);
    });
  }

  kotaSelect.addEventListener('change', async () => {
    const namaKota = kotaSelect.value;
    if (!namaKota) { fillSelect(kecSelect, [], 'Pilih Kecamatan'); return; }
    const kabId = kotaNameToId.get(namaKota);
    if (!kabId) { fillSelect(kecSelect, [], 'Pilih Kecamatan'); return; }

    const kecArr = await loadKecamatanByKabId(kabId);
    const kecItems = kecArr
      .filter(it => it && (it.nama || it.name))
      .map(it => norm(it.nama || it.name))
      .sort((a,b)=>a.localeCompare(b,'id'))
      .map(n => ({ value:n, label:n }));
    fillSelect(kecSelect, kecItems, 'Pilih Kecamatan');
  });

  afterRegionInit();
});

/* =========================
   UI LAINNYA (tetap dari file kamu)
========================= */
// Toggle password
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

// Bookmark counter
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
          <button onclick="closeBookmarkModal()" class="close-btn"><i class="fas fa-times"></i></button>
        </div>
        <div class="bookmark-modal-body">`;
  if (bookmarks.length === 0) {
    modalHTML += `
      <div class="empty-bookmarks">
        <i class="far fa-bookmark"></i>
        <h4>Belum Ada Bookmark</h4>
        <p>Mulai jelajahi destinasi dan tambahkan ke bookmark!</p>
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
              <a href="<?= base_url('detail/') ?>${b.wisata_id}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat Detail</a>
              <button onclick="removeBookmark(${b.wisata_id})" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
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
