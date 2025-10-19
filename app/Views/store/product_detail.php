<h2><?= esc($product->name) ?></h2>
<hr>
<div style="display: flex; gap: 20px;">
    <div style="flex: 1;">
        <p><?= esc($product->description) ?></p>
    </div>
    <div style="flex: 1; max-width: 300px;">
        <div class="product-card">
            <div class="price" style="font-size: 24px;">Rp <?= number_format($product->price, 0, ',', '.') ?></div>
            <hr>
            <?= form_open('cart/add') ?>
                <input type="hidden" name="product_id" value="<?= $product->id ?>">
                <input type="hidden" name="product_name" value="<?= esc($product->name) ?>">
                <input type="hidden" name="product_price" value="<?= $product->price ?>">
                <button type="submit" class="button primary" style="width: 100%; padding: 12px;">+ Tambah ke Keranjang</button>
            <?= form_close() ?>
        </div>
    </div>
</div>