<?php

namespace App\Controllers;

use App\Models\LicenseModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class License extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'license']); // Muat helper baru kita
    }

    /**
     * Menampilkan daftar lisensi (Replikasi class-license-keys-list-table.php)
     */
    public function index()
    {
        $licenseModel = new LicenseModel();
        $data['licenses'] = $licenseModel->orderBy('created_at', 'DESC')->findAll();

        // Ambil relasi untuk setiap lisensi (untuk ditampilkan di tabel)
        foreach ($data['licenses'] as $key => $license) {
            $data['licenses'][$key]->product = $licenseModel->getProduct($license);
            $data['licenses'][$key]->user = $licenseModel->getUser($license);
        }

        return view('dashboard/licenses', $data);
    }

    /**
     * Menampilkan form tambah lisensi (Replikasi add-license-form.php)
     */
    public function add()
    {
        $productModel = new ProductModel();
        $userModel = new UserModel();

        $data['products'] = $productModel->findAll();
        $data['users'] = $userModel->whereIn('role', ['customer', 'vendor', 'reseller'])->findAll();

        return view('dashboard/add_license', $data);
    }

    /**
     * Menyimpan lisensi baru (Replikasi handle_form_actions)
     */
    public function save()
    {
        $licenseModel = new LicenseModel();
        $productModel = new ProductModel();

        // 1. Ambil data dari form
        $product_id  = $this->request->getPost('product_id');
        $user_id     = $this->request->getPost('user_id');
        $domain      = $this->request->getPost('domain');
        $expiry_date = $this->request->getPost('expiry_date');

        // 2. Ambil data vendor_id dari produk
        $product = $productModel->find($product_id);
        if (!$product) {
            $this->session->setFlashdata('error', 'Produk tidak ditemukan.');
            return redirect()->back()->withInput();
        }
        $vendor_id = $product->vendor_id; // Ambil ID vendor/admin pembuat produk

        // 3. Panggil Helper Generator Lisensi kita!
        $license_key = generate_license_key($domain, $expiry_date, $product_id);

        // 4. Simpan ke database
        $data = [
            'license_key' => $license_key,
            'product_id'  => $product_id,
            'user_id'     => $user_id,
            'vendor_id'   => $vendor_id,
            'domain'      => $domain,
            'expiry_date' => $expiry_date,
            'status'      => 'active',
        ];

        if ($licenseModel->save($data)) {
            $this->session->setFlashdata('success', 'Kunci lisensi berhasil dibuat.');
            return redirect()->to('dashboard/licenses');
        } else {
            $this->session->setFlashdata('errors', $licenseModel->errors());
            return redirect()->back()->withInput();
        }
    }
}