<?= $this->extend('dashboard/layout') ?>

<?= $this->section('page_title') ?>
Buat Lisensi Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mdmv-add-product-page-wrapper shopee-style">
    <div class="page-header">
        <h3 class="page-title">Buat Kunci Lisensi Baru</h3>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mdmv-widget-card" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 15px;">
            <strong>Gagal menyimpan:</strong>
            <ul>
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="mdmv-widget-card">
        <div class="card-body">
            <?= form_open('dashboard/licenses/save') ?>
                <div class="form-row" style="margin-bottom: 15px;">
                    <label for="product_id">Produk *</label>
                    <select name="product_id" id="product_id" required style="width: 100%; padding: 10px;">
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach($products as $product): ?>
                            <option value="<?= $product->id ?>"><?= esc($product->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row" style="margin-bottom: 15px;">
                    <label for="user_id">Pengguna (Pembeli) *</label>
                    <select name="user_id" id="user_id" required style="width: 100%; padding: 10px;">
                        <option value="">-- Pilih Pengguna --</option>
                         <?php foreach($users as $user): ?>
                            <option value="<?= $user->id ?>"><?= esc($user->username) ?> (<?= esc($user->email) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row" style="margin-bottom: 15px;">
                    <label for="domain">Domain *</label>
                    <input type="text" name="domain" id="domain" required placeholder="example.com" style="width: 100%; padding: 10px;">
                </div>
                
                <div class="form-row" style="margin-bottom: 15px;">
                    <label for="expiry_date">Tanggal Kadaluwarsa *</label>
                    <input type="date" name="expiry_date" id="expiry_date" required style="width: 100%; padding: 10px;">
                </div>

                <div class="form-actions" style="margin-top: 20px;">
                    <button type="submit" class="button primary">Generate Kunci Lisensi</button>
                    <a href="<?= site_url('dashboard/licenses') ?>" class="button secondary">Batal</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>