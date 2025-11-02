<?php

namespace App\Models;
use CodeIgniter\Model;

class BookmarkModel extends Model
{
    protected $table = 'bookmark';
    protected $primaryKey = 'bookmark_id';
    protected $allowedFields = [
        'user_id', 'wisata_id', 'created_at'
    ];
    
    protected $useTimestamps = false;

    // Cek apakah wisata sudah di-bookmark oleh user
    public function isBookmarked($userId, $wisataId)
    {
        return $this->where('user_id', $userId)
                   ->where('wisata_id', $wisataId)
                   ->countAllResults() > 0;
    }

    // Tambah bookmark
    public function addBookmark($userId, $wisataId)
    {
        if ($this->isBookmarked($userId, $wisataId)) {
            return false;
        }

        $data = [
            'user_id' => $userId,
            'wisata_id' => $wisataId,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }

    // Hapus bookmark
    public function removeBookmark($userId, $wisataId)
    {
        return $this->where('user_id', $userId)
                   ->where('wisata_id', $wisataId)
                   ->delete();
    }

    // Toggle/icon bookmark
    public function toggleBookmark($userId, $wisataId)
    {
        if ($this->isBookmarked($userId, $wisataId)) {
            $this->removeBookmark($userId, $wisataId);
            return 'removed';
        } else {
            $this->addBookmark($userId, $wisataId);
            return 'added';
        }
    }

    // Dapatkan semua bookmark user dengan detail wisata
    public function getUserBookmarks($userId)
    {
        return $this->select('bookmark.*, wisata.*, kategori.nama_kategori, kecamatan.nama_kecamatan, kota.nama_kota')
                   ->join('wisata', 'wisata.wisata_id = bookmark.wisata_id')
                   ->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                   ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
                   ->join('kota', 'kota.kota_id = wisata.kota_id')
                   ->where('bookmark.user_id', $userId)
                   ->orderBy('bookmark.created_at', 'DESC')
                   ->findAll();
    }

    // Dapatkan jumlah bookmark user
    public function getUserBookmarkCount($userId)
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

    // Dapatkan wisata yang paling sering di-bookmark
    public function getPopularBookmarks($limit = 10)
    {
        return $this->select('wisata.*, kategori.nama_kategori, COUNT(bookmark.wisata_id) as bookmark_count')
                   ->join('wisata', 'wisata.wisata_id = bookmark.wisata_id')
                   ->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                   ->groupBy('bookmark.wisata_id')
                   ->orderBy('bookmark_count', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    // Hapus semua bookmark user
    public function deleteUserBookmarks($userId)
    {
        return $this->where('user_id', $userId)->delete();
    }

    // Hapus semua bookmark untuk wisata tertentu
    public function deleteWisataBookmarks($wisataId)
    {
        return $this->where('wisata_id', $wisataId)->delete();
    }
}