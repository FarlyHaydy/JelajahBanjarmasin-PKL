<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\GaleriModel;

class UserController extends BaseController
{
    protected $wisataModel;
    protected $galeriModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->galeriModel = new GaleriModel();
        $this->wishlistModel = new \App\Models\WishlistModel();
    }

    // Halaman Rekreasi
    public function rekreasi()
    {
        $wisataRekreasi = $this->wisataModel->getByKategori('Rekreasi');
        
        // Ambil gambar utama untuk setiap wisata
        foreach ($wisataRekreasi as &$wisata) {
            $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
            $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
        }
        
        $data = [
            'wisata' => $wisataRekreasi,
            'kategori' => 'Rekreasi'
        ];
        
        return view('user/rekreasi', $data);
    }

    // Halaman Kuliner
    public function kuliner()
    {
        $wisataKuliner = $this->wisataModel->getByKategori('Kuliner');
        
        // Ambil gambar utama untuk setiap wisata
        foreach ($wisataKuliner as &$wisata) {
            $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
            $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
        }
        
        $data = [
            'wisata' => $wisataKuliner,
            'kategori' => 'Kuliner'
        ];
        
        return view('user/kuliner', $data);
    }

    // Halaman Religi
    public function religi()
    {
        $wisataReligi = $this->wisataModel->getByKategori('Religi');
        
        // Ambil gambar utama untuk setiap wisata
        foreach ($wisataReligi as &$wisata) {
            $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
            $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
        }
        
        $data = [
            'wisata' => $wisataReligi,
            'kategori' => 'Religi'
        ];
        
        return view('user/religi', $data);
    }

    // Detail Wisata - Method yang diperbaiki
      public function detailWisata($id)
    {
        // Ambil data wisata dengan relasi
        $wisata = $this->wisataModel->getWithRelations($id);
        
        if (!$wisata) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Wisata tidak ditemukan');
        }

        // Ambil gambar wisata
        $images = $this->galeriModel->getByWisata($id);

        // NEW: Cek apakah user sudah bookmark wisata ini
        $isBookmarked = false;
        if (session()->get('user_id')) {
            $isBookmarked = $this->wishlistModel->isBookmarked(session()->get('user_id'), $id);
        }

        $data = [
            'wisata' => $wisata,
            'images' => $images,
            'isBookmarked' => $isBookmarked // NEW
        ];

        return view('user/detailWisata', $data);
    }

    // Alias untuk detail wisata (untuk kompatibilitas routing)
    public function detail($id = null)
    {
        return $this->detailWisata($id);
    }

    // API untuk pencarian wisata
    public function searchWisata()
    {
        $keyword = $this->request->getGet('q');
        $kategori = $this->request->getGet('kategori');
        
        $builder = $this->wisataModel->builder();
        $builder->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
                ->join('kota', 'kota.kota_id = wisata.kota_id')
                ->select('wisata.*, kategori.nama_kategori, kecamatan.nama_kecamatan, kota.nama_kota');
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('wisata.nama_wisata', $keyword)
                    ->orLike('wisata.deskripsi', $keyword)
                    ->orLike('wisata.alamat', $keyword)
                    ->groupEnd();
        }
        
        if (!empty($kategori)) {
            $builder->where('kategori.nama_kategori', $kategori);
        }
        
        $results = $builder->get()->getResultArray();
        
        // Tambahkan gambar utama
        foreach ($results as &$wisata) {
            $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
            $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
        }
        
        return $this->response->setJSON($results);
    }
}