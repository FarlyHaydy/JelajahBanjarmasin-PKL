<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GaleriModel;
use App\Models\WisataModel;

class GaleriController extends BaseController
{
    protected $galeriModel;
    protected $wisataModel;
    
    public function __construct()
    {
        $this->galeriModel = new GaleriModel();
        $this->wisataModel = new WisataModel();
    }

    // Upload gambar ke wisata
    public function upload($wisataId)
    {
        // Validasi wisata exists
        $wisata = $this->wisataModel->find($wisataId);
        if (!$wisata) {
            return redirect()->back()->with('error', 'Wisata tidak ditemukan');
        }

        // Validasi file upload
        $validation = \Config\Services::validation();
        $rules = [
            'gambar' => [
                'uploaded[gambar]',
                'max_size[gambar,2048]', // 2MB max
                'is_image[gambar]',
                'mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]'
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'File tidak valid: ' . implode(', ', $validation->getErrors()));
        }

        // Cek jumlah gambar yang sudah ada
        $existingCount = $this->galeriModel->where('wisata_id', $wisataId)->countAllResults();
        if ($existingCount >= 5) {
            return redirect()->back()->with('error', 'Maksimal 5 gambar per wisata');
        }

        $file = $this->request->getFile('gambar');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/wisata/';
            
            // Buat folder jika belum ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate nama file baru
            $newName = $file->getRandomName();
            
            // Pindahkan file
            if ($file->move($uploadPath, $newName)) {
                // Cek apakah ini gambar pertama (akan jadi primary)
                $isPrimary = ($existingCount === 0) ? 1 : 0;
                
                // Simpan ke database
                $galeriData = [
                    'wisata_id' => $wisataId,
                    'nama_file' => $newName,
                    'is_primary' => $isPrimary,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $galeriId = $this->galeriModel->insert($galeriData);
                
                if ($galeriId) {
                    return redirect()->back()->with('success', 'Gambar berhasil diupload');
                } else {
                    // Hapus file jika gagal simpan ke database
                    unlink($uploadPath . $newName);
                    return redirect()->back()->with('error', 'Gagal menyimpan data gambar');
                }
            } else {
                return redirect()->back()->with('error', 'Gagal mengupload file');
            }
        } else {
            return redirect()->back()->with('error', 'File tidak valid atau sudah dipindahkan');
        }
    }

    // Hapus gambar
    public function delete($galeriId)
    {
        $gambar = $this->galeriModel->find($galeriId);
        
        if (!$gambar) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan');
        }

        $wisataId = $gambar['wisata_id'];
        $filePath = FCPATH . 'uploads/wisata/' . $gambar['nama_file'];
        
        // Hapus file fisik
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Hapus dari database
        $this->galeriModel->delete($galeriId);
        
        // Jika yang dihapus adalah primary image, set gambar lain sebagai primary
        if ($gambar['is_primary'] == 1) {
            $nextImage = $this->galeriModel->where('wisata_id', $wisataId)
                                          ->orderBy('galeri_id', 'ASC')
                                          ->first();
            
            if ($nextImage) {
                $this->galeriModel->update($nextImage['galeri_id'], ['is_primary' => 1]);
            }
        }
        
        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
    }

    // Set gambar sebagai primary
    public function setPrimary($galeriId)
    {
        $gambar = $this->galeriModel->find($galeriId);
        
        if (!$gambar) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gambar tidak ditemukan']);
        }

        $wisataId = $gambar['wisata_id'];
        
        // Reset semua gambar wisata menjadi bukan primary
        $this->galeriModel->where('wisata_id', $wisataId)->set(['is_primary' => 0])->update();
        
        // Set gambar yang dipilih sebagai primary
        $this->galeriModel->update($galeriId, ['is_primary' => 1]);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Gambar utama berhasil diubah']);
    }

    // Get semua gambar wisata (AJAX)
    public function getByWisata($wisataId)
    {
        $images = $this->galeriModel->where('wisata_id', $wisataId)
                                   ->orderBy('is_primary', 'DESC')
                                   ->orderBy('galeri_id', 'ASC')
                                   ->findAll();
        
        return $this->response->setJSON($images);
    }

    // Update urutan gambar (drag & drop)
    public function updateOrder()
    {
        $orders = $this->request->getJSON(true);
        
        if (!$orders || !is_array($orders)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
        }

        foreach ($orders as $order) {
            if (isset($order['id']) && isset($order['position'])) {
                $this->galeriModel->update($order['id'], ['sort_order' => $order['position']]);
            }
        }
        
        return $this->response->setJSON(['success' => true, 'message' => 'Urutan gambar berhasil diupdate']);
    }

    // Bulk delete gambar
    public function bulkDelete()
    {
        $galeriIds = $this->request->getPost('galeri_ids');
        
        if (!$galeriIds || !is_array($galeriIds)) {
            return redirect()->back()->with('error', 'Tidak ada gambar yang dipilih');
        }

        $deletedCount = 0;
        $uploadPath = FCPATH . 'uploads/wisata/';
        
        foreach ($galeriIds as $galeriId) {
            $gambar = $this->galeriModel->find($galeriId);
            
            if ($gambar) {
                // Hapus file fisik
                $filePath = $uploadPath . $gambar['nama_file'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Hapus dari database
                $this->galeriModel->delete($galeriId);
                $deletedCount++;
            }
        }
        
        return redirect()->back()->with('success', "$deletedCount gambar berhasil dihapus");
    }

    // Generate thumbnail (untuk optimasi loading)
    public function generateThumbnail($galeriId, $width = 300, $height = 200)
    {
        $gambar = $this->galeriModel->find($galeriId);
        
        if (!$gambar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Gambar tidak ditemukan');
        }

        $originalPath = FCPATH . 'uploads/wisata/' . $gambar['nama_file'];
        $thumbnailPath = FCPATH . 'uploads/wisata/thumbs/';
        
        // Buat folder thumbnail jika belum ada
        if (!is_dir($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }
        
        $thumbnailFile = pathinfo($gambar['nama_file'], PATHINFO_FILENAME) . '_' . $width . 'x' . $height . '.' . pathinfo($gambar['nama_file'], PATHINFO_EXTENSION);
        $thumbnailFullPath = $thumbnailPath . $thumbnailFile;
        
        // Jika thumbnail sudah ada, tampilkan
        if (file_exists($thumbnailFullPath)) {
            $this->response->setHeader('Content-Type', mime_content_type($thumbnailFullPath));
            return file_get_contents($thumbnailFullPath);
        }
        
        // Generate thumbnail baru
        $image = \Config\Services::image();
        
        try {
            $image->withFile($originalPath)
                  ->resize($width, $height, true, 'center')
                  ->save($thumbnailFullPath, 80); // Quality 80%
            
            $this->response->setHeader('Content-Type', mime_content_type($thumbnailFullPath));
            return file_get_contents($thumbnailFullPath);
            
        } catch (\Exception $e) {
            // Jika gagal generate thumbnail, tampilkan gambar asli
            $this->response->setHeader('Content-Type', mime_content_type($originalPath));
            return file_get_contents($originalPath);
        }
    }

    // Validate dan resize gambar saat upload
    private function processImage($file, $maxWidth = 1920, $maxHeight = 1080, $quality = 85)
    {
        if (!$file->isValid()) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/wisata/';
        $newName = $file->getRandomName();
        
        // Pindahkan file sementara
        if (!$file->move($uploadPath, $newName)) {
            return false;
        }

        $fullPath = $uploadPath . $newName;
        
        // Cek dimensi gambar
        $imageInfo = getimagesize($fullPath);
        if (!$imageInfo) {
            unlink($fullPath);
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Resize jika terlalu besar
        if ($width > $maxWidth || $height > $maxHeight) {
            $image = \Config\Services::image();
            
            try {
                $image->withFile($fullPath)
                      ->resize($maxWidth, $maxHeight, true, 'center')
                      ->save($fullPath, $quality);
            } catch (\Exception $e) {
                unlink($fullPath);
                return false;
            }
        }
        
        return $newName;
    }
}