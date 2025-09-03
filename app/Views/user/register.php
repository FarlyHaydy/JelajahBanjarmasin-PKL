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
        const KAB_DIR   = "<?= base_url('data/kabupaten') ?>";   // folder
const KEC_DIR   = "<?= base_url('data/kecamatan') ?>";   // folder
const kotaSelect = document.getElementById('kota');
const kecSelect  = document.getElementById('kecamatan');

const OLD_KOTA = "<?= esc(old('kota')) ?>";
const OLD_KEC  = "<?= esc(old('kecamatan')) ?>";

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

// Simpan peta: nama_kota -> id_kab (buat fetch kecamatan per file)
const kotaNameToId = new Map();

// Helper: fetch semua file JSON dalam folder tertentu (asumsi kamu punya 1 file gabungan kabupaten.json,
// ATAU beberapa file per provinsi. Kalau beberapa file, daftarkan manual di array FILES_KAB di bawah.)
async function loadKabupatenAll() {
  // 2) kalau tidak ada, sebutkan daftar file per-provinsi di sini:
const FILES_KAB = [
  '11.json', '12.json', '13.json', '14.json', '15.json', '16.json', '17.json', '18.json', '19.json', '21.json',
  '31.json', '32.json', '33.json', '34.json', '35.json', '36.json', '51.json', '52.json', '53.json', '61.json',
  '62.json', '63.json', '64.json', '65.json', '71.json', '72.json', '73.json', '74.json', '75.json', '76.json',
  '81.json', '82.json', '91.json', '92.json', '94.json', '1101.json', '1102.json', '1103.json', '1104.json', '1105.json',
  '1106.json', '1107.json', '1108.json', '1109.json', '1110.json', '1111.json', '1112.json', '1113.json', '1114.json', '1115.json',
  '1116.json', '1117.json', '1118.json', '1171.json', '1172.json', '1173.json', '1174.json', '1175.json', '1201.json', '1202.json',
  '1203.json', '1204.json', '1205.json', '1206.json', '1207.json', '1208.json', '1209.json', '1210.json', '1211.json', '1212.json',
  '1213.json', '1214.json', '1215.json', '1216.json', '1217.json', '1218.json', '1219.json', '1220.json', '1221.json', '1222.json',
  '1223.json', '1224.json', '1225.json', '1271.json', '1272.json', '1273.json', '1274.json', '1275.json', '1276.json', '1277.json',
  '1278.json', '1301.json', '1302.json', '1303.json', '1304.json', '1305.json', '1306.json', '1307.json', '1308.json', '1309.json',
  '1310.json', '1311.json', '1312.json', '1371.json', '1372.json', '1373.json', '1374.json', '1375.json', '1376.json', '1377.json',
  '1401.json', '1402.json', '1403.json', '1404.json', '1405.json', '1406.json', '1407.json', '1408.json', '1409.json', '1410.json',
  '1471.json', '1472.json', '1501.json', '1502.json', '1503.json', '1504.json', '1505.json', '1506.json', '1507.json', '1508.json',
  '1509.json', '1571.json', '1572.json', '1601.json', '1602.json', '1603.json', '1604.json', '1605.json', '1606.json', '1607.json',
  '1608.json', '1609.json', '1610.json', '1611.json', '1612.json', '1613.json', '1671.json', '1672.json', '1673.json', '1674.json',
  '1701.json', '1702.json', '1703.json', '1704.json', '1705.json', '1706.json', '1707.json', '1708.json', '1709.json', '1771.json',
  '1801.json', '1802.json', '1803.json', '1804.json', '1805.json', '1806.json', '1807.json', '1808.json', '1809.json', '1810.json',
  '1811.json', '1812.json', '1813.json', '1871.json', '1872.json', '1901.json', '1902.json', '1903.json', '1904.json', '1905.json',
  '1906.json', '1971.json', '2101.json', '2102.json', '2103.json', '2104.json', '2105.json', '2171.json', '2172.json', '3101.json',
  '3171.json', '3172.json', '3173.json', '3174.json', '3175.json', '3201.json', '3202.json', '3203.json', '3204.json', '3205.json',
  '3206.json', '3207.json', '3208.json', '3209.json', '3210.json', '3211.json', '3212.json', '3213.json', '3214.json', '3215.json',
  '3216.json', '3217.json', '3218.json', '3271.json', '3272.json', '3273.json', '3274.json', '3275.json', '3276.json', '3277.json',
  '3278.json', '3279.json', '3301.json', '3302.json', '3303.json', '3304.json', '3305.json', '3306.json', '3307.json', '3308.json',
  '3309.json', '3310.json', '3311.json', '3312.json', '3313.json', '3314.json', '3315.json', '3316.json', '3317.json', '3318.json',
  '3319.json', '3320.json', '3321.json', '3322.json', '3323.json', '3324.json', '3325.json', '3326.json', '3327.json', '3328.json',
  '3329.json', '3371.json', '3372.json', '3373.json', '3374.json', '3375.json', '3376.json', '3401.json', '3402.json', '3403.json',
  '3404.json', '3471.json', '3501.json', '3502.json', '3503.json', '3504.json', '3505.json', '3506.json', '3507.json', '3508.json',
  '3509.json', '3510.json', '3511.json', '3512.json', '3513.json', '3514.json', '3515.json', '3516.json', '3517.json', '3518.json',
  '3519.json', '3520.json', '3521.json', '3522.json', '3523.json', '3524.json', '3525.json', '3526.json', '3527.json', '3528.json',
  '3529.json', '3571.json', '3572.json', '3573.json', '3574.json', '3575.json', '3576.json', '3577.json', '3578.json', '3579.json',
  '3601.json', '3602.json', '3603.json', '3604.json', '3671.json', '3672.json', '3673.json', '3674.json', '5101.json', '5102.json',
  '5103.json', '5104.json', '5105.json', '5106.json', '5107.json', '5108.json', '5171.json', '5201.json', '5202.json', '5203.json',
  '5204.json', '5205.json', '5206.json', '5207.json', '5208.json', '5271.json', '5272.json', '5301.json', '5302.json', '5303.json',
  '5304.json', '5305.json', '5306.json', '5307.json', '5308.json', '5309.json', '5310.json', '5311.json', '5312.json', '5313.json',
  '5314.json', '5315.json', '5316.json', '5317.json', '5318.json', '5319.json', '5320.json', '5321.json', '5371.json', '6101.json',
  '6102.json', '6103.json', '6104.json', '6105.json', '6106.json', '6107.json', '6108.json', '6109.json', '6110.json', '6111.json',
  '6112.json', '6171.json', '6172.json', '6201.json', '6202.json', '6203.json', '6204.json', '6205.json', '6206.json', '6207.json',
  '6208.json', '6209.json', '6210.json', '6211.json', '6212.json', '6213.json', '6271.json', '6301.json', '6302.json', '6303.json',
  '6304.json', '6305.json', '6306.json', '6307.json', '6308.json', '6309.json', '6310.json', '6311.json', '6371.json', '6372.json',
  '6401.json', '6402.json', '6403.json', '6407.json', '6408.json', '6409.json', '6411.json', '6471.json', '6472.json', '6474.json',
  '6501.json', '6502.json', '6503.json', '6504.json', '6571.json', '7101.json', '7102.json', '7103.json', '7104.json', '7105.json',
  '7106.json', '7107.json', '7108.json', '7109.json', '7110.json', '7111.json', '7171.json', '7172.json', '7173.json', '7174.json',
  '7201.json', '7202.json', '7203.json', '7204.json', '7205.json', '7206.json', '7207.json', '7208.json', '7209.json', '7210.json',
  '7211.json', '7212.json', '7271.json', '7301.json', '7302.json', '7303.json', '7304.json', '7305.json', '7306.json', '7307.json',
  '7308.json', '7309.json', '7310.json', '7311.json', '7312.json', '7313.json', '7314.json', '7315.json', '7316.json', '7317.json',
  '7318.json', '7322.json', '7324.json', '7326.json', '7371.json', '7372.json', '7373.json', '7401.json', '7402.json', '7403.json',
  '7404.json', '7405.json', '7406.json', '7407.json', '7408.json', '7409.json', '7410.json', '7411.json', '7412.json', '7413.json',
  '7414.json', '7415.json', '7471.json', '7472.json', '7501.json', '7502.json', '7503.json', '7504.json', '7505.json', '7511.json',
  '7601.json', '7602.json', '7603.json', '7604.json', '7605.json', '7606.json', '8101.json', '8102.json', '8103.json', '8104.json',
  '8105.json', '8106.json', '8107.json', '8108.json', '8109.json', '8171.json', '8172.json', '8201.json', '8202.json', '8203.json',
  '8204.json', '8205.json', '8206.json', '8207.json', '8208.json', '8271.json', '8272.json', '9101.json', '9102.json', '9103.json',
  '9104.json', '9105.json', '9106.json', '9107.json', '9108.json', '9109.json', '9110.json', '9111.json', '9112.json', '9113.json',
  '9114.json', '9115.json', '9116.json', '9117.json', '9118.json', '9119.json', '9120.json', '9121.json', '9122.json', '9123.json',
  '9124.json', '9125.json', '9126.json', '9127.json', '9128.json', '9171.json', '9201.json', '9202.json', '9203.json', '9204.json',
  '9205.json', '9206.json', '9207.json', '9208.json', '9209.json', '9210.json', '9211.json', '9212.json', '9271.json'
];
  let all = [];
  for (const f of FILES_KAB) {
    try {
      const r = await fetch(`${KAB_DIR}/${f}`, { headers: { 'Accept': 'application/json' }});
      if (r.ok) {
        const arr = await r.json();
        if (Array.isArray(arr)) all = all.concat(arr);
      }
    } catch (e) {}
  }
  return all;
}

async function loadKecamatanByKabId(kabId) {
  const url = `${KEC_DIR}/${kabId}.json`;
  const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
  if (!res.ok) return [];
  return await res.json();
}

function norm(s){ return (s||'').trim().replace(/\s+/g,' '); }

document.addEventListener('DOMContentLoaded', async () => {
  // 1) load kabupaten
  const kabAll = await loadKabupatenAll();
  const kotaItems = kabAll
    .filter(it => it && it.id && it.nama)
    .map(it => ({ id: String(it.id), name: norm(it.nama) }))
    .sort((a,b) => a.name.localeCompare(b.name,'id'));

  kotaItems.forEach(it => kotaNameToId.set(it.name, it.id));
  fillSelect(kotaSelect, kotaItems.map(it => ({ value: it.name, label: it.name })), 'Pilih Kota/Kabupaten');

  // 2) repopulate jika ada OLD
  if (OLD_KOTA && kotaNameToId.has(OLD_KOTA)) {
    kotaSelect.value = OLD_KOTA;
    const kabId = kotaNameToId.get(OLD_KOTA);
    const kec = await loadKecamatanByKabId(kabId);
    const kecItems = kec
      .filter(it => it && it.nama)
      .map(it => norm(it.nama))
      .sort((a,b) => a.localeCompare(b,'id'))
      .map(n => ({ value: n, label: n }));
    fillSelect(kecSelect, kecItems, 'Pilih Kecamatan');
    if (OLD_KEC) kecSelect.value = OLD_KEC;
  } else {
    fillSelect(kecSelect, [], 'Pilih Kecamatan');
  }

  // 3) on change kota → load kecamatan file {ID}.json
  kotaSelect.addEventListener('change', async () => {
    const namaKota = kotaSelect.value;
    const kabId = kotaNameToId.get(namaKota);
    if (!kabId) {
      fillSelect(kecSelect, [], 'Pilih Kecamatan');
      return;
    }
    const kec = await loadKecamatanByKabId(kabId);
    const kecItems = kec
      .filter(it => it && it.nama)
      .map(it => norm(it.nama))
      .sort((a,b) => a.localeCompare(b,'id'))
      .map(n => ({ value: n, label: n }));
    fillSelect(kecSelect, kecItems, 'Pilih Kecamatan');
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
