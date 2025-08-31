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
    $password = $this->request->getPost('password');

    // Debug log
    log_message('debug', "Login attempt: $emailOrUsername with password: $password");

    $akun = null;

    // Cari berdasarkan email dulu
    $akun = $this->akunModel->where('Email', $emailOrUsername)->first();
    log_message('debug', "Search by email result: " . print_r($akun, true));
    
    // Jika tidak ditemukan berdasarkan email, cari berdasarkan username
    if (!$akun) {
        $akun = $this->akunModel
                    ->join('user', 'user.id = akun.user_id')
                    ->where('user.Username', $emailOrUsername)
                    ->select('akun.*, user.Username, user.Nama_Asli, user.id as user_id') // TAMBAH Nama_Asli
                    ->first();
        
        log_message('debug', "Search by username result: " . print_r($akun, true));
    } else {
        // Jika ditemukan berdasarkan email, ambil data user juga
        $akun = $this->akunModel
                    ->join('user', 'user.id = akun.user_id')
                    ->where('akun.Email', $emailOrUsername)
                    ->select('akun.*, user.Username, user.Nama_Asli, user.id as user_id') // TAMBAH Nama_Asli
                    ->first();
        
        log_message('debug', "Search by email with join result: " . print_r($akun, true));
    }

    if ($akun) {
        $passwordVerify = password_verify($password, $akun['Password']);
        log_message('debug', "Password verify result: " . ($passwordVerify ? 'TRUE' : 'FALSE'));
        log_message('debug', "Input password: $password");
        log_message('debug', "Hash from DB: " . $akun['Password']);
        
        if ($passwordVerify) {
            // Login berhasil - UPDATE SESSION DATA
            session()->set([
                'isLoggedIn' => true,
                'user_id' => $akun['user_id'],
                'userEmail' => $akun['Email'],
                'username' => $akun['Username'],
                'Nama_Asli' => $akun['Nama_Asli'], // TAMBAH INI untuk dropdown profil
                'role' => $akun['Role']
            ]);
            
            log_message('debug', "Login success, role: " . $akun['Role']);
            log_message('debug', "Session data: " . print_r(session()->get(), true));
            
            if ($akun['Role'] === 'admin') {
                log_message('debug', "Redirecting to admin dashboard");
                return redirect()->to('/admin/dashboard');
            } else {
                log_message('debug', "Redirecting to user dashboard");
                return redirect()->to('/dashboard');
            }
        } else {
            log_message('debug', "Password verification failed");
        }
    } else {
        log_message('debug', "No account found");
    }
    
    log_message('debug', "Login failed - returning error");
    return redirect()->back()->with('error', 'Email/Username atau password salah');
}

    public function registerAction()
    {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $namaAsli = $this->request->getPost('nama_asli');
        $nomorTelepon = $this->request->getPost('telepon');
        $jenisKelamin = $this->request->getPost('jenis_kelamin');
        $alamat = $this->request->getPost('alamat');
        $kota = $this->request->getPost('kota');
        $kecamatan = $this->request->getPost('kecamatan');

        // Validasi manual jika perlu
        if (empty($username) || empty($email) || empty($namaAsli) || empty($nomorTelepon)) {
            return redirect()->back()->with('error', 'Semua field wajib harus diisi!');
        }

        // Cek apakah email sudah terdaftar di tabel akun
        if ($this->akunModel->where('Email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar!');
        }

        // Cek apakah username sudah terdaftar di tabel user
        if ($this->userModel->where('Username', $username)->first()) {
            return redirect()->back()->with('error', 'Username sudah terdaftar!');
        }

        try {
            // 1. Insert ke tabel user terlebih dahulu
            $userData = [
                'Username' => $username,
                'Nama_Asli' => $namaAsli,
                'Nomor_Telepon' => $nomorTelepon,
                'Jenis_Kelamin' => $jenisKelamin,
                'Alamat' => $alamat,
                'Kota' => $kota,
                'Kecamatan' => $kecamatan,
                'Email' => $email
            ];

            $userId = $this->userModel->skipValidation(true)->insert($userData);
            
            if (!$userId) {
                $errors = $this->userModel->errors();
                return redirect()->back()->with('error', 'Gagal membuat user: ' . implode(', ', $errors));
            }

            // 2. Insert ke tabel akun dengan user_id (role default = 'user')
            $akunData = [
                'user_id' => $userId,
                'Email' => $email,
                'Password' => $password,
                'Role' => 'user' // Default role untuk registrasi umum
            ];

            $akunResult = $this->akunModel->skipValidation(true)->insert($akunData);
            
            if (!$akunResult) {
                // Rollback: hapus user yang sudah dibuat
                $this->userModel->delete($userId);
                $errors = $this->akunModel->errors();
                return redirect()->back()->with('error', 'Gagal membuat akun: ' . implode(', ', $errors));
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

    // Method lainnya tetap sama
    public function register()
    {
        return view('user/register');
    }

    public function login()
    {
        return view('user/login');
    }

    public function profil()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Session tidak valid');
        }

        $user = $this->userModel->find($userId);

        if ($user) {
            return view('user/profil', ['user' => $user]);
        } else {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan');
        }
    }

    public function updateProfil()
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    $userId = session()->get('user_id');
    
    if (!$userId) {
        return redirect()->to('/login')->with('error', 'Session tidak valid');
    }

    // Ambil data saat ini untuk pengecekan
    $currentUser = $this->userModel->find($userId);
    $currentAkun = $this->akunModel->where('user_id', $userId)->first();
    
    if (!$currentUser || !$currentAkun) {
        return redirect()->to('/login')->with('error', 'Data user tidak ditemukan');
    }

    // Ambil data dari form
    $namaAsli = $this->request->getPost('nama_asli');
    $email = $this->request->getPost('email');
    $alamat = $this->request->getPost('alamat');
    $nomorTelepon = $this->request->getPost('nomor_telepon');
    $jenisKelamin = $this->request->getPost('jenis_kelamin');
    $kota = $this->request->getPost('kota');
    $kecamatan = $this->request->getPost('kecamatan');
    $password = $this->request->getPost('password');

    // Validasi basic
    if (empty($namaAsli) || empty($email) || empty($alamat) || empty($nomorTelepon)) {
        return redirect()->back()->withInput()->with('error', 'Field yang wajib tidak boleh kosong');
    }

    // Validasi email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return redirect()->back()->withInput()->with('error', 'Format email tidak valid');
    }

    // Cek email unique jika berbeda dari email saat ini
    if ($email !== $currentUser['Email']) {
        $emailExists = $this->userModel->where('Email', $email)->first();
        if ($emailExists) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan oleh user lain');
        }
        
        $emailExistsAkun = $this->akunModel->where('Email', $email)->first();
        if ($emailExistsAkun) {
            return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar di sistem');
        }
    }

    // Validasi password jika diisi
    if (!empty($password) && strlen($password) < 6) {
        return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter');
    }

    try {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        // 1. Update tabel USER
        $userData = [
            'Nama_Asli' => $namaAsli,
            'Email' => $email,
            'Alamat' => $alamat,
            'Nomor_Telepon' => $nomorTelepon,
            'Jenis_Kelamin' => $jenisKelamin,
            'Kota' => $kota,
            'Kecamatan' => $kecamatan
        ];

        $userResult = $db->table('user')->where('id', $userId)->update($userData);

        // 2. Update tabel AKUN
        $akunData = ['Email' => $email];
        
        if (!empty($password)) {
            $akunData['Password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $akunResult = $db->table('akun')->where('user_id', $userId)->update($akunData);

        $db->transComplete(); // Selesai transaksi

        if ($db->transStatus() === false) {
            throw new \Exception('Transaksi database gagal');
        }

        // Update session dengan data terbaru
        session()->set([
            'Nama_Asli' => $namaAsli,
            'userEmail' => $email
        ]);

        return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui!');
        
    } catch (\Exception $e) {
        log_message('error', 'Update profil error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    }
}
}