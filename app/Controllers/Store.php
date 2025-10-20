<?php

namespace App\Controllers;

use App\Models\ProductModel; // 'use' statement (Benar)

class Store extends BaseController
{
	protected $productModel; // Deklarasi properti (Benar)
    protected $helpers = ['form', 'url'];
	
	
	public function __construct() // Constructor (Benar)
    {
        $this->productModel = new ProductModel();
    }

    // Halaman utama toko (menampilkan semua produk)
    public function index()
    {
        $data = [
            // PERBAIKAN: Menggunakan $this->productModel dan query yang benar
            'products' => $this->productModel->where('status', 'publish')->findAll() 
        ];

        return view('store/header')
             . view('store/product_list', $data)
             . view('store/footer');
    }

    // Halaman detail produk
    public function product($id)
    {
        // 1. Ambil data dari model
        $productData = $this->productModel->find($id);

        // 2. PERBAIKAN: Paksa data menjadi object
        // Ini untuk menangani jika model karena suatu alasan mengembalikan array
        $data['product'] = is_array($productData) ? (object)$productData : $productData;

        // 3. Cek di baris 47 sekarang akan aman
        if (empty($data['product']) || $data['product']->status !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 4. Kirim ke view (View Anda akan 100% menerima object)
        return view('store/header')
             . view('store/product_detail', $data)
             . view('store/footer');
    }
}
