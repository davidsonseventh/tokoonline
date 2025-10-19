<?= $this->extend('dashboard/layout') ?>

<?= $this->section('page_title') ?>
Tambah Produk Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mdmv-add-product-page-wrapper shopee-style">
    
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

    <?= form_open('dashboard/products/save') ?>
        <div class="form-main-content">
            <div class="form-fields-section">

                <div class="mdmv-form-card">
                    <div class="card-header"><h3>Informasi Produk</h3></div>
                    <div class="card-body">
                        <div class="form-row">
                            <label for="product_title">Nama Produk <span class="required">*</span></label>
                            <input type="text" id="product_title" name="product_title" value="<?= old('product_title') ?>" required placeholder="Masukkan nama produk">
                        </div>
                        <div class="form-row">
                            <label for="product_description">Deskripsi Produk</label>
                            <textarea id="product_description" name="product_description" rows="8" placeholder="Masukkan deskripsi lengkap produk Anda"><?= old('product_description') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="mdmv-form-card">
                    <div class="card-header"><h3>Informasi Penjualan</h3></div>
                    <div class="card-body">
                        
                        <div class="form-row">
                            <label for="product_type">Tipe Produk</label>
                            <select id="product_type" name="product_type">
                                <option value="simple">Produk Simple</option>
                                <option value="variable">Produk Bervariasi</option>
                            </select>
                        </div>

                        <div class="form-row" id="mdmv-main-price-row">
                            <label for="product_price">Harga (Rp) <span class="required">*</span></label>
                            <input type="number" id="product_price" name="product_price" value="<?= old('product_price') ?>" step="any" min="0" required placeholder="Masukkan harga">
                        </div>

                        <div class="mdmv-form-card" id="mdmv-variations-card" style="display: none; border: 1px dashed #d00; margin-top: 15px;">
                            <div class="card-header" style="background: #fff1f0;">
                                <h3>Variasi Produk</h3>
                            </div>
                            <div class="card-body">
                                <div id="mdmv-variation-options-wrapper">
                                    </div>
                                <button type="button" id="mdmv-add-variation-option" class="button secondary">+ Tambah Opsi Variasi (e.g., Warna)</button>
                                <hr style="margin: 20px 0;">
                                <div id="mdmv-variations-table-wrapper" style="display: none;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                        <h4>Daftar Variasi</h4>
                                        <button type="button" id="mdmv-generate-variations" class="button primary">Buat Kombinasi Variasi</button>
                                    </div>
                                    <table class="mdmv-variations-table" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Variasi</th>
                                                <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Harga <span class="required">*</span></th>
                                                <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">Stok</th>
                                                <th style="border: 1px solid #e0e0e0; padding: 12px; background-color: #f9f9f9;">SKU</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mdmv-variations-list">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                </div>
        </div>

        <div class="form-actions-footer">
            <a href="<?= site_url('dashboard/products') ?>" class="button secondary">Kembali</a>
            <input type="submit" name="submit_product" class="button primary" value="Simpan Produk">
        </div>
    <?= form_close() ?>
</div>

<style>
/* Styling minimal untuk tabel variasi */
.variation-option-group { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.variation-option-group input { flex: 1; }
.mdmv-variations-table td { border: 1px solid #e0e0e0; padding: 8px; vertical-align: middle; }
.mdmv-variations-table input { width: 100%; }
</style>
<?= $this->endSection() ?>


<?= $this->section('page_scripts') ?>
<script>
jQuery(document).ready(function($) {
    // Fungsi untuk menampilkan/menyembunyikan bagian variasi dan harga utama
    function toggleVariationSection() {
        var productType = $('#product_type').val();
        var variationsCard = $('#mdmv-variations-card');
        var mainPriceRow = $('#mdmv-main-price-row');
        var mainPriceInput = mainPriceRow.find('input');

        if (productType === 'variable') {
            variationsCard.slideDown();
            mainPriceRow.slideUp();
            mainPriceInput.prop('required', false);
        } else {
            variationsCard.slideUp();
            mainPriceRow.slideDown();
            mainPriceInput.prop('required', true);
        }
    }

    // Jalankan fungsi saat tipe produk diubah atau saat halaman dimuat
    $('#product_type').on('change', toggleVariationSection).trigger('change');

    // Tambah opsi variasi (misal: Warna, Ukuran)
    $('#mdmv-add-variation-option').on('click', function() {
        var optionIndex = $('#mdmv-variation-options-wrapper .variation-option-group').length;
        var newOption = `
            <div class="variation-option-group form-row-flex" style="display: flex; gap: 10px; margin-bottom: 10px;">
                <input type="text" name="variation_options[${optionIndex}][name]" class="variation-option-name" placeholder="Nama Opsi (e.g., Ukuran)" style="flex: 1;">
                <input type="text" name="variation_options[${optionIndex}][values]" class="variation-option-values" placeholder="Pilihan (pisahkan koma, e.g., 3ml, 15ml)" style="flex: 2;">
                <button type="button" class="button secondary remove-variation-option">Hapus</button>
            </div>`;
        $('#mdmv-variation-options-wrapper').append(newOption);
        $('#mdmv-variations-table-wrapper').slideDown();
    });

    // Hapus opsi variasi
    $('#mdmv-variation-options-wrapper').on('click', '.remove-variation-option', function() {
        $(this).closest('.variation-option-group').remove();
        if ($('#mdmv-variation-options-wrapper .variation-option-group').length === 0) {
            $('#mdmv-variations-table-wrapper').slideUp();
            $('#mdmv-variations-list').empty();
        }
    });

    // Buat kombinasi variasi
    $('#mdmv-generate-variations').on('click', function() {
        var options = [];
        $('.variation-option-group').each(function() {
            var name = $(this).find('.variation-option-name').val().trim();
            var values = $(this).find('.variation-option-values').val().split(',').map(v => v.trim()).filter(Boolean);
            if (name && values.length > 0) {
                options.push({ name: name, values: values });
            }
        });

        if (options.length === 0) {
            alert('Silakan tambahkan minimal satu opsi dan pilihan variasi.');
            return;
        }

        var combinations = (args) => {
            var r = [], max = args.length - 1;
            function helper(arr, i) {
                for (var j = 0, l = args[i].values.length; j < l; j++) {
                    var a = arr.slice(0);
                    a.push({ name: args[i].name, value: args[i].values[j] });
                    if (i == max) r.push(a);
                    else helper(a, i + 1);
                }
            }
            helper([], 0);
            return r;
        };

        var allCombinations = combinations(options);
        $('#mdmv-variations-list').empty();

        allCombinations.forEach((combo, index) => {
            let comboName = combo.map(c => c.value).join(' / ');
            let attributesHtml = '';
            combo.forEach(attr => {
                 attributesHtml += `<input type="hidden" name="variations[${index}][attributes][${attr.name}]" value="${attr.value}">`;
            });

            var newRow = `
                <tr>
                    <td>${comboName} ${attributesHtml}</td>
                    <td><input type="number" class="wc_input_price" name="variations[${index}][price]" placeholder="Harga" required></td>
                    <td><input type="number" name="variations[${index}][stock]" placeholder="Stok"></td>
                    <td><input type="text" name="variations[${index}][sku]" placeholder="SKU"></td>
                </tr>`;
            $('#mdmv-variations-list').append(newRow);
        });
    });
});
</script>
<?= $this->endSection() ?>