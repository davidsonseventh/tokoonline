<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('page_title', true) ?? 'Dashboard' ?> - Ezidcode</title>
    <style>
        /* CSS dari EziMarket/style.css untuk dashboard.php */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; background-color: #f5f5f5; color: #333; margin: 0; padding: 0; }
        a { color: #d00; text-decoration: none; }
        .mdmv-dashboard-wrapper.shopee-style { display: flex; font-family: Arial, sans-serif; background-color: #f5f5f5; min-height: 100vh; }
        .mdmv-dashboard-sidebar { width: 240px; background-color: #ffffff; border-right: 1px solid #e0e0e0; flex-shrink: 0; }
        .mdmv-dashboard-sidebar .sidebar-header { padding: 20px; border-bottom: 1px solid #e0e0e0; }
        .mdmv-dashboard-sidebar .sidebar-header h3 { margin: 0; font-size: 18px; color: #333; }
        .mdmv-sidebar-nav { padding: 10px 0; }
        .mdmv-sidebar-nav .mdmv-nav-item { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-size: 14px; transition: background-color 0.2s, color 0.2s; }
        .mdmv-sidebar-nav .mdmv-nav-item:hover { background-color: #f0f0f0; }
        .mdmv-sidebar-nav .mdmv-nav-item.active { background-color: #fff1f0; color: #d00; font-weight: bold; border-right: 3px solid #d00; }
        .mdmv-sidebar-nav .mdmv-nav-item .nav-icon { margin-right: 15px; font-size: 18px; }
        .mdmv-dashboard-main { flex-grow: 1; padding: 25px; overflow-y: auto; }
        .mdmv-main-header { margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .mdmv-main-header h2 { margin: 0; font-size: 24px; color: #333; }
        .mdmv-widget-card { background-color: #ffffff; border-radius: 8px; border: 1px solid #e0e0e0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .mdmv-widget-card .card-header { padding: 15px 20px; border-bottom: 1px solid #e0e0e0; }
        .mdmv-widget-card .card-header h3 { margin: 0; font-size: 16px; color: #333; }
        .mdmv-widget-card .card-body { padding: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; }
        .stat-item { display: flex; flex-direction: column; }
        .stat-label { font-size: 14px; color: #777; margin-bottom: 5px; }
        .stat-value { font-size: 22px; font-weight: bold; color: #333; }
        /* Tombol */
        .button { background-color: #6c757d; color: white; border: none; padding: 8px 15px; font-size: 14px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .button.primary { background-color: #d00; }
        .button:hover { opacity: 0.8; }
        /* Navigasi Atas Sederhana */
        .top-nav { background: #d00; color: white; padding: 10px 25px; display: flex; justify-content: flex-end; gap: 20px; }
        .top-nav span { font-weight: bold; }
        .top-nav a { color: white; }
    </style>
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <?= $this->renderSection('page_scripts') ?>
</head>
	
</head>
<body>

    <div class="top-nav">
        <span>Halo, <?= esc(session()->get('username')) ?>!</span>
        <a href="<?= site_url('logout') ?>">Logout</a>
    </div>

    <div class="mdmv-dashboard-wrapper shopee-style">
        <aside class="mdmv-dashboard-sidebar">
            <div class="sidebar-header">
                <h3>Toko Saya</h3>
            </div>
            <nav class="mdmv-sidebar-nav">
                <?php $role = session()->get('role'); ?>

                <a href="<?= site_url('dashboard') ?>" class="mdmv-nav-item <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                
                <?php if ($role == 'admin' || $role == 'vendor'): ?>
                    <a href="<?= site_url('dashboard/products') ?>" class="mdmv-nav-item <?= (str_contains(uri_string(), 'dashboard/products')) ? 'active' : '' ?>">
                        <span class="nav-icon">📦</span>
                        <span class="nav-label">Produk Saya</span>
                    </a>
                    <a href="<?= site_url('dashboard/products/add') ?>" class="mdmv-nav-item <?= (str_contains(uri_string(), 'dashboard/products/add')) ? 'active' : '' ?>">
                        <span class="nav-icon">➕</span>
                        <span class="nav-label">Tambah Produk Baru</span>
                    </a>
                <?php endif; ?>
                
                <?php if ($role == 'admin'): ?>
                    <a href="#" class="mdmv-nav-item">
                        <span class="nav-icon">🔑</span>
                        <span class="nav-label">License Manager</span>
                    </a>
                    <a href="#" class="mdmv-nav-item">
                        <span class="nav-icon">👥</span>
                        <span class="nav-label">User Applications</span>
                    </a>
                <?php endif; ?>

                <?php if ($role == 'vendor'): ?>
                    <a href="#" class="mdmv-nav-item">
                        <span class="nav-icon">💰</span>
                        <span class="nav-label">Penghasilan</span>
                    </a>
                <?php endif; ?>

                <a href="<?= site_url('dashboard/settings') ?>" class="mdmv-nav-item <?= (str_contains(uri_string(), 'dashboard/settings')) ? 'active' : '' ?>">
                    <span class="nav-icon">⚙️</span>
                    <span class="nav-label">Pengaturan</span>
                </a>
            </nav>
        </aside>

        <main class="mdmv-dashboard-main">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

</body>
</html>