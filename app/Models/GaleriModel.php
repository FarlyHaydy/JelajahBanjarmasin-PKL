<?php

// app/Models/GaleriModel.php
namespace App\Models;
use CodeIgniter\Model;

class GaleriModel extends Model
{
    protected $table = 'galeri';
    protected $primaryKey = 'galeri_id';
    protected $allowedFields = ['wisata_id', 'nama_file', 'is_primary'];
    
    public function getByWisata($wisataId)
    {
        return $this->where('wisata_id', $wisataId)
                    ->orderBy('is_primary', 'DESC')
                    ->orderBy('galeri_id', 'ASC')
                    ->findAll();
    }
    
    public function getPrimaryImage($wisataId)
    {
        return $this->where('wisata_id', $wisataId)
                    ->where('is_primary', 1)  // Gunakan 1 instead of true untuk konsistensi
                    ->first();
    }
    
    public function setPrimaryImage($wisataId, $galeriId)
    {
        // Reset semua gambar menjadi bukan primary
        $this->where('wisata_id', $wisataId)->set(['is_primary' => 0])->update();
        
        // Set gambar terpilih menjadi primary
        return $this->update($galeriId, ['is_primary' => 1]);
    }
}