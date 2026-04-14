<?php
ob_start();
?>

<div class="auth-card">
    <div class="auth-header text-center"> <div class="mb-3">
            <img src="<?= BASE_URL ?>assets/img/BRI_logo.png" alt="Logo BRI" style="max-height: 50px; width: auto; background: white; padding: 4px; border-radius: 8px;">
        </div>
        
        <h3 class="mb-0">BRI Lubuk Sikaping</h3>
        <p class="mb-0 mt-2">Sistem Pengajuan Kredit</p>
    </div>

    <div class="p-4">
        <h5 class="mb-4 text-center">Masuk ke Akun Anda</h5>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>auth/login" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                           id="email" name="email" value="<?= $email ?? '' ?>" required autofocus>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                           id="password" name="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <hr class="my-4">

        <p class="text-center mb-0">
            Belum punya akun? <a href="<?= BASE_URL ?>auth/register"><strong>Daftar Sekarang</strong></a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Login - Sistem Pengajuan Kredit BRI';
require_once BASE_PATH . 'app/views/layouts/auth.php';
?>