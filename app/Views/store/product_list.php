<h2>Produk Kami</h2>
<div class="product-grid">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= esc($product->name) ?></h3>
                <div class="price">Rp <?= number_format($product->price, 0, ',', '.') ?></div>
                <a href="<?= site_url('product/' . $product->id) ?>" class="button" style="margin-top: 10px;">Lihat Detail</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Belum ada produk yang tersedia.</p>
    <?php endif; ?>
</div>