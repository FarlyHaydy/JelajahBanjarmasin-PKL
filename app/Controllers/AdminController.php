<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\GaleriModel;
use App\Models\KategoriModel;
use App\Models\KotaModel;
use App\Models\KecamatanModel;
use App\Models\UserModel; // NEW: Tambah UserModel

class AdminController extends BaseController
{
    protected $wisataModel;
    protected $bookmarkModel;
    protected $galeriModel;
    protected $kategoriModel;
    protected $kotaModel;
    protected $kecamatanModel;
    protected $userModel; // NEW: Tambah property userModel

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->galeriModel = new GaleriModel();
        $this->kategoriModel = new KategoriModel();
        $this->kotaModel = new KotaModel();
        $this->kecamatanModel = new KecamatanModel();
        $this->userModel = new UserModel(); // NEW: Inisialisasi UserModel
        $this->bookmarkModel = new \App\Models\BookmarkModel();
    }

    // Dashboard utama
    public function dashboard()
    {
        // Debug: tampilkan data untuk troubleshooting
        $wisataData = $this->wisataModel->getAllWithRelations();
        $kategoriData = $this->kategoriModel->findAll();
        $kotaData = $this->kotaModel->findAll();
        $kecamatanData = $this->kecamatanModel->getWithKota();
        $usersData = $this->userModel->findAll(); // NEW: Ambil data users
        
        // // Debug log
        // log_message('debug', 'Wisata count: ' . count($wisataData));
        // log_message('debug', 'Kategori count: ' . count($kategoriData));
        // log_message('debug', 'Kota count: ' . count($kotaData));
        // log_message('debug', 'Kecamatan count: ' . count($kecamatanData));
        // log_message('debug', 'Users count: ' . count($usersData)); // NEW: Debug users
        
        $data = [
            'wisata' => $wisataData,
            'kategori' => $kategoriData,
            'kota' => $kotaData,
            'kecamatan' => $kecamatanData,
            'users' => $usersData // NEW: Kirim data users ke view
        ];
        
        return view('admin/dashboardAdmin', $data);
    }

    // ==================== USER MANAGEMENT ====================
    
    // NEW: Get user untuk edit
    public function getUser($id)
{
    try {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['error' => 'User tidak ditemukan']);
        }

        return $this->response->setJSON(['user' => $user]);
    } catch (\Exception $e) {
        log_message('error', 'Get user error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Error mengambil data user']);
    }
}
    // NEW: Update user
    public function updateUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        // Validation rules untuk update (tanpa unique check untuk data yang sama)
        $rules = [
            'Username' => "required|min_length[3]|max_length[255]",
            'Nama_Asli' => 'required|min_length[2]|max_length[255]',
            'Nomor_Telepon' => 'required|min_length[10]|max_length[15]',
            'Jenis_Kelamin' => 'required|in_list[Laki-Laki,Perempuan]',
            'Alamat' => 'required|min_length[5]',
            'Email' => "required|valid_email|max_length[255]"
        ];

        // Check unique untuk username dan email jika berbeda
        $newUsername = $this->request->getPost('Username');
        $newEmail = $this->request->getPost('Email');
        
        if ($newUsername !== $user['Username']) {
            $existingUser = $this->userModel->where('Username', $newUsername)->first();
            if ($existingUser) {
                return redirect()->back()->withInput()->with('error', 'Username sudah digunakan');
            }
        }
        
        if ($newEmail !== $user['Email']) {
            $existingUser = $this->userModel->where('Email', $newEmail)->first();
            if ($existingUser) {
                return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar');
            }
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $validation->getErrors()));
        }

        $userData = [
            'Username' => $newUsername,
            'Nama_Asli' => $this->request->getPost('Nama_Asli'),
            'Nomor_Telepon' => $this->request->getPost('Nomor_Telepon'),
            'Jenis_Kelamin' => $this->request->getPost('Jenis_Kelamin'),
            'Alamat' => $this->request->getPost('Alamat'),
            'Kota' => $this->request->getPost('Kota'),
            'Kecamatan' => $this->request->getPost('Kecamatan'),
            'Email' => $newEmail
        ];

        try {
            $this->userModel->update($id, $userData);
            return redirect()->to('/admin/dashboard')->with('success', 'Data pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            log_message('error', 'Update user error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    // NEW: Delete user
    public function deleteUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        try {
            // Hapus semua bookmark user ini
            $this->bookmarkModel->where('user_id', $id)->delete();
            
            // Hapus user
            $this->userModel->delete($id);

            return redirect()->to('/admin/dashboard')->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            log_message('error', 'Delete user error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    // ==================== WISATA MANAGEMENT ====================

    // Buat wisata baru
    public function createWisata()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama_wisata' => 'required|min_length[3]|max_length[255]',
            'alamat' => 'required|min_length[10]',
            'kategori_id' => 'required|is_natural_no_zero',
            'kota_id' => 'required|is_natural_no_zero',
            'kecamatan_id' => 'required|is_natural_no_zero',
            'deskripsi' => 'required|min_length[20]',
            'detail' => 'required|min_length[50]',
            'gambar' => 'uploaded[gambar.0]|max_size[gambar.0,2048]|is_image[gambar.0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $validation->getErrors()));
        }

        // Validasi koordinat jika diisi
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        
        if (!empty($latitude) || !empty($longitude)) {
            if (!$this->wisataModel->validateCoordinates($latitude, $longitude)) {
                return redirect()->back()->withInput()->with('error', 'Koordinat tidak valid. Latitude: -90 sampai 90, Longitude: -180 sampai 180');
            }
        }

        // FIXED: Simpan data wisata dengan manual timestamp
        $wisataData = [
            'nama_wisata' => $this->request->getPost('nama_wisata'),
            'alamat' => $this->request->getPost('alamat'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'kota_id' => $this->request->getPost('kota_id'),
            'kecamatan_id' => $this->request->getPost('kecamatan_id'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'detail' => $this->request->getPost('detail'),
            'latitude' => !empty($latitude) ? $latitude : null,
            'longitude' => !empty($longitude) ? $longitude : null,
            'created_at' => date('Y-m-d H:i:s'),  // FIXED: Manual timestamp
            'updated_at' => date('Y-m-d H:i:s')   // FIXED: Manual timestamp
        ];

        try {
            $wisataId = $this->wisataModel->insert($wisataData);

            if (!$wisataId) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data wisata');
            }

            // Upload gambar
            $files = $this->request->getFiles();
            if (isset($files['gambar'])) {
                $this->uploadImages($wisataId, $files['gambar']);
            }

            return redirect()->to('/admin/dashboard')->with('success', 'Postingan wisata berhasil dibuat!');
        } catch (\Exception $e) {
            log_message('error', 'Create wisata error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }


    // Get data wisata untuk edit
    public function getWisata($id)
{
    try {
        $wisata = $this->wisataModel->getWithRelations($id);

        if (!$wisata) {
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Wisata tidak ditemukan']);
        }

        return $this->response->setStatusCode(200)
            ->setJSON(['status' => 'success', 'wisata' => $wisata]);
    } catch (\Throwable $e) {
        log_message('error', 'getWisata error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)
            ->setJSON(['status' => 'error', 'message' => 'Gagal memuat data']);
    }
}

    // Update wisata
     public function updateWisata($id)
{
    $wisata = $this->wisataModel->find($id);
    
    if (!$wisata) {
        return redirect()->back()->with('error', 'Wisata tidak ditemukan');
    }

    $validation = \Config\Services::validation();
    
    $rules = [
        'nama_wisata' => 'required|min_length[3]|max_length[255]',
        'alamat' => 'required|min_length[10]',
        'kategori_id' => 'required|is_natural_no_zero',
        'kota_id' => 'required|is_natural_no_zero',
        'kecamatan_id' => 'required|is_natural_no_zero',
        'deskripsi' => 'required|min_length[20]',
        'detail' => 'required|min_length[50]',
        'latitude' => 'permit_empty|decimal',
        'longitude' => 'permit_empty|decimal'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $validation->getErrors()));
    }

    // Validasi koordinat jika diisi
    $latitude = $this->request->getPost('latitude');
    $longitude = $this->request->getPost('longitude');
    
    if (!empty($latitude) || !empty($longitude)) {
        if (!$this->wisataModel->validateCoordinates($latitude, $longitude)) {
            return redirect()->back()->withInput()->with('error', 'Koordinat tidak valid. Latitude: -90 sampai 90, Longitude: -180 sampai 180');
        }
    }

    // Update data wisata dengan manual timestamp
    $wisataData = [
        'nama_wisata' => $this->request->getPost('nama_wisata'),
        'alamat' => $this->request->getPost('alamat'),
        'kategori_id' => $this->request->getPost('kategori_id'),
        'kota_id' => $this->request->getPost('kota_id'),
        'kecamatan_id' => $this->request->getPost('kecamatan_id'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'detail' => $this->request->getPost('detail'),
        'latitude' => !empty($latitude) ? $latitude : null,
        'longitude' => !empty($longitude) ? $longitude : null,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    try {
        $this->wisataModel->update($id, $wisataData);
    } catch (\Exception $e) {
        log_message('error', 'Update wisata error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
    }

    // FIXED: Cek apakah ada file gambar yang diupload
    $files = $this->request->getFileMultiple('gambar');
    
    // Validasi: cek apakah user benar-benar upload file
    $hasValidUpload = false;
    foreach ($files as $file) {
        if ($file->isValid() && !$file->hasMoved()) {
            $hasValidUpload = true;
            break;
        }
    }
    
    // Jika ada upload valid, proses gambar
    if ($hasValidUpload) {
        // Hapus gambar lama
        $this->deleteWisataImages($id);
        
        // Upload gambar baru
        $uploaded = $this->uploadImages($id, $files);
        
        if ($uploaded > 0) {
            return redirect()->to('/admin/dashboard')->with('success', 'Postingan wisata dan gambar berhasil diperbarui!');
        } else {
            return redirect()->to('/admin/dashboard')->with('success', 'Postingan wisata berhasil diperbarui, namun ada masalah dengan upload gambar.');
        }
    }

    return redirect()->to('/admin/dashboard')->with('success', 'Postingan wisata berhasil diperbarui!');
}

    // Hapus wisata
     public function deleteWisata($id)
    {
        $wisata = $this->wisataModel->find($id);
        
        if (!$wisata) {
            return redirect()->back()->with('error', 'Wisata tidak ditemukan');
        }

        try {
            // Hapus gambar terkait
            $this->deleteWisataImages($id);

            // NEW: Hapus semua bookmark untuk wisata ini
            $this->bookmarkModel->deleteWisataBookmarks($id);

            // Hapus data wisata
            $this->wisataModel->delete($id);

            return redirect()->to('/admin/dashboard')->with('success', 'Postingan wisata berhasil dihapus!');
        } catch (\Exception $e) {
            log_message('error', 'Delete wisata error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus wisata: ' . $e->getMessage());
        }
    }

    // ==================== KATEGORI MANAGEMENT ====================

    public function createKategori()
    {
        $nama = $this->request->getPost('nama_kategori');
        
        if (empty($nama)) {
            return redirect()->back()->with('error', 'Nama kategori tidak boleh kosong');
        }

        $data = [
            'nama_kategori' => $nama,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->kategoriModel->insert($data);
        return redirect()->to('/admin/dashboard')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function deleteKategori($id)
    {
        // Cek apakah kategori masih digunakan
        $wisataCount = $this->wisataModel->where('kategori_id', $id)->countAllResults();
        
        if ($wisataCount > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh wisata');
        }

        $this->kategoriModel->delete($id);
        return redirect()->to('/admin/dashboard')->with('success', 'Kategori berhasil dihapus!');
    }

    // ==================== KOTA MANAGEMENT ====================

    public function createKota()
    {
        $nama = $this->request->getPost('nama_kota');
        
        if (empty($nama)) {
            return redirect()->back()->with('error', 'Nama kota tidak boleh kosong');
        }

        $data = [
            'nama_kota' => $nama,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->kotaModel->insert($data);
        return redirect()->to('/admin/dashboard')->with('success', 'Kota berhasil ditambahkan!');
    }

    public function deleteKota($id)
    {
        // Cek apakah kota masih digunakan
        $wisataCount = $this->wisataModel->where('kota_id', $id)->countAllResults();
        $kecamatanCount = $this->kecamatanModel->where('kota_id', $id)->countAllResults();
        
        if ($wisataCount > 0 || $kecamatanCount > 0) {
            return redirect()->back()->with('error', 'Kota tidak dapat dihapus karena masih digunakan');
        }

        $this->kotaModel->delete($id);
        return redirect()->to('/admin/dashboard')->with('success', 'Kota berhasil dihapus!');
    }

    // ==================== KECAMATAN MANAGEMENT ====================

    public function createKecamatan()
    {
        $nama = $this->request->getPost('nama_kecamatan');
        $kotaId = $this->request->getPost('kota_id');
        
        if (empty($nama) || empty($kotaId)) {
            return redirect()->back()->with('error', 'Nama kecamatan dan kota harus diisi');
        }

        $data = [
            'nama_kecamatan' => $nama,
            'kota_id' => $kotaId,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->kecamatanModel->insert($data);
        return redirect()->to('/admin/dashboard')->with('success', 'Kecamatan berhasil ditambahkan!');
    }

    public function deleteKecamatan($id)
    {
        // Cek apakah kecamatan masih digunakan
        $wisataCount = $this->wisataModel->where('kecamatan_id', $id)->countAllResults();
        
        if ($wisataCount > 0) {
            return redirect()->back()->with('error', 'Kecamatan tidak dapat dihapus karena masih digunakan oleh wisata');
        }

        $this->kecamatanModel->delete($id);
        return redirect()->to('/admin/dashboard')->with('success', 'Kecamatan berhasil dihapus!');
    }

    // Get kecamatan by kota (AJAX)
    public function getKecamatanByKota($kotaId)
    {
        $kecamatan = $this->kecamatanModel->getByKota($kotaId);
        return $this->response->setJSON($kecamatan);
    }

    public function getKategori($id)
{
    try {
        $kategori = $this->kategoriModel->find($id);
        
        if (!$kategori) {
            return $this->response->setJSON(['error' => 'Kategori tidak ditemukan']);
        }

        return $this->response->setJSON(['kategori' => $kategori]);
    } catch (\Exception $e) {
        log_message('error', 'Get kategori error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Error mengambil data kategori']);
    }
}

public function updateKategori($id)
{
    $kategori = $this->kategoriModel->find($id);
    
    if (!$kategori) {
        return redirect()->back()->with('error', 'Kategori tidak ditemukan');
    }

    $nama = $this->request->getPost('nama_kategori');
    
    if (empty($nama)) {
        return redirect()->back()->with('error', 'Nama kategori tidak boleh kosong');
    }

    // Check unique untuk nama kategori jika berbeda
    if ($nama !== $kategori['nama_kategori']) {
        $existingKategori = $this->kategoriModel->where('nama_kategori', $nama)->first();
        if ($existingKategori) {
            return redirect()->back()->with('error', 'Nama kategori sudah digunakan');
        }
    }

    $data = [
        'nama_kategori' => $nama,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    try {
        $this->kategoriModel->update($id, $data);
        return redirect()->to('/admin/dashboard')->with('success', 'Kategori berhasil diperbarui!');
    } catch (\Exception $e) {
        log_message('error', 'Update kategori error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengupdate kategori: ' . $e->getMessage());
    }
}

// ==================== KOTA MANAGEMENT (UPDATE) ====================

public function getKota($id)
{
    try {
        $kota = $this->kotaModel->find($id);
        
        if (!$kota) {
            return $this->response->setJSON(['error' => 'Kota tidak ditemukan']);
        }

        return $this->response->setJSON(['kota' => $kota]);
    } catch (\Exception $e) {
        log_message('error', 'Get kota error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Error mengambil data kota']);
    }
}

public function updateKota($id)
{
    $kota = $this->kotaModel->find($id);
    
    if (!$kota) {
        return redirect()->back()->with('error', 'Kota tidak ditemukan');
    }

    $nama = $this->request->getPost('nama_kota');
    
    if (empty($nama)) {
        return redirect()->back()->with('error', 'Nama kota tidak boleh kosong');
    }

    // Check unique untuk nama kota jika berbeda
    if ($nama !== $kota['nama_kota']) {
        $existingKota = $this->kotaModel->where('nama_kota', $nama)->first();
        if ($existingKota) {
            return redirect()->back()->with('error', 'Nama kota sudah digunakan');
        }
    }

    $data = [
        'nama_kota' => $nama,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    try {
        $this->kotaModel->update($id, $data);
        return redirect()->to('/admin/dashboard')->with('success', 'Kota berhasil diperbarui!');
    } catch (\Exception $e) {
        log_message('error', 'Update kota error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengupdate kota: ' . $e->getMessage());
    }
}

// ==================== KECAMATAN MANAGEMENT (UPDATE) ====================

public function getKecamatan($id)
{
    try {
        $kecamatan = $this->kecamatanModel->getWithKota($id);
        
        if (!$kecamatan) {
            return $this->response->setJSON(['error' => 'Kecamatan tidak ditemukan']);
        }

        return $this->response->setJSON(['kecamatan' => $kecamatan]);
    } catch (\Exception $e) {
        log_message('error', 'Get kecamatan error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Error mengambil data kecamatan']);
    }
}

public function updateKecamatan($id)
{
    $kecamatan = $this->kecamatanModel->find($id);
    
    if (!$kecamatan) {
        return redirect()->back()->with('error', 'Kecamatan tidak ditemukan');
    }

    $nama = $this->request->getPost('nama_kecamatan');
    $kotaId = $this->request->getPost('kota_id');
    
    if (empty($nama) || empty($kotaId)) {
        return redirect()->back()->with('error', 'Nama kecamatan dan kota harus diisi');
    }

    // Check unique untuk nama kecamatan dalam kota yang sama
    if ($nama !== $kecamatan['nama_kecamatan'] || $kotaId !== $kecamatan['kota_id']) {
        $existingKecamatan = $this->kecamatanModel
            ->where('nama_kecamatan', $nama)
            ->where('kota_id', $kotaId)
            ->first();
        if ($existingKecamatan) {
            return redirect()->back()->with('error', 'Nama kecamatan sudah ada di kota tersebut');
        }
    }

    $data = [
        'nama_kecamatan' => $nama,
        'kota_id' => $kotaId,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    try {
        $this->kecamatanModel->update($id, $data);
        return redirect()->to('/admin/dashboard')->with('success', 'Kecamatan berhasil diperbarui!');
    } catch (\Exception $e) {
        log_message('error', 'Update kecamatan error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengupdate kecamatan: ' . $e->getMessage());
    }
}

public function importWisata()
{
    // Cek apakah ini POST request
    if (!$this->request->is('post')) {
        return redirect()->back()->with('error', 'Metode tidak valid. Gunakan form untuk upload.');
    }

    log_message('info', 'Import wisata dipanggil - Method: ' . $this->request->getMethod());

    // Validasi file upload
    $validationRules = [
        'file_txt' => [
            'rules' => 'uploaded[file_txt]|ext_in[file_txt,txt]|max_size[file_txt,5120]',
            'errors' => [
                'uploaded' => 'File TXT harus diupload.',
                'ext_in' => 'File harus berformat TXT.',
                'max_size' => 'Ukuran file maksimal 5MB.'
            ]
        ]
    ]; 

    if (!$this->validate($validationRules)) {
        $errors = $this->validator->getErrors();
        $errorMessage = is_array($errors) ? implode(', ', $errors) : $errors;
        return redirect()->back()->with('error', $errorMessage);
    }

    $file = $this->request->getFile('file_txt');
    
    // Validasi file
    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'File tidak valid atau gagal diupload.');
    }

    // Validasi ekstensi file
    if ($file->getClientExtension() !== 'txt') {
        return redirect()->back()->with('error', 'File harus berformat TXT.');
    }

    try {
        // Baca file TXT
        $filePath = $file->getTempName();
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if (empty($lines)) {
            return redirect()->back()->with('error', 'File TXT kosong.');
        }

        log_message('info', 'Total baris dalam file: ' . count($lines));

        $dataWisata = [];
        $errors = [];
        $rowNumber = 0;
        
        // Deteksi delimiter dari baris pertama
        $delimiter = $this->detectDelimiter($lines[0]);
        log_message('info', 'Delimiter terdeteksi: ' . $delimiter);
        
        // Skip header jika ada (baris pertama yang mengandung kata 'nama' atau 'wisata')
        $startIndex = 0;
        if (stripos($lines[0], 'nama') !== false || stripos($lines[0], 'wisata') !== false) {
            $startIndex = 1;
            log_message('info', 'Header detected, skipping first line');
        }
        
        // Parse setiap baris
        for ($i = $startIndex; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            $rowNumber = $i + 1;
            
            // Skip baris kosong
            if (empty($line)) {
                continue;
            }
            
            // Parse baris berdasarkan delimiter
            $row = array_map('trim', explode($delimiter, $line));
            
            // Validasi jumlah kolom
            if (count($row) < 9) {
                $errors[] = "Baris ke-{$rowNumber}: Data tidak lengkap (harus ada 9 kolom, ditemukan " . count($row) . " kolom).";
                continue;
            }
            
            // Validasi data wajib (nama dan alamat)
            if (empty($row[0])) {
                $errors[] = "Baris ke-{$rowNumber}: Nama wisata tidak boleh kosong.";
                continue;
            }
            
            if (empty($row[1])) {
                $errors[] = "Baris ke-{$rowNumber}: Alamat tidak boleh kosong.";
                continue;
            }
            
            // Validasi kategori_id, kota_id, kecamatan_id harus numerik
            if (!is_numeric($row[2])) {
                $errors[] = "Baris ke-{$rowNumber}: Kategori ID harus berupa angka (ditemukan: {$row[2]}).";
                continue;
            }
            
            if (!is_numeric($row[3])) {
                $errors[] = "Baris ke-{$rowNumber}: Kota ID harus berupa angka (ditemukan: {$row[3]}).";
                continue;
            }
            
            if (!is_numeric($row[4])) {
                $errors[] = "Baris ke-{$rowNumber}: Kecamatan ID harus berupa angka (ditemukan: {$row[4]}).";
                continue;
            }
            
            // Validasi koordinat jika diisi
            $latitude = !empty($row[7]) ? $row[7] : null;
            $longitude = !empty($row[8]) ? $row[8] : null;
            
            if ($latitude !== null && !is_numeric($latitude)) {
                $errors[] = "Baris ke-{$rowNumber}: Latitude harus berupa angka (ditemukan: {$latitude}).";
                continue;
            }
            
            if ($longitude !== null && !is_numeric($longitude)) {
                $errors[] = "Baris ke-{$rowNumber}: Longitude harus berupa angka (ditemukan: {$longitude}).";
                continue;
            }
            
            // Siapkan data untuk insert
            $dataWisata[] = [
                'nama_wisata' => $row[0],
                'alamat' => $row[1],
                'kategori_id' => (int)$row[2],
                'kota_id' => (int)$row[3],
                'kecamatan_id' => (int)$row[4],
                'deskripsi' => $row[5],
                'detail' => $row[6],
                'latitude' => $latitude !== null ? (float)$latitude : null,
                'longitude' => $longitude !== null ? (float)$longitude : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Jika ada error validasi
        if (!empty($errors)) {
            $maxErrors = 10;
            $displayErrors = array_slice($errors, 0, $maxErrors);
            $errorMessage = implode('<br>', $displayErrors);
            
            if (count($errors) > $maxErrors) {
                $remaining = count($errors) - $maxErrors;
                $errorMessage .= "<br><strong>... dan {$remaining} error lainnya.</strong>";
            }
            
            return redirect()->back()->with('error', "Terdapat error pada file TXT:<br>{$errorMessage}");
        }

        // Cek apakah ada data untuk diimport
        if (empty($dataWisata)) {
            return redirect()->back()->with('error', 'File TXT tidak memiliki data valid untuk diimport.');
        }

        log_message('info', 'Total data untuk import: ' . count($dataWisata));

        // Simpan data wisata ke database
        $result = $this->wisataModel->importBatch($dataWisata);
        $totalImported = count($dataWisata);
        
        log_message('info', "Berhasil import {$totalImported} data wisata");
        
        return redirect()->back()->with('success', "Berhasil mengimport {$totalImported} data destinasi wisata!");
        
    } catch (\Exception $e) {
        log_message('error', 'Import wisata error: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
    }
}

/**
 * Helper method untuk deteksi delimiter otomatis
 */
private function detectDelimiter($line)
{
    // Cek delimiter yang paling sering muncul
    $delimiters = ['|', "\t", ';', ','];
    $counts = [];
    
    foreach ($delimiters as $delimiter) {
        $counts[$delimiter] = substr_count($line, $delimiter);
    }
    
    // Return delimiter dengan jumlah terbanyak
    arsort($counts);
    $detected = array_key_first($counts);
    
    // Jika tidak ada delimiter yang terdeteksi, default ke pipe
    return $counts[$detected] > 0 ? $detected : '|';
}
    // ==================== HELPER METHODS ====================

    // Upload multiple images
    private function uploadImages($wisataId, $files)
    {
        $uploadPath = FCPATH . 'uploads/wisata/';
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $uploaded = 0;
        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                
                if ($file->move($uploadPath, $newName)) {
                    // Simpan ke database
                    $galeriData = [
                        'wisata_id' => $wisataId,
                        'nama_file' => $newName,
                        'is_primary' => ($uploaded === 0) ? 1 : 0, // Gambar pertama sebagai primary
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->galeriModel->insert($galeriData);
                    $uploaded++;
                }
            }
        }
        
        return $uploaded;
    }

    // Hapus semua gambar wisata
    private function deleteWisataImages($wisataId)
    {
        $images = $this->galeriModel->where('wisata_id', $wisataId)->findAll();
        $uploadPath = FCPATH . 'uploads/wisata/';
        
        foreach ($images as $image) {
            // Hapus file fisik
            $filePath = $uploadPath . $image['nama_file'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Hapus dari database
            $this->galeriModel->delete($image['galeri_id']);
        }
    }

    public function destinasi()
{
    $wisataData = $this->wisataModel->getAllWithRelations();
    $kategoriData = $this->kategoriModel->findAll();

    $data = [
        'wisata' => $wisataData,
        'kategori' => $kategoriData
    ];

    return view('admin/dashboardAdmin', $data);
}

}