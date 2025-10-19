<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Ezidcode</title>
    <style>
        /* CSS Dasar untuk Tampilan Toko */
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .top-nav { background: #d00; padding: 10px 20px; text-align: right; }
        .top-nav a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { border: 1px solid #eee; border-radius: 5px; overflow: hidden; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        .product-card-content { padding: 15px; }
        .product-card h3 { font-size: 16px; margin: 10px 0; min-height: 40px; }
        .product-card .price { color: #d00; font-weight: bold; font-size: 1.1em; }
        .button { background-color: #6c757d; color: white; border: none; padding: 8px 15px; font-size: 14px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; transition: opacity 0.2s; }
        .button.primary { background-color: #d00; }
        .button:hover { opacity: 0.8; }
    </style>
</head>
<body>
<div class="top-nav">
    
    <?php
    // Ambil instance session service
    $session = \Config\Services::session(); 
    ?>
    
    <a href="<?= site_url('cart') ?>">Keranjang (<?= \Config\Services::cart()->totalItems() ?>)</a>
    
    <?php if ($session->get('isLoggedIn')): ?>
        <a href="<?= site_url('dashboard') ?>">Dashboard Saya</a>
        <a href="<?= site_url('logout') ?>">Logout</a>
    <?php else: ?>
        <a href="<?= site_url('login') ?>">Login</a>
    <?php endif; ?>

</div>
<div class="container">