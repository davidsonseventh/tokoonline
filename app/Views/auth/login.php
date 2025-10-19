<div class="form-header">
    <h3>Log in</h3>
</div>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="login-error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?= form_open('login'); ?>
    <div class="form-row">
        <label for="email">Username atau Email</label>
        <input type="text" name="email" id="email" placeholder="Username atau Email" required>
    </div>
    <div class="form-row">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
    </div>
    <div class="form-row">
        <button type="submit">LOG IN</button>
    </div>
<?= form_close(); ?>

<div class="form-footer">
    <a class="forgot-password" href="#">Lupa Password</a>
    <p class="register-link">
        Baru di sini? <a href="<?= site_url('register') ?>">Daftar</a>
    </p>
</div>