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
        'latitude', 'longitude', 'created_at', 'updated_at'
    ];
    
    // Disable automatic timestamps karena kita handle manual
    protected $useTimestamps = false;

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

    /**
     * Method untuk validasi koordinat
     */
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

    /**
     * Method untuk validasi foreign keys
     */
    public function validateForeignKeys($kategori_id, $kota_id, $kecamatan_id)
    {
        $db = \Config\Database::connect();
        
        // Cek kategori_id exists
        $kategori = $db->table('kategori')
                      ->where('kategori_id', $kategori_id)
                      ->countAllResults();
        
        if ($kategori == 0) {
            return false;
        }
        
        // Cek kota_id exists
        $kota = $db->table('kota')
                  ->where('kota_id', $kota_id)
                  ->countAllResults();
        
        if ($kota == 0) {
            return false;
        }
        
        // Cek kecamatan_id exists
        $kecamatan = $db->table('kecamatan')
                       ->where('kecamatan_id', $kecamatan_id)
                       ->countAllResults();
        
        if ($kecamatan == 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Method untuk mendapatkan wisata berdasarkan koordinat terdekat
     */
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
    
    /**
     * Method untuk import data secara batch
     */
    public function importBatch($data)
    {
        // Validasi data sebelum insert
        foreach ($data as $index => $row) {
            // Validasi koordinat jika diisi
            if (!empty($row['latitude']) && !empty($row['longitude'])) {
                if (!$this->validateCoordinates($row['latitude'], $row['longitude'])) {
                    throw new \Exception("Baris " . ($index + 2) . ": Koordinat tidak valid (Latitude: {$row['latitude']}, Longitude: {$row['longitude']})");
                }
            }
            
            // Validasi foreign key exists
            if (!$this->validateForeignKeys($row['kategori_id'], $row['kota_id'], $row['kecamatan_id'])) {
                throw new \Exception("Baris " . ($index + 2) . ": ID Kategori ({$row['kategori_id']}), Kota ({$row['kota_id']}), atau Kecamatan ({$row['kecamatan_id']}) tidak ditemukan di database");
            }
        }
        
        // Insert batch
        $result = $this->builder()->insertBatch($data);
        
        if ($result === false) {
            throw new \Exception("Gagal melakukan insert batch ke database");
        }
        
        return $result;
    }
}