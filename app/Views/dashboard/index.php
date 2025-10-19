<?= $this->extend('dashboard/layout') ?>

<?= $this->section('page_title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mdmv-dashboard-content">

    <?php if ($role == 'vendor' || $role == 'admin'): ?>
        <div class="mdmv-widget-card">
            <div class="card-header">
                <h3>Performa Toko (Contoh)</h3>
            </div>
            <div class="card-body stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Saldo Saat Ini</span>
                    <strong class="stat-value">Rp 0</strong>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Penghasilan</span>
                    <strong class="stat-value">Rp 0</strong>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Produk Terjual</span>
                    <strong class="stat-value">0</strong>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mdmv-widget-card">
        <div class="card-header">
            <h3>Selamat Datang, <?= esc($username) ?>!</h3>
        </div>
        <div class="card-body">
            <p>Anda login sebagai: <strong><?= esc(ucfirst($role)) ?></strong></p>
            
            <?php if ($role == 'vendor' && session()->get('application_status') == 'pending'): ?>
                <p style="color: blue;">
                    <strong>Status Akun:</strong> Aplikasi vendor Anda sedang ditinjau (pending).
                </p>
            <?php endif; ?>
        </div>
    </div>

</div>
<?= $this->endSection() ?>