<?php

namespace App\Models;
use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    protected $allowedFields = ['nama_kategori'];
    
    // Mengambil semua data wisata beserta relasi kategori, kecamatan, dan kota
    public function getAllWisataWithRelations()
    {
        return $this->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                    ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
                    ->join('kota', 'kota.kota_id = wisata.kota_id')
                    ->select('wisata.*, kategori.nama_kategori, kecamatan.nama_kecamatan, kota.nama_kota')
                    ->orderBy('wisata.created_at', 'ASC')
                    ->findAll(); 
    }
}