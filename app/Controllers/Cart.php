<?php

namespace App\Controllers;

class Cart extends BaseController
{
    protected $helpers = ['form', 'url']; // Helper 'cart' tidak diperlukan lagi
    protected $cart; // Tambahkan properti ini

    public function __construct()
    {
        // Muat service keranjang menggunakan sintaks CI4 yang benar
        $this->cart = \Config\Services::cart();
    }

    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();

        return view('store/header')
             . view('store/cart', $data)
             . view('store/footer');
    }

    public function add()
    {
        $data = [
            'id'      => $this->request->getPost('product_id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('product_price'),
            'name'    => $this->request->getPost('product_name'),
            'options' => []
        ];

        $this->cart->insert($data);
        return redirect()->back();
    }

    public function remove($rowid)
    {
        $this->cart->remove($rowid);
        return redirect()->to('cart');
    }
}