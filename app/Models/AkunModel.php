<?php

namespace App\Models;
use CodeIgniter\Model;

class AkunModel extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'user_id',
        'Email', 
        'Password',
         'Role'
    ];

    // Validation rules
    protected $validationRules = [
        'user_id' => 'required|integer|is_unique[akun.user_id]',
        'Email' => 'required|valid_email|max_length[255]|is_unique[akun.Email]',
        'Password' => 'required|min_length[6]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID harus ada',
            'is_unique' => 'User sudah memiliki akun'
        ],
        'Email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'Password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Mengambil data akun berdasarkan email
    public function getAkunByEmail($email)
    {
        return $this->where('Email', $email)->first();
    }

    // Mengambil data akun berdasarkan username dengan join ke tabel user
    public function getAkunByUsername($username)
    {
        return $this->join('user', 'user.id = akun.user_id')
                    ->where('user.Username', $username)
                    ->select('akun.*, user.Username')
                    ->first();
    }

    // Membuat akun baru
    public function createAkun($data)
    {
        return $this->insert($data);
    }

    // Update password berdasarkan user_id
    public function updatePassword($userId, $newPassword)
    {
        return $this->where('user_id', $userId)
                    ->set(['Password' => $newPassword])
                    ->update();
    }
}
