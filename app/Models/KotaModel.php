<?php
// app/Models/KotaModel.php
namespace App\Models;
use CodeIgniter\Model;

class KotaModel extends Model
{
    protected $table = 'kota';
    protected $primaryKey = 'kota_id';
    protected $allowedFields = ['nama_kota'];
    protected $useTimestamps = false;
}
