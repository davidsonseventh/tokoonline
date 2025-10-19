<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenseModel extends Model
{
    protected $table            = 'license_keys';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    
    protected $allowedFields    = [
        'license_key', 'product_id', 'order_id', 'user_id', 
        'vendor_id', 'domain', 'expiry_date', 'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // Tidak ada updated_at di tabel Anda

    /**
     * Relasi: Ambil data produk
     */
    public function getProduct($license)
    {
        $productModel = new ProductModel();
        return $productModel->find($license->product_id);
    }

    /**
     * Relasi: Ambil data user (pembeli)
     */
    public function getUser($license)
    {
        $userModel = new UserModel();
        return $userModel->find($license->user_id);
    }
}