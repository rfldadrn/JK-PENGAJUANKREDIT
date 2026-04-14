<?php
ob_start();
?>

<!-- Produk Kredit Management -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen Produk Kredit</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <div class="row g-4">
        <?php if (!empty($data['produkKredit'])): ?>
            <?php foreach ($data['produkKredit'] as $produk): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($produk['nama_kredit']) ?></h5>
                                <span class="badge bg-secondary"><?= htmlspecialchars($produk['kode_kredit']) ?></span>
                            </div>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id_jenis_kredit" value="<?= $produk['id_jenis_kredit'] ?>">
                                <select name="status" class="form-select form-select-sm"
                                        onchange="this.form.submit()"
                                        style="width: auto;">
                                    <option value="aktif" <?= $produk['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="nonaktif" <?= $produk['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </form>
                        </div>

                        <p class="text-muted small mb-3"><?= htmlspecialchars($produk['deskripsi']) ?></p>

                        <div class="border-top pt-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Plafond</small>
                                    <strong class="small">
                                        Rp <?= number_format($produk['plafond_min'], 0, ',', '.') ?>
                                        <br>
                                        - Rp <?= number_format($produk['plafond_max'], 0, ',', '.') ?>
                                    </strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Bunga/Tahun</small>
                                    <strong class="small"><?= $produk['bunga_per_tahun'] ?>%</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Tenor</small>
                                    <strong class="small">
                                        <?= $produk['tenor_min'] ?> - <?= $produk['tenor_max'] ?> bulan
                                    </strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge <?= $produk['status'] === 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($produk['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php
                        $syarat = json_decode($produk['syarat_dokumen'], true) ?? [];
                        if (!empty($syarat)):
                        ?>
                        <div class="border-top mt-3 pt-3">
                            <small class="text-muted d-block mb-2">Syarat Dokumen:</small>
                            <ul class="small mb-0 ps-3">
                                <?php foreach ($syarat as $dok): ?>
                                <li><?= htmlspecialchars($dok) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> Dibuat: <?= date('d/m/Y', strtotime($produk['created_at'])) ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    Belum ada produk kredit
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Kredit Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Kredit <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kredit" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kode Kredit <span class="text-danger">*</span></label>
                            <input type="text" name="kode_kredit" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Plafond Minimum <span class="text-danger">*</span></label>
                            <input type="number" name="plafond_min" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plafond Maximum <span class="text-danger">*</span></label>
                            <input type="number" name="plafond_max" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Bunga/Tahun (%) <span class="text-danger">*</span></label>
                            <input type="number" name="bunga_per_tahun" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tenor Min (bulan) <span class="text-danger">*</span></label>
                            <input type="number" name="tenor_min" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tenor Max (bulan) <span class="text-danger">*</span></label>
                            <input type="number" name="tenor_max" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Syarat Dokumen</label>
                            <input type="text" name="syarat_dokumen" class="form-control"
                                   placeholder="Pisahkan dengan koma, contoh: KTP, KK, Slip Gaji, NPWP">
                            <small class="text-muted">Pisahkan setiap dokumen dengan koma (,)</small>
                        </div>
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

$pageTitle = 'Manajemen Produk Kredit';
$pageSubtitle = 'Kelola produk kredit yang tersedia';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
