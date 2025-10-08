<?php

namespace App\Controllers;

use App\Models\BookmarkModel;
use App\Models\WisataModel;

class BookmarkController extends BaseController
{
    protected $bookmarkModel;
    protected $wisataModel;

    public function __construct()
    {
        $this->bookmarkModel = new BookmarkModel();
        $this->wisataModel = new WisataModel();
    }

    // Toggle bookmark via AJAX
    public function toggle()
    {
        log_message('debug', 'Bookmark toggle called');
        log_message('debug', 'Session data: ' . json_encode(session()->get()));
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $userId = session()->get('user_id');
        $wisataId = $this->request->getPost('wisata_id');

        if (!$wisataId || !is_numeric($wisataId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID wisata tidak valid'
            ]);
        }

        $wisata = $this->wisataModel->find($wisataId);
        if (!$wisata) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Destinasi wisata tidak ditemukan'
            ]);
        }

        try {
            $result = $this->bookmarkModel->toggleBookmark($userId, $wisataId);
            
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
            log_message('error', 'Bookmark toggle error: ' . $e->getMessage());
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
        $isBookmarked = $this->bookmarkModel->isBookmarked($userId, $wisataId);

        return $this->response->setJSON([
            'bookmarked' => $isBookmarked,
            'icon' => $isBookmarked ? 'fas fa-bookmark' : 'far fa-bookmark'
        ]);
    }

    // Dapatkan semua bookmark user
    public function getUserBookmarks()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $bookmarks = $this->bookmarkModel->getUserBookmarks($userId);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $bookmarks,
            'count' => count($bookmarks)
        ]);
    }

    // Hapus bookmark
    public function remove($wisataId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        try {
            $this->bookmarkModel->removeBookmark($userId, $wisataId);
            
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