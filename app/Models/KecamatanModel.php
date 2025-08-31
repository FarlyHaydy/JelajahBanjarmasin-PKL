<?php

// app/Models/KecamatanModel.php
namespace App\Models;
use CodeIgniter\Model;

class KecamatanModel extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'kecamatan_id';
    protected $allowedFields = ['nama_kecamatan', 'kota_id'];
    protected $useTimestamps = false;
    
    // Method yang diperlukan untuk dashboard admin
    public function getWithKota()
    {
        return $this->select('kecamatan.*, kota.nama_kota')
                    ->join('kota', 'kota.kota_id = kecamatan.kota_id')
                    ->orderBy('kota.nama_kota', 'ASC')
                    ->orderBy('kecamatan.nama_kecamatan', 'ASC')
                    ->findAll();
    }
    
    // Method untuk get kecamatan berdasarkan kota (AJAX)
    public function getByKota($kotaId)
    {
        return $this->where('kota_id', $kotaId)
                    ->orderBy('nama_kecamatan', 'ASC')
                    ->findAll();
    }
}