<?php

namespace App\Controllers;

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

    // Get all images for a wisata
    public function getByWisata($wisataId)
    {
        try {
            // Validasi wisata exists
            $wisata = $this->wisataModel->find($wisataId);
            if (!$wisata) {
                return $this->response->setJSON([]);
            }

            $images = $this->galeriModel->where('wisata_id', $wisataId)
                                       ->orderBy('is_primary', 'DESC')
                                       ->orderBy('galeri_id', 'ASC')
                                       ->findAll();
            
            return $this->response->setJSON($images);
            
        } catch (\Exception $e) {
            log_message('error', 'Gallery getByWisata error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to load images'
            ]);
        }
    }

    // Upload new image
    public function upload($wisataId)
    {
        try {
            // Validasi wisata exists
            $wisata = $this->wisataModel->find($wisataId);
            if (!$wisata) {
                return $this->response->setJSON(['success' => false, 'message' => 'Wisata tidak ditemukan']);
            }

            // Cek jumlah gambar yang sudah ada
            $existingCount = $this->galeriModel->where('wisata_id', $wisataId)->countAllResults();
            if ($existingCount >= 5) {
                return $this->response->setJSON(['success' => false, 'message' => 'Maksimal 5 gambar per wisata']);
            }

            // Validasi file upload
            $validation = \Config\Services::validation();
            $rules = [
                'gambar' => [
                    'uploaded[gambar]',
                    'max_size[gambar,10240]',
                    'is_image[gambar]',
                    'mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]'
                ]
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid: ' . implode(', ', $validation->getErrors())]);
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
                        return $this->response->setJSON(['success' => true, 'message' => 'Gambar berhasil diupload']);
                    } else {
                        // Hapus file jika gagal simpan ke database
                        unlink($uploadPath . $newName);
                        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan data gambar']);
                    }
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupload file']);
                }
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid atau sudah dipindahkan']);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Gallery upload error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    // Set primary image
    public function setPrimary($wisataId)
    {
        try {
            $galeriId = $this->request->getPost('galeri_id');
            
            $gambar = $this->galeriModel->find($galeriId);
            
            if (!$gambar || $gambar['wisata_id'] != $wisataId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gambar tidak ditemukan']);
            }

            // Reset semua gambar wisata menjadi bukan primary
            $this->galeriModel->where('wisata_id', $wisataId)->set(['is_primary' => 0])->update();
            
            // Set gambar yang dipilih sebagai primary
            $this->galeriModel->update($galeriId, ['is_primary' => 1]);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Gambar utama berhasil diubah']);
            
        } catch (\Exception $e) {
            log_message('error', 'Gallery setPrimary error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    // Delete image
    public function delete($wisataId)
    {
        try {
            $galeriId = $this->request->getPost('galeri_id');
            
            $gambar = $this->galeriModel->find($galeriId);
            
            if (!$gambar || $gambar['wisata_id'] != $wisataId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gambar tidak ditemukan']);
            }

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
            
            return $this->response->setJSON(['success' => true, 'message' => 'Gambar berhasil dihapus']);
            
        } catch (\Exception $e) {
            log_message('error', 'Gallery delete error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }
}