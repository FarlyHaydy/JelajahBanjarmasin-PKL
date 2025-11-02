<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AkunModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $akunModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->akunModel = new AkunModel();
    }

    public function loginAction()
    {
        $emailOrUsername = $this->request->getPost('email');
        $password        = $this->request->getPost('password');

        if (empty($emailOrUsername) || empty($password)) {
        return redirect()->back()->with('error', 'Harap isi username/email dan password');
    }

        // Cari akun berdasarkan email terlebih dahulu
        $akun = $this->akunModel->where('Email', $emailOrUsername)->first();

        // Jika tidak ada, coba berdasarkan Username (join ke tabel user)
        if (!$akun) {
            $akun = $this->akunModel
                ->join('user', 'user.id = akun.user_id')
                ->where('user.Username', $emailOrUsername)
                ->select('akun.*, user.Username, user.Nama_Asli, user.id as user_id')
                ->first();
        } else {
            // Lengkapi data user jika ketemu via email
            $akun = $this->akunModel
                ->join('user', 'user.id = akun.user_id')
                ->where('akun.Email', $emailOrUsername)
                ->select('akun.*, user.Username, user.Nama_Asli, user.id as user_id')
                ->first();
        }

        if ($akun && password_verify($password, $akun['Password'])) {
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $akun['user_id'],
                'userEmail'  => $akun['Email'],
                'username'   => $akun['Username'],
                'Nama_Asli'  => $akun['Nama_Asli'],
                'role'       => $akun['Role'],
            ]);

            return ($akun['Role'] === 'admin')
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Email/username atau Password yang anda masukan salah');
    }

    public function registerAction()
    {
        $username     = $this->request->getPost('username');
        $email        = $this->request->getPost('email');
        $password     = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $namaAsli     = $this->request->getPost('nama_asli');
        $nomorTelepon = $this->request->getPost('telepon');
        $jenisKelamin = $this->request->getPost('jenis_kelamin');
        $alamat       = $this->request->getPost('alamat');
        $kota         = $this->request->getPost('kota');
        $kecamatan    = $this->request->getPost('kecamatan');

        if (empty($username) || empty($email) || empty($namaAsli) || empty($nomorTelepon)) {
            return redirect()->back()->with('error', 'Semua field wajib harus diisi!');
        }

        if ($this->akunModel->where('Email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar!');
        }

        if ($this->userModel->where('Username', $username)->first()) {
            return redirect()->back()->with('error', 'Username sudah terdaftar!');
        }

        try {
            // 1) Insert tabel user
            $userData = [
                'Username'       => $username,
                'Nama_Asli'      => $namaAsli,
                'Nomor_Telepon'  => $nomorTelepon,
                'Jenis_Kelamin'  => $jenisKelamin,
                'Alamat'         => $alamat,
                'Kota'           => $kota,
                'Kecamatan'      => $kecamatan,
                'Email'          => $email,
            ];

            $userId = $this->userModel->skipValidation(true)->insert($userData);
            if (!$userId) {
                $errors = $this->userModel->errors();
                return redirect()->back()->with('error', 'Gagal membuat user: ' . implode(', ', (array) $errors));
            }

            // 2) Insert tabel akun
            $akunData = [
                'user_id'  => $userId,
                'Email'    => $email,
                'Password' => $password,
                'Role'     => 'user',
            ];

            $akunResult = $this->akunModel->skipValidation(true)->insert($akunData);
            if (!$akunResult) {
                $this->userModel->delete($userId);
                $errors = $this->akunModel->errors();
                return redirect()->back()->with('error', 'Gagal membuat akun: ' . implode(', ', (array) $errors));
            }

            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/dashboard')->with('message', 'Logout berhasil');
    }

    public function register()
{
    $kotaKecamatan = $this->buildKotaKecamatanCombined();

    // kirim ke view register
    return view('user/register', [
        'kotaKecamatan' => $kotaKecamatan,
    ]);
}

    public function login()
    {
        return view('user/login');
    }

    /* =========================
       PROFIL (no-delay data)
    ========================= */
    public function profil()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Session tidak valid');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan');
        }

        // Preload kota/kecamatan ke memori (cache 24 jam)
        $kotaKecamatan = $this->buildKotaKecamatanCombined();

        // Hitung bookmark langsung dari DB (tanpa fetch di client)
        // GANTI 'wishlist' bila nama tabelmu berbeda.
        $db            = \Config\Database::connect();
        $bookmarkCount = (int) $db->table('bookmark')->where('user_id', $userId)->countAllResults();

        return view('user/profil', [
            'user'          => $user,
            'kotaKecamatan' => $kotaKecamatan,
            'bookmarkCount' => $bookmarkCount,
        ]);
    }

    public function updateProfil()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Session tidak valid');
        }

        $currentUser = $this->userModel->find($userId);
        $currentAkun = $this->akunModel->where('user_id', $userId)->first();
        if (!$currentUser || !$currentAkun) {
            return redirect()->to('/login')->with('error', 'Data user tidak ditemukan');
        }

        $namaAsli     = $this->request->getPost('nama_asli');
        $email        = $this->request->getPost('email');
        $alamat       = $this->request->getPost('alamat');
        $nomorTelepon = $this->request->getPost('nomor_telepon');
        $jenisKelamin = $this->request->getPost('jenis_kelamin');
        $kota         = $this->request->getPost('kota');
        $kecamatan    = $this->request->getPost('kecamatan');
        $password     = $this->request->getPost('password');

        if (empty($namaAsli) || empty($email) || empty($alamat) || empty($nomorTelepon) || empty($jenisKelamin) || empty($kota) || empty($kecamatan)) {
            return redirect()->back()->withInput()->with('error', 'Field yang wajib tidak boleh kosong');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Format email tidak valid');
        }

        if ($email !== $currentUser['Email']) {
            if ($this->userModel->where('Email', $email)->first()) {
                return redirect()->back()->withInput()->with('error', 'Email sudah digunakan oleh user lain');
            }
            if ($this->akunModel->where('Email', $email)->first()) {
                return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar di sistem');
            }
        }

        if (!empty($password) && strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter');
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Update user
            $userData = [
                'Nama_Asli'     => $namaAsli,
                'Email'         => $email,
                'Alamat'        => $alamat,
                'Nomor_Telepon' => $nomorTelepon,
                'Jenis_Kelamin' => $jenisKelamin,
                'Kota'          => $kota,
                'Kecamatan'     => $kecamatan,
            ];
            $db->table('user')->where('id', $userId)->update($userData);

            // Update akun
            $akunData = ['Email' => $email];
            if (!empty($password)) {
                $akunData['Password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $db->table('akun')->where('user_id', $userId)->update($akunData);

            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            // Refresh session
            session()->set([
                'Nama_Asli' => $namaAsli,
                'userEmail' => $email,
            ]);

            return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            log_message('error', 'Update profil error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /* =====================================================
       KOTA/KECAMATAN SERVER-SIDE (no fetch di client)
    ===================================================== */
    private function buildKotaKecamatanCombined(): array
    {
        $cacheFile = WRITEPATH . 'cache/kota_kecamatan_combined.json';
        $cacheTtl  = 86400; // 24 jam

        // Pakai cache jika masih valid
        if (is_file($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
            $cached = json_decode(@file_get_contents($cacheFile), true);
            if (is_array($cached)) return $cached;
        }

        $kabDir = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'kabupaten';
        $kecDir = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'kecamatan';

        $result = [];

        foreach (glob($kabDir . DIRECTORY_SEPARATOR . '*.json') as $kabFile) {
            $kabList = json_decode(@file_get_contents($kabFile), true);
            if (!is_array($kabList)) continue;

            foreach ($kabList as $kab) {
                $id   = $kab['id']   ?? null;
                $name = trim((string)($kab['nama'] ?? $kab['name'] ?? ''));
                if (!$id || $name === '') continue;

                $kecFile = $kecDir . DIRECTORY_SEPARATOR . $id . '.json';
                $kecs    = [];

                if (is_file($kecFile)) {
                    $kArr = json_decode(@file_get_contents($kecFile), true);
                    if (is_array($kArr)) {
                        foreach ($kArr as $kc) {
                            $nm = trim((string)($kc['nama'] ?? $kc['name'] ?? ''));
                            if ($nm !== '') $kecs[] = $nm;
                        }
                    }
                }

                $kecs = array_values(array_unique($kecs));
                sort($kecs, SORT_NATURAL | SORT_FLAG_CASE);

                $result[$name] = $kecs;
            }
        }

        ksort($result, SORT_NATURAL | SORT_FLAG_CASE);

        @file_put_contents(
            $cacheFile,
            json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return $result;
    }

    // Opsional kalau ingin dipakai AJAX di halaman lain
    public function kotaKecamatanJson()
    {
        $data = $this->buildKotaKecamatanCombined();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }
}
