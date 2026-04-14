<?php
ob_start();
?>

<div class="auth-card">
    <div class="auth-header">
        <h3 class="mb-0"><i class="bi bi-bank2"></i> BRI Lubuk Sikaping</h3>
        <p class="mb-0 mt-2">Sistem Pengajuan Kredit</p>
    </div>

    <div class="p-4">
        <h5 class="mb-4 text-center">Daftar Akun Baru</h5>

        <form action="<?= BASE_URL ?>auth/register" method="POST">
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>"
                       id="nama_lengkap" name="nama_lengkap" value="<?= $old['nama_lengkap'] ?? '' ?>" required autofocus>
                <?php if (isset($errors['nama_lengkap'])): ?>
                    <div class="invalid-feedback"><?= $errors['nama_lengkap'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       id="email" name="email" value="<?= $old['email'] ?? '' ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">No. Handphone</label>
                <input type="text" class="form-control <?= isset($errors['no_hp']) ? 'is-invalid' : '' ?>"
                       id="no_hp" name="no_hp" value="<?= $old['no_hp'] ?? '' ?>" required>
                <?php if (isset($errors['no_hp'])): ?>
                    <div class="invalid-feedback"><?= $errors['no_hp'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                       id="password" name="password" required>
                <div class="form-text">Minimal 6 karakter</div>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                       id="password_confirm" name="password_confirm" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <div class="invalid-feedback"><?= $errors['password_confirm'] ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-person-plus me-2"></i>Daftar
            </button>
        </form>

        <hr class="my-4">

        <p class="text-center mb-0">
            Sudah punya akun? <a href="<?= BASE_URL ?>auth/login"><strong>Login di sini</strong></a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Register - Sistem Pengajuan Kredit BRI';
require_once BASE_PATH . 'app/views/layouts/auth.php';
?>
