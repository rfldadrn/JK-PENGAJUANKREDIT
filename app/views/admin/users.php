<?php
ob_start();
?>

<!-- Users Management -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen User</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="bi bi-plus-circle"></i> Tambah User
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['users'])): ?>
                            <?php foreach ($data['users'] as $user): ?>
                            <tr>
                                <td><?= $user['id_user'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($user['nama_lengkap']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['no_hp']) ?></td>
                                <td>
                                    <?php
                                    $roleBadges = [
                                        'admin' => 'bg-danger',
                                        'pimpinan' => 'bg-primary',
                                        'analis' => 'bg-info',
                                        'petugas' => 'bg-warning',
                                        'nasabah' => 'bg-secondary'
                                    ];
                                    $badgeClass = $roleBadges[$user['role']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($user['role']) ?></span>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                                        <select name="status_akun" class="form-select form-select-sm"
                                                onchange="this.form.submit()"
                                                style="width: auto;">
                                            <option value="aktif" <?= $user['status_akun'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="nonaktif" <?= $user['status_akun'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <span class="text-muted small">ID: <?= $user['id_user'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    Belum ada user
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                        <small class="text-muted">Min. 6 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="pimpinan">Pimpinan</option>
                            <option value="analis">Analis</option>
                            <option value="petugas">Petugas (AO)</option>
                            <option value="nasabah">Nasabah</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Manajemen User';
$pageSubtitle = 'Kelola user dan akses sistem';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
