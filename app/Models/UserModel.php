<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'Username',
        'Nama_Asli', 
        'Nomor_Telepon',
        'Jenis_Kelamin',
        'Alamat',
        'Kota',
        'Kecamatan',
        'Email'
    ];

    // Validation rules
    protected $validationRules = [
        'Username' => 'required|min_length[3]|max_length[255]|is_unique[user.Username]',
        'Nama_Asli' => 'required|min_length[2]|max_length[255]',
        'Nomor_Telepon' => 'required|min_length[10]|max_length[15]',
        'Jenis_Kelamin' => 'required|in_list[Laki-Laki,Perempuan]',
        'Alamat' => 'required|min_length[5]',
        'Email' => 'required|valid_email|max_length[255]|is_unique[user.Email]'
    ];

    protected $validationMessages = [
        'Username' => [
            'required' => 'Username harus diisi',
            'is_unique' => 'Username sudah digunakan',
            'min_length' => 'Username minimal 3 karakter'
        ],
        'Email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'Nama_Asli' => [
            'required' => 'Nama asli harus diisi'
        ],
        'Nomor_Telepon' => [
            'required' => 'Nomor telepon harus diisi',
            'min_length' => 'Nomor telepon minimal 10 digit'
        ],
        'Jenis_Kelamin' => [
            'required' => 'Jenis kelamin harus dipilih'
        ],
        'Alamat' => [
            'required' => 'Alamat harus diisi'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getUserByUsername($username)
    {
        return $this->where('Username', $username)->first();
    }

    public function getUserById($id)
    {
        return $this->find($id);
    }

    public function createUser($data)
    {
        return $this->insert($data);
    }

    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }
}