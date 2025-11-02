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
        'gambar' => 'uploaded[gambar.0]|max_size[gambar.0,10240]|is_image[gambar.0]'
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
        'created_at' => date('Y-m-d H:i:s'),  
        'updated_at' => date('Y-m-d H:i:s')   
    ];

    try {
        $wisataId = $this->wisataModel->insert($wisataData);

        if (!$wisataId) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data wisata');
        }

        // Upload gambar - GUNAKAN GALERICONTROLLER
        $files = $this->request->getFiles();
        if (isset($files['gambar'])) {
            foreach ($files['gambar'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Simulate upload to GaleriController
                    $this->uploadToGaleri($wisataId, $file);
                }
            }
        }

        return redirect()->to('/admin/dashboard#create-section')->with('success', 'Postingan wisata berhasil dibuat!');
    } catch (\Exception $e) {
        log_message('error', 'Create wisata error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

// Helper method untuk upload ke galeri
private function uploadToGaleri($wisataId, $file)
{
    $uploadPath = FCPATH . 'uploads/wisata/';
    
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $newName = $file->getRandomName();
    
    if ($file->move($uploadPath, $newName)) {
        // Cek jumlah gambar yang sudah ada
        $existingCount = $this->galeriModel->where('wisata_id', $wisataId)->countAllResults();
        $isPrimary = ($existingCount === 0) ? 1 : 0;
        
        // Simpan ke database
        $galeriData = [
            'wisata_id' => $wisataId,
            'nama_file' => $newName,
            'is_primary' => $isPrimary,
        ];
        
        return $this->galeriModel->insert($galeriData);
    }
    
    return false;
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
    // Di method updateWisata - HAPUS bagian ini:
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
            return redirect()->to('/admin/dashboard#edit-section')->with('success', 'Postingan wisata berhasil diperbarui!');
        } catch (\Exception $e) {
            log_message('error', 'Update wisata error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
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

            return redirect()->to('/admin/dashboard#delete-section')->with('success', 'Postingan wisata berhasil dihapus!');
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
        ];

        $this->kategoriModel->insert($data);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function deleteKategori($id)
    {
        // Cek apakah kategori masih digunakan
        $wisataCount = $this->wisataModel->where('kategori_id', $id)->countAllResults();
        
        if ($wisataCount > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh wisata');
        }

        $this->kategoriModel->delete($id);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kategori berhasil dihapus!');
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
        ];

        $this->kotaModel->insert($data);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kota berhasil ditambahkan!');
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
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kota berhasil dihapus!');
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
        ];

        $this->kecamatanModel->insert($data);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kecamatan berhasil ditambahkan!');
    }

    public function deleteKecamatan($id)
    {
        // Cek apakah kecamatan masih digunakan
        $wisataCount = $this->wisataModel->where('kecamatan_id', $id)->countAllResults();
        
        if ($wisataCount > 0) {
            return redirect()->back()->with('error', 'Kecamatan tidak dapat dihapus karena masih digunakan oleh wisata');
        }

        $this->kecamatanModel->delete($id);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kecamatan berhasil dihapus!');
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
    ];

    try {
        $this->kategoriModel->update($id, $data);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kategori berhasil diperbarui!');
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
    ];

    try {
        $this->kotaModel->update($id, $data);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kota berhasil diperbarui!');
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
    ];

    try {
        $this->kecamatanModel->update($id, $data);
        return redirect()->to('/admin/dashboard#master-section')->with('success', 'Kecamatan berhasil diperbarui!');
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

    // Validasi file upload untuk XLSX
    $validationRules = [
        'file_xlsx' => [
            'rules' => 'uploaded[file_xlsx]|ext_in[file_xlsx,xlsx,xls]|max_size[file_xlsx,10240]',
            'errors' => [
                'uploaded' => 'File Excel harus diupload.',
                'ext_in' => 'File harus berformat Excel (XLSX atau XLS).',
                'max_size' => 'Ukuran file maksimal 10MB.'
            ]
        ]
    ]; 

    if (!$this->validate($validationRules)) {
        $errors = $this->validator->getErrors();
        $errorMessage = is_array($errors) ? implode(', ', $errors) : $errors;
        return redirect()->back()->with('error', $errorMessage);
    }

    $file = $this->request->getFile('file_xlsx');
    
    // Validasi file
    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'File tidak valid atau gagal diupload.');
    }

    try {
        // Load library PhpSpreadsheet
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file->getTempName());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Konversi ke array
        $rows = $worksheet->toArray();
        
        if (empty($rows)) {
            return redirect()->back()->with('error', 'File Excel kosong.');
        }

        log_message('info', 'Total baris dalam file: ' . count($rows));

        $dataWisata = [];
        $errors = [];
        
        // Skip header (baris pertama)
        $startIndex = 1;
        
        // Parse setiap baris
        for ($i = $startIndex; $i < count($rows); $i++) {
            $row = $rows[$i];
            $rowNumber = $i + 1;
            
            // Skip baris kosong
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Validasi jumlah kolom minimal
            if (count($row) < 9) {
                $errors[] = "Baris ke-{$rowNumber}: Data tidak lengkap (harus ada 9 kolom, ditemukan " . count($row) . " kolom).";
                continue;
            }
            
            // Trim semua nilai
            $row = array_map('trim', $row);
            
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
            
            // Validasi deskripsi dan detail
            if (empty($row[5])) {
                $errors[] = "Baris ke-{$rowNumber}: Deskripsi tidak boleh kosong.";
                continue;
            }
            
            if (empty($row[6])) {
                $errors[] = "Baris ke-{$rowNumber}: Detail tidak boleh kosong.";
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
            
            return redirect()->back()->with('error', "Terdapat error pada file Excel:<br>{$errorMessage}");
        }

        // Cek apakah ada data untuk diimport
        if (empty($dataWisata)) {
            return redirect()->back()->with('error', 'File Excel tidak memiliki data valid untuk diimport.');
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

// Tambahkan method untuk download template Excel
public function downloadTemplate()
{
    // Create new Spreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set header
    $headers = [
        'Nama Wisata', 
        'Alamat', 
        'Kategori_ID', 
        'Kota_ID', 
        'Kecamatan_ID', 
        'Deskripsi', 
        'Detail', 
        'Latitude', 
        'Longitude'
    ];
    
    $sheet->fromArray($headers, null, 'A1');
    
    // Set example data
    $exampleData = [
        'Pantai Indah',
        'Jl. Pantai No. 1, Kelurahan Pantai Indah',
        '1',
        '1', 
        '1',
        'Pantai dengan pemandangan sunset yang menakjubkan',
        'Pantai ini memiliki pasir putih yang bersih dan air laut yang jernih. Cocok untuk berenang, berselancar, dan camping.',
        '-3.316694',
        '114.590111'
    ];
    
    $sheet->fromArray($exampleData, null, 'A2');
    
    // Auto size columns
    foreach(range('A','I') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Set style for header
    $headerStyle = [
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'E6E6FA']
        ]
    ];
    $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
    
    // Create writer and output
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="template_import_wisata.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
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