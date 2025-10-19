<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/*
 * ====================================================================
 * TAMBAHKAN SEMUA KODE DI BAWAH INI
 * ====================================================================
 */

// Rute untuk Autentikasi
$routes->get('login', 'Auth::login');       // Menampilkan halaman login
$routes->post('login', 'Auth::attemptLogin'); // Memproses form login
$routes->get('register', 'Auth::register');    // Menampilkan halaman registrasi
$routes->post('register', 'Auth::attemptRegister'); // Memproses form registrasi
$routes->get('logout', 'Auth::logout');      // Proses logout








/*
 * ====================================================================
 * GRUP RUTE DASHBOARD (Perbaikan untuk Tahap 3, 4, & 5)
 * ====================================================================
 */
// Grup ini dilindungi oleh 'auth' (harus login)
$routes->group('dashboard', ['filter' => 'auth'], static function ($routes) {

    // Rute Dashboard Utama
    $routes->get('/', 'Dashboard::index');

    // Rute Produk (Tahap 4)
    $routes->get('products', 'Dashboard::products');
    $routes->get('products/add', 'Dashboard::addProduct');
    $routes->post('products/save', 'Dashboard::saveProduct');

    // Rute Pengaturan
    $routes->get('settings', 'Dashboard::settings');

    // Rute License Manager (Hanya bisa diakses oleh admin)
    // Dilindungi oleh 'auth' DAN 'admin'
    $routes->group('licenses', ['filter' => 'admin'], static function ($routes) {
        $routes->get('/', 'License::index');
        $routes->get('add', 'License::add');
        $routes->post('save', 'License::save');
    });

});


/*
 * ====================================================================
 * TAMBAHAN BARU UNTUK TAHAP 6 (Rute Publik)
 * ====================================================================
 */
$routes->get('store', 'Store::index'); // Halaman toko
$routes->get('product/(:num)', 'Store::product/$1'); // Halaman detail produk

$routes->get('cart', 'Cart::index'); // Melihat keranjang
$routes->post('cart/add', 'Cart::add'); // Menambah ke keranjang
$routes->get('cart/remove/(:any)', 'Cart::remove/$1'); // Menghapus item

$routes->get('checkout', 'Checkout::index'); // Halaman checkout
$routes->post('checkout/placeorder', 'Checkout::placeOrder'); // Proses order

// Rute "ajaib" untuk Callback Tripay
// Ini harus DILUAR filter 'auth' dan 'csrf'
$routes->post('checkout', 'Callback::handle', ['filter' => '']);