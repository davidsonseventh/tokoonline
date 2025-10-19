<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object'; // Kita akan gunakan object, mirip WordPress
    protected $allowedFields    = [
        'username', 'email', 'password', 'full_name', 'role', 
        'phone_number', 'store_name', 'application_status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username'  => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
        'email'     => 'required|valid_email|is_unique[users.email]',
        'password'  => 'required|min_length[8]',
    ];
    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Maaf, username tersebut sudah digunakan.',
        ],
        'email' => [
            'is_unique' => 'Maaf, email tersebut sudah terdaftar.',
        ],
    ];

    // Callbacks
    protected $beforeInsert = ['hashPassword'];

    /**
     * Otomatis hash password sebelum disimpan ke database
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}