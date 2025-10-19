<?= $this->extend('dashboard/layout') ?>

<?= $this->section('page_title') ?>
License Manager
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mdmv-products-page-wrapper shopee-style">

    <div class="page-header">
        <h3 class="page-title">License Key Manager</h3>
        <div class="header-actions">
            <a href="<?= site_url('dashboard/licenses/add') ?>" class="button primary">+ Tambah Lisensi Baru</a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mdmv-widget-card" style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 15px;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="mdmv-widget-card">
        <div class="card-body">
            <table class="mdmv-variations-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Kunci Lisensi</th>
                        <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Domain</th>
                        <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Produk</th>
                        <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Pengguna</th>
                        <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Kadaluwarsa</th>
                        <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($licenses)): ?>
                        <?php foreach ($licenses as $license): ?>
                            <tr>
                                <td style="border: 1px solid #e0e0e0; padding: 8px;"><code><?= esc($license->license_key) ?></code></td>
                                <td style="border: 1px solid #e0e0e0; padding: 8px;"><?= esc($license->domain) ?></td>
                                <td style="border: 1px solid #e0e0e0; padding: 8px;"><?= esc($license->product->name ?? 'Produk Dihapus') ?></td>
                                <td style="border: 1px solid #e0e0e0; padding: 8px;"><?= esc($license->user->username ?? 'User Dihapus') ?></td>
                                <td style="border: 1px solid #e0e0e0; padding: 8px;"><?= esc($license->expiry_date) ?></td>
                                <td style="border: 1px solid #e0e0e0; padding: 8px;"><span class="mdmv-status-label status-<?= esc($license->status) ?>"><?= esc(ucfirst($license->status)) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center;">Belum ada kunci lisensi yang dibuat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>.mdmv-status-label.status-active { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; } .mdmv-status-label.status-inactive { background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; }</style>
<?= $this->endSection() ?>