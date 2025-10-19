<div class="form-header">
    <h3>Daftar</h3>
</div>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="login-error validation-errors">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="login-error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>


<?= form_open('register'); ?>
    <div class="form-row">
        <label for="mdmv_role">Daftar Sebagai *</label>
        <select name="role" id="mdmv_role" required>
            <option value="customer" <?= set_select('role', 'customer', true) ?>>Pengguna Biasa (Pembeli)</option>
            <option value="vendor" <?= set_select('role', 'vendor') ?>>Vendor (Penjual)</option>
            <option value="reseller" <?= set_select('role', 'reseller') ?>>Reseller</option>
        </select>
    </div>
    <div class="form-row" id="store_name_field" style="display: none;">
        <label for="store_name">Nama Toko *</label>
        <input type="text" name="store_name" id="store_name" value="<?= set_value('store_name') ?>" placeholder="Nama Toko Anda">
    </div>
    <div class="form-row">
        <label for="full_name">Nama Lengkap *</label>
        <input type="text" name="full_name" id="full_name" value="<?= set_value('full_name') ?>" placeholder="Nama Lengkap Anda" required>
    </div>
    <div class="form-row">
        <label for="username">Username *</label>
        <input type="text" name="username" id="username" value="<?= set_value('username') ?>" placeholder="Username" required>
    </div>
    <div class="form-row">
        <label for="email">Email *</label>
        <input type="email" name="email" id="email" value="<?= set_value('email') ?>" placeholder="Email" required>
    </div>
    <div class="form-row">
        <label for="phone_number">Nomor HP (WhatsApp) *</label>
        <input type="text" name="phone_number" id="phone_number" value="<?= set_value('phone_number') ?>" placeholder="Nomor WhatsApp" required>
    </div>
    <div class="form-row">
        <label for="password">Password *</label>
        <input type="password" name="password" id="password" placeholder="Password (min. 8 karakter)" required>
    </div>
    <div class="form-row">
        <label for="password_confirm">Konfirmasi Password *</label>
        <input type="password" name="password_confirm" id="password_confirm" placeholder="Ketik ulang password" required>
    </div>
    <div class="form-row">
        <button type="submit">Daftar Sekarang</button>
    </div>
<?= form_close(); ?>

<div class="form-footer">
    <p class="register-link">
        Sudah punya akun? <a href="<?= site_url('login') ?>">Login</a>
    </p>
</div>