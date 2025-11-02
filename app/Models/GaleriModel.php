<?php

namespace App\Models;
use CodeIgniter\Model;

class GaleriModel extends Model
{
    protected $table = 'galeri';
    protected $primaryKey = 'galeri_id';
    protected $allowedFields = ['wisata_id', 'nama_file', 'is_primary'];
    
    // Mengambil semua gambar berdasarkan wisata_id, diurutkan primary terlebih dahulu
    public function getByWisata($wisataId)
    {
        return $this->where('wisata_id', $wisataId)
                    ->orderBy('is_primary', 'DESC')
                    ->orderBy('galeri_id', 'ASC')
                    ->findAll();
    }
    
    // Mengambil gambar utama (primary) dari suatu wisata
    public function getPrimaryImage($wisataId)
    {
        return $this->where('wisata_id', $wisataId)
                    ->where('is_primary', 1) 
                    ->first();
    }
    
    // Set gambar tertentu sebagai primary dan reset gambar lainnya
    public function setPrimaryImage($wisataId, $galeriId)
    {
        // Reset semua gambar menjadi bukan primary
        $this->where('wisata_id', $wisataId)->set(['is_primary' => 0])->update();
        
        // Set gambar terpilih menjadi primary
        return $this->update($galeriId, ['is_primary' => 1]);
    }
}