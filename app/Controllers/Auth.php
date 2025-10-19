<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        // Inisialisasi session
        $this->session = \Config\Services::session();
    }

    /**
     * Menampilkan halaman login
     */
    public function login()
    {
        // Jika sudah login, lempar ke dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('dashboard'); // (Nanti kita buat halaman dashboard)
        }
        
        return view('auth/header')
             . view('auth/login')
             . view('auth/footer');
    }

    /**
     * Memproses upaya login
     */
    public function attemptLogin()
    {
        $userModel = new UserModel();
        $emailOrUsername = $this->request->getPost('email'); // Form login akan pakai field 'email'
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email atau username
        $user = $userModel->where('email', $emailOrUsername)
                          ->orWhere('username', $emailOrUsername)
                          ->first();

        // Cek user dan password
        if ($user && password_verify($password, $user->password)) {
            // Set session data
            $this->session->set([
                'user_id'    => $user->id,
                'username'   => $user->username,
                'email'      => $user->email,
                'role'       => $user->role,
                'isLoggedIn' => true,
            ]);
            
            // Arahkan ke dashboard (nanti kita buat)
            return redirect()->to('dashboard');
        } else {
            // Jika gagal, kembali ke login dengan pesan error
            $this->session->setFlashdata('error', 'Username atau password salah.');
            return redirect()->to('login');
        }
    }

    /**
     * Menampilkan halaman registrasi
     */
    public function register()
    {
        // Jika sudah login, lempar ke dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }

        return view('auth/header')
             . view('auth/register')
             . view('auth/footer');
    }

    /**
     * Memproses upaya registrasi
     */
    public function attemptRegister()
    {
        $userModel = new UserModel();

        // Ambil data dari form
        $data = [
            'full_name'  => $this->request->getPost('full_name'),
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'role'       => $this->request->getPost('role'), // 'customer', 'vendor', 'reseller'
            'phone_number' => $this->request->getPost('phone_number'),
            'store_name' => $this->request->getPost('role') === 'vendor' ? $this->request->getPost('store_name') : null,
            'application_status' => in_array($this->request->getPost('role'), ['vendor', 'reseller']) ? 'pending' : null,
        ];

        // Validasi password confirm
        if ($data['password'] !== $this->request->getPost('password_confirm')) {
            $this->session->setFlashdata('error', 'Password dan Konfirmasi Password tidak cocok.');
            return redirect()->to('register')->withInput();
        }

        // Coba simpan data menggunakan model (sudah termasuk validasi dan hash password)
        if ($userModel->save($data)) {
            // Jika berhasil, login-kan user dan arahkan ke dashboard
            $user_id = $userModel->getInsertID();
            $this->session->set([
                'user_id'    => $user_id,
                'username'   => $data['username'],
                'email'      => $data['email'],
                'role'       => $data['role'],
                'isLoggedIn' => true,
            ]);
            return redirect()->to('dashboard'); // (Nanti kita buat halaman dashboard)
        } else {
            // Jika validasi gagal, kembali ke form registrasi
            $this->session->setFlashdata('errors', $userModel->errors());
            return redirect()->to('register')->withInput();
        }
    }

    /**
     * Proses Logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('login');
    }
}