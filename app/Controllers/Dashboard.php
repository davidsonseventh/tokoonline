<?php

namespace App\Controllers;

// Impor semua model yang kita butuhkan
use App\Models\ProductModel;
use App\Models\AttributeModel;
use App\Models\VariationModel;

class Dashboard extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']); // Muat helper form
    }

    /**
     * Menampilkan halaman dashboard utama
     */
    public function index()
    {
        $data = [
            'username' => $this->session->get('username'),
            'role'     => $this->session->get('role')
        ];
        
        return view('dashboard/index', $data);
    }

    /**
     * Menampilkan halaman "Produk Saya"
     */
    public function products() {
        $productModel = new ProductModel();
        $data['products'] = $productModel
            ->where('vendor_id', $this->session->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();
            
        return view('dashboard/products', $data);
    }

    /**
     * Menampilkan halaman "Tambah Produk"
     */
    public function addProduct()
    {
        return view('dashboard/add_product');
    }

    /**
     * Menyimpan produk baru (Simple atau Variable)
     * Ini adalah replikasi dari class-product-manager.php
     */
    public function saveProduct()
    {
        $productType = $this->request->getPost('product_type');
        $vendor_id = $this->session->get('user_id');
        $productName = $this->request->getPost('product_title');

        if (empty($productName)) {
             $this->session->setFlashdata('errors', ['Nama produk wajib diisi.']);
             return redirect()->back()->withInput();
        }

        if ($productType === 'simple') {
            return $this->createSimpleProduct($vendor_id);
        } elseif ($productType === 'variable') {
            return $this->createVariableProduct($vendor_id);
        }
    }

    /**
     * Logika untuk menyimpan produk simple
     */
    private function createSimpleProduct($vendor_id)
    {
        $productModel = new ProductModel();
        
        $data = [
            'vendor_id'   => $vendor_id,
            'name'        => $this->request->getPost('product_title'),
            'description' => $this->request->getPost('product_description'),
            'price'       => $this->request->getPost('product_price'),
            'status'      => 'pending', // Wajib pending untuk approval admin
            'type'        => 'simple',
        ];

        if ($productModel->save($data)) {
            $this->session->setFlashdata('success', 'Produk simple berhasil ditambahkan dan sedang menunggu persetujuan.');
            return redirect()->to('dashboard/products');
        } else {
            $this->session->setFlashdata('errors', $productModel->errors());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Logika untuk menyimpan produk variable (dari vendor-variations.js)
     */
    private function createVariableProduct($vendor_id)
    {
        $productModel = new ProductModel();
        $attributeModel = new AttributeModel();
        $variationModel = new VariationModel();

        // 1. Simpan Produk Induk (Variable)
        $productData = [
            'vendor_id'   => $vendor_id,
            'name'        => $this->request->getPost('product_title'),
            'description' => $this->request->getPost('product_description'),
            'price'       => 0, // Harga diatur di variasi
            'status'      => 'pending',
            'type'        => 'variable',
        ];

        $product_id = $productModel->insert($productData);

        if (!$product_id) {
            $this->session->setFlashdata('errors', $productModel->errors());
            return redirect()->back()->withInput();
        }

        // 2. Simpan Atribut (Warna, Ukuran, dll)
        $variationOptions = $this->request->getPost('variation_options');
        if (!empty($variationOptions)) {
            foreach ($variationOptions as $option) {
                if (empty($option['name']) || empty($option['values'])) continue;
                
                $attributeData = [
                    'product_id' => $product_id,
                    'name'       => $option['name'],
                    'options'    => $option['values'], // Simpan sebagai 'S,M,L'
                ];
                $attributeModel->save($attributeData);
            }
        }

        // 3. Simpan Variasi (Kombinasi)
        $variations = $this->request->getPost('variations');
        if (!empty($variations)) {
            foreach ($variations as $var_data) {
                $variationData = [
                    'product_id' => $product_id,
                    'attributes' => json_encode($var_data['attributes']), // Simpan sebagai JSON {"Warna":"Merah"}
                    'price'      => $var_data['price'],
                    'stock'      => !empty($var_data['stock']) ? $var_data['stock'] : null,
                    'sku'        => $var_data['sku'],
                ];
                $variationModel->save($variationData);
            }
        }
        
        $this->session->setFlashdata('success', 'Produk bervariasi berhasil ditambahkan dan sedang menunggu persetujuan.');
        return redirect()->to('dashboard/products');
    }
    
    public function settings() {
        return view('dashboard/settings');
    }
}