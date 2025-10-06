<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\KategoriModel;
use App\Models\GaleriModel;

class DashboardController extends BaseController
{
    protected $wisataModel;
    protected $kategoriModel;
    protected $galeriModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->kategoriModel = new KategoriModel();
        $this->galeriModel = new GaleriModel();
    }

    public function index()
    {
        // TAMBAHAN: Ambil semua data kategori dari database
        $kategoriData = $this->kategoriModel->findAll();

        // Ambil data wisata populer (6 wisata terbaru) dengan gambar utama
        $wisataPopuler = $this->wisataModel
            ->select('wisata.*, kategori.nama_kategori, kota.nama_kota, kecamatan.nama_kecamatan, galeri.nama_file as primary_image')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
            ->join('kota', 'kota.kota_id = wisata.kota_id')
            ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
            ->join('galeri', 'galeri.wisata_id = wisata.wisata_id AND galeri.is_primary = 1', 'left')
            ->orderBy('wisata.wisata_id', 'DESC')
            ->limit(6)
            ->findAll();

        // Ambil data untuk slider (4 wisata random)
        $sliderWisata = $this->wisataModel
            ->select('wisata.*, galeri.nama_file as primary_image')
            ->join('galeri', 'galeri.wisata_id = wisata.wisata_id AND galeri.is_primary = 1', 'left')
            ->orderBy('RAND()')
            ->limit(4)
            ->findAll();

        // PERBAIKAN: Tambahkan data kategori ke array data
        $data = [
            'kategori' => $kategoriData,           // TAMBAHAN: Data kategori dinamis
            'wisataPopuler' => $wisataPopuler,
            'sliderWisata' => $sliderWisata
        ];

        return view('user/dashboard', $data);
    }
}