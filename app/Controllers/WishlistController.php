<?php

// app/Controllers/WishlistController.php
namespace App\Controllers;

use App\Models\WishlistModel;
use App\Models\WisataModel;

class WishlistController extends BaseController
{
    protected $wishlistModel;
    protected $wisataModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
        $this->wisataModel = new WisataModel();
    }

    // Toggle bookmark via AJAX
    public function toggle()
    
    {
        // DEBUG: Log semua request data
    log_message('debug', 'Wishlist toggle called');
    log_message('debug', 'Session data: ' . json_encode(session()->get()));
    log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
    log_message('debug', 'Headers: ' . json_encode($this->request->getHeaders()));
    
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $userId = session()->get('user_id');
        $wisataId = $this->request->getPost('wisata_id');

        // Validasi input
        if (!$wisataId || !is_numeric($wisataId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID wisata tidak valid'
            ]);
        }

        // Cek apakah wisata exists
        $wisata = $this->wisataModel->find($wisataId);
        if (!$wisata) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Destinasi wisata tidak ditemukan'
            ]);
        }

        try {
            $result = $this->wishlistModel->toggleBookmark($userId, $wisataId);
            
            if ($result === 'added') {
                return $this->response->setJSON([
                    'status' => 'success',
                    'action' => 'added',
                    'message' => 'Destinasi ditambahkan ke bookmark',
                    'icon' => 'fas fa-bookmark'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'success',
                    'action' => 'removed',
                    'message' => 'Destinasi dihapus dari bookmark',
                    'icon' => 'far fa-bookmark'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Wishlist toggle error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }

    // Cek status bookmark untuk wisata tertentu
    public function checkStatus($wisataId)
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'bookmarked' => false,
                'icon' => 'far fa-bookmark'
            ]);
        }

        $userId = session()->get('user_id');
        $isBookmarked = $this->wishlistModel->isBookmarked($userId, $wisataId);

        return $this->response->setJSON([
            'bookmarked' => $isBookmarked,
            'icon' => $isBookmarked ? 'fas fa-bookmark' : 'far fa-bookmark'
        ]);
    }

    // Dapatkan semua bookmark user untuk halaman profil
    public function getUserBookmarks()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $bookmarks = $this->wishlistModel->getUserBookmarks($userId);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $bookmarks,
            'count' => count($bookmarks)
        ]);
    }

    // Hapus bookmark dari halaman profil
    public function remove($wisataId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        try {
            $this->wishlistModel->removeBookmark($userId, $wisataId);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Bookmark berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus bookmark'
            ]);
        }
    }
}