<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\GaleriModel;
use App\Models\KategoriModel;

class UserController extends BaseController
{
    protected $wisataModel;
    protected $galeriModel;
    protected $wishlistModel;
    protected $kategoriModel; 

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->galeriModel = new GaleriModel();
        $this->wishlistModel = new \App\Models\WishlistModel();
        $this->kategoriModel = new KategoriModel();
    }

    private function getKategoriData()
{
    return $this->kategoriModel->findAll();
}

// Update method rekreasi
public function rekreasi()
{
    $wisataRekreasi = $this->wisataModel->getByKategori('Rekreasi');
    
    // Ambil gambar utama untuk setiap wisata
    foreach ($wisataRekreasi as &$wisata) {
        $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
        $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
    }
    
    $data = [
        'kategori' => $this->getKategoriData(),    // TAMBAHAN: Data kategori untuk navbar dinamis
        'wisata' => $wisataRekreasi,
        'namaKategori' => 'Rekreasi'
    ];
    
    return view('user/rekreasi', $data);
}

// Update method kuliner
public function kuliner()
{
    $wisataKuliner = $this->wisataModel->getByKategori('Kuliner');
    
    // Ambil gambar utama untuk setiap wisata
    foreach ($wisataKuliner as &$wisata) {
        $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
        $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
    }
    
    $data = [
        'kategori' => $this->getKategoriData(),    // TAMBAHAN: Data kategori untuk navbar dinamis
        'wisata' => $wisataKuliner,
        'namaKategori' => 'Kuliner'
    ];
    
    return view('user/kuliner', $data);
}

// Update method religi  
public function religi()
{
    $wisataReligi = $this->wisataModel->getByKategori('Religi');
    
    // Ambil gambar utama untuk setiap wisata
    foreach ($wisataReligi as &$wisata) {
        $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
        $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
    }
    
    $data = [
        'kategori' => $this->getKategoriData(),    // TAMBAHAN: Data kategori untuk navbar dinamis
        'wisata' => $wisataReligi,
        'namaKategori' => 'Religi'
    ];
    
    return view('user/religi', $data);
}

// Update method kategoriWisata
public function kategoriWisata($namaKategori = null)
{
    if (!$namaKategori) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
    }

    // Ubah URL-friendly name kembali ke nama asli
    $namaKategori = ucfirst(strtolower($namaKategori));
    
    // Cek apakah kategori exists
    $kategoriData = $this->kategoriModel->where('nama_kategori', $namaKategori)->first();
    if (!$kategoriData) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
    }

    // Ambil wisata berdasarkan kategori
    $wisataByKategori = $this->wisataModel->getByKategori($namaKategori);
    
    // ✅ PERBAIKAN: Ambil gambar utama untuk setiap wisata (SAMA seperti kategori bawaan)
    foreach ($wisataByKategori as &$wisata) {
        $primaryImage = $this->galeriModel->getPrimaryImage($wisata['wisata_id']);
        $wisata['primary_image'] = $primaryImage ? $primaryImage['nama_file'] : 'default.jpg';
        
        
    }
    
    $data = [
        'wisata' => $wisataByKategori,
        'kategori' => $namaKategori,
        'kategoriData' => $kategoriData,
        'kategori_list' => $this->getKategoriData()
    ];
    
    return view('user/kategori_wisata', $data);
}

public function detailWisata($id)
    {
        // Ambil data wisata dengan relasi
        $wisata = $this->wisataModel->getWithRelations($id);
        $kategoriData = $this->getKategoriData();
        
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
            'kategori' => $kategoriData,
            'wisata' => $wisata,
            'images' => $images,
            'isBookmarked' => $isBookmarked
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

    public function faq()
    {
        $kategoriData = $this->getKategoriData();
        $data = [
            'kategori' => $kategoriData
        ];


        return view('user/faq', $data); 
    }
}