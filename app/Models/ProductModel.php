<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    // Kolom yang boleh diisi dari controller
    protected $allowedFields    = [
        'vendor_id',
        'name',
        'description',
        'price',
        'type',
        'status',
        'digital_file_path',
        'use_admin_license',
        'sku', // Tambahkan SKU
    ];

    // Menggunakan timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Aturan Validasi (dasar)
    protected $validationRules = [
        'name'  => 'required|min_length[5]',
        'price' => 'permit_empty|decimal', // Harga tidak wajib untuk produk variabel
        'type'  => 'required|in_list[simple,variable]',
    ];
    
    // Kita juga perlu model untuk Atribut dan Variasi nanti
    // Untuk saat ini, kita fokus pada produk induk
}

// BUAT JUGA MODEL UNTUK ATRIBUT DAN VARIASI
// Kita akan membutuhkannya di Controller
// (Kita akan buat file-nya sekarang agar siap pakai)

/**
 * Model untuk Atribut (misal: Warna, Ukuran)
 */
class AttributeModel extends Model
{
    protected $table = 'product_attributes'; // Anda harus membuat tabel ini
    protected $allowedFields = ['product_id', 'name', 'options'];
    // Buat tabel `product_attributes` di SQL:
    // CREATE TABLE `product_attributes` (
    //   `id` INT AUTO_INCREMENT PRIMARY KEY,
    //   `product_id` INT NOT NULL,
    //   `name` VARCHAR(100) NOT NULL,
    //   `options` TEXT NOT NULL, -- Simpan sebagai JSON atau comma-separated
    //   FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    // );
}

/**
 * Model untuk Variasi (kombinasi atribut)
 */
class VariationModel extends Model
{
    protected $table = 'product_variations'; // Anda harus membuat tabel ini
    protected $allowedFields = ['product_id', 'attributes', 'price', 'stock', 'sku'];
    // Buat tabel `product_variations` di SQL:
    // CREATE TABLE `product_variations` (
    //   `id` INT AUTO_INCREMENT PRIMARY KEY,
    //   `product_id` INT NOT NULL,
    //   `attributes` TEXT NOT NULL, -- Simpan sebagai JSON (cth: {"Warna": "Merah", "Ukuran": "L"})
    //   `price` DECIMAL(15, 2) NOT NULL,
    //   `stock` INT NULL,
    //   `sku` VARCHAR(100) NULL,
    //   FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    // );
}