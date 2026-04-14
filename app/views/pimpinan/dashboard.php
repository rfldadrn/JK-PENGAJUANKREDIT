<?php
ob_start();
?>

<!-- Dashboard Pimpinan -->
<div class="row g-4 mb-4">
    <!-- Statistik Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Menunggu Keputusan</p>
                        <h3 class="mb-0"><?= $stats['menunggu'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Disetujui</p>
                        <h3 class="mb-0"><?= $stats['disetujui'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ditolak</p>
                        <h3 class="mb-0"><?= $stats['ditolak'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Perlu Revisi</p>
                        <h3 class="mb-0"><?= $stats['revisi'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pengajuan Menunggu Keputusan -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Menunggu Keputusan</h5>
                <a href="<?= BASE_URL ?>pimpinan/persetujuan" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengajuanBaru)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nasabah</th>
                                    <th>Kredit</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengajuanBaru as $item): ?>
                                <tr>
                                    <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                                    <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                                    <td><?= $item['nama_kredit'] ?></td>
                                    <td>Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>pimpinan/persetujuan/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-success">
                                            <i class="bi bi-clipboard-check"></i> Review
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-check display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Tidak ada pengajuan menunggu</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pengajuan Disetujui -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Disetujui Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengajuanDisetujui)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pengajuanDisetujui as $item): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= $item['no_pengajuan'] ?></strong>
                                    <div class="small text-muted"><?= htmlspecialchars($item['nama_lengkap']) ?></div>
                                </div>
                                <span class="badge bg-success">Disetujui</span>
                            </div>
                            <div class="mt-1 small">
                                Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?> / <?= $item['tenor'] ?> bln
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0 small">Belum ada yang disetujui</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>pimpinan/persetujuan" class="btn btn-outline-primary w-100">
                            <i class="bi bi-clipboard-check me-2"></i>Review Pengajuan
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>pimpinan/laporan" class="btn btn-outline-success w-100">
                            <i class="bi bi-bar-chart me-2"></i>Lihat Laporan
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>pimpinan/riwayat" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Keputusan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Dashboard Pimpinan';
$pageSubtitle = 'Selamat datang, ' . $_SESSION['nama_lengkap'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
