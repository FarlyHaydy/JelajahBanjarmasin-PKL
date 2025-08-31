<?php

// app/Models/WisataModel.php
namespace App\Models;
use CodeIgniter\Model;

class WisataModel extends Model
{
    protected $table = 'wisata';
    protected $primaryKey = 'wisata_id';
    protected $allowedFields = [
        'nama_wisata', 'alamat', 'deskripsi', 'detail', 
        'kategori_id', 'kecamatan_id', 'kota_id',
        'latitude', 'longitude', 'created_at', 'updated_at'  // FIXED: Manual timestamps
    ];
    
    // FIXED: Disable automatic timestamps untuk avoid error
    protected $useTimestamps = false;
    // protected $createdField = 'created_at';
    // protected $updatedField = 'updated_at';

    public function getAllWithRelations()
    {
        return $this->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                    ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
                    ->join('kota', 'kota.kota_id = wisata.kota_id')
                    ->select('wisata.*, kategori.nama_kategori, kecamatan.nama_kecamatan, kota.nama_kota')
                    ->findAll();
    }

    public function getByKategori($kategori)
    {
        return $this->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                    ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
                    ->join('kota', 'kota.kota_id = wisata.kota_id')
                    ->where('kategori.nama_kategori', $kategori)
                    ->select('wisata.*, kategori.nama_kategori, kecamatan.nama_kecamatan, kota.nama_kota')
                    ->findAll();
    }

    public function getWithRelations($id)
    {
        return $this->join('kategori', 'kategori.kategori_id = wisata.kategori_id')
                    ->join('kecamatan', 'kecamatan.kecamatan_id = wisata.kecamatan_id')
                    ->join('kota', 'kota.kota_id = wisata.kota_id')
                    ->where('wisata.wisata_id', $id)
                    ->select('wisata.*, kategori.nama_kategori, kecamatan.nama_kecamatan, kota.nama_kota')
                    ->first();
    }

    // Method untuk validasi koordinat
    public function validateCoordinates($latitude, $longitude)
    {
        if (empty($latitude) || empty($longitude)) {
            return true; // Koordinat opsional
        }

        // Validasi latitude (-90 sampai 90)
        if ($latitude < -90 || $latitude > 90) {
            return false;
        }

        // Validasi longitude (-180 sampai 180)  
        if ($longitude < -180 || $longitude > 180) {
            return false;
        }

        return true;
    }

    // Method untuk mendapatkan wisata berdasarkan koordinat terdekat
    public function getNearbyWisata($latitude, $longitude, $radius = 10)
    {
        // Menggunakan formula Haversine untuk mencari wisata dalam radius tertentu (km)
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance 
                FROM wisata 
                WHERE latitude IS NOT NULL AND longitude IS NOT NULL
                HAVING distance < ? 
                ORDER BY distance LIMIT 10";
        
        return $this->db->query($sql, [$latitude, $longitude, $latitude, $radius])->getResultArray();
    }
}