<?= $this->extend('dashboard/layout') ?>

<?= $this->section('page_title') ?>
Produk Saya
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mdmv-products-page-wrapper shopee-style">

    <div class="page-header">
        <h3 class="page-title">Produk Saya</h3>
        <div class="header-actions">
            <a href="<?= site_url('dashboard/products/add') ?>" class="button primary">+ Tambah Produk Baru</a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mdmv-widget-card" style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 15px;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="mdmv-tabs-nav product-tabs">
        <a href="#" class="tab-item active" data-status="all">Semua</a>
        <a href="#" class="tab-item" data-status="publish">Live</a>
        <a href="#" class="tab-item" data-status="pending">Perlu Tindakan</a>
        <a href="#" class="tab-item" data-status="draft">Belum Ditampilkan</a>
    </div>

    <div class="mdmv-widget-card">
        <div class="card-body">
            <div class="product-list-header">
                <div class="col-product">Produk</div>
                <div class="col-price">Harga</div>
                <div class="col-stock">Stok</div>
                <div class="col-status">Status</div>
                <div class="col-actions">Aksi</div>
            </div>

            <div class="product-list-body">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-list-item" data-status="<?= esc($product->status) ?>" data-title="<?= esc(strtolower($product->name)) ?>">
                            <div class="col-product">
                                <div class="product-details">
                                    <span class="product-name"><?= esc($product->name) ?></span>
                                    <span class="product-sku">SKU: <?= esc($product->sku ?? '-') ?></span>
                                </div>
                            </div>
                            <div class="col-price">Rp <?= number_format($product->price, 0, ',', '.') ?></div>
                            <div class="col-stock"><?= esc($product->stock ?? '∞') ?></div>
                            <div class="col-status">
                                <span class="mdmv-status-label status-<?= esc($product->status) ?>"><?= esc(ucfirst($product->status)) ?></span>
                            </div>
                            <div class="col-actions">
                                <a href="#">Ubah</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products-found" style="padding: 20px; text-align: center;">
                        <p>Anda belum memiliki produk. <a href="<?= site_url('dashboard/products/add') ?>">Tambah produk baru sekarang!</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<style>
/* CSS Sederhana untuk Status */
.mdmv-status-label { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #fff; }
.status-publish { background-color: #28a745; }
.status-pending { background-color: #ffc107; color: #333; }
.status-draft { background-color: #6c757d; }
.col-product { flex: 4; display: flex; align-items: center; }
.col-price, .col-stock, .col-status { flex: 1; text-align: center; }
.col-actions { flex: 1; text-align: right; }
.product-details { margin-left: 15px; }
.product-name { font-weight: 600; color: #333; }
.product-sku { font-size: 12px; color: #888; }
.product-list-header { display: flex; align-items: center; padding: 15px 20px; background-color: #f9f9f9; font-weight: 600; color: #555; font-size: 14px; border-bottom: 1px solid #e0e0e0; }
.product-list-item { display: flex; align-items: center; padding: 15px 20px; border-bottom: 1px solid #f0f0f0; }
</style>
<?= $this->endSection() ?>