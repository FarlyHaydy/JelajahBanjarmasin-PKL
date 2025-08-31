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
        $this->wishlistModel = new \App\Models\WishlistModel();
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
        
        // Debug log
        log_message('debug', 'Wisata count: ' . count($wisataData));
        log_message('debug', 'Kategori count: ' . count($kategoriData));
        log_message('debug', 'Kota count: ' . count($kotaData));
        log_message('debug', 'Kecamatan count: ' . count($kecamatanData));
        log_message('debug', 'Users count: ' . count($usersData)); // NEW: Debug users
        
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
            // Hapus semua wishlist user ini
            $this->wishlistModel->where('user_id', $id)->delete();
            
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
        $wisata = $this->wisataModel->getWithRelations($id);
        
        if (!$wisata) {
            return $this->response->setJSON(['error' => 'Wisata tidak ditemukan']);
        }

        return $this->response->setJSON(['wisata' => $wisata]);
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

        // FIXED: Update data wisata dengan manual timestamp
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
            'updated_at' => date('Y-m-d H:i:s')  // FIXED: Manual timestamp
        ];

        // FIXED: Update dengan try-catch untuk error handling
        try {
            $this->wisataModel->update($id, $wisataData);
        } catch (\Exception $e) {
            log_message('error', 'Update wisata error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }

        // Upload gambar baru jika ada
        $files = $this->request->getFiles();
        if (isset($files['gambar']) && $files['gambar'][0]->isValid()) {
            // Hapus gambar lama
            $this->deleteWisataImages($id);
            // Upload gambar baru
            $this->uploadImages($id, $files['gambar']);
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
            $this->wishlistModel->deleteWisataBookmarks($id);

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
}