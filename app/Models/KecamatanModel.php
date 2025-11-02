<?php

namespace App\Models;
use CodeIgniter\Model;

class KecamatanModel extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'kecamatan_id';
    protected $allowedFields = ['nama_kecamatan', 'kota_id'];
    
    // Mengambil semua kecamatan beserta nama kota, diurutkan berdasarkan kota dan kecamatan
    public function getWithKota()
{
    return $this->select('kecamatan.kecamatan_id, kecamatan.nama_kecamatan, kecamatan.kota_id, kota.nama_kota')
                ->join('kota', 'kota.kota_id = kecamatan.kota_id')
                ->orderBy('kota.nama_kota', 'ASC')
                ->orderBy('kecamatan.nama_kecamatan', 'ASC')
                ->findAll();
}
    // Mengambil daftar kecamatan berdasarkan kota_id (AJAX)
    public function getByKota($kotaId)
    {
        return $this->where('kota_id', $kotaId)
                    ->orderBy('nama_kecamatan', 'ASC')
                    ->findAll();
    }
}