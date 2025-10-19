<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Store extends BaseController
{
    protected $helpers = ['form', 'url'];

    // Halaman utama toko (menampilkan semua produk)
    public function index()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->where('status', 'publish')->findAll();

        return view('store/header')
             . view('store/product_list', $data)
             . view('store/footer');
    }

    // Halaman detail produk
    public function product($id)
    {
        $productModel = new ProductModel();
        $data['product'] = $productModel->find($id);

        if (empty($data['product']) || $data['product']->status !== 'publish') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('store/header')
             . view('store/product_detail', $data)
             . view('store/footer');
    }
}