<?php
ob_start();
?>

<!-- Dashboard Petugas -->
<div class="row g-4 mb-4">
    <!-- Statistik Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pengajuan Baru</p>
                        <h3 class="mb-0"><?= $stats['diajukan'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-inbox"></i>
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
                        <p class="text-muted mb-1">Verifikasi</p>
                        <h3 class="mb-0"><?= $stats['verifikasi'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-info bg-opacity-10 text-info">
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
                        <p class="text-muted mb-1">Survei Lapangan</p>
                        <h3 class="mb-0"><?= $stats['survei'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-geo-alt"></i>
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
                        <p class="text-muted mb-1">Selesai Analisis</p>
                        <h3 class="mb-0"><?= $stats['analisis'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-calculator"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pengajuan Baru -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-inbox me-2"></i>Pengajuan Baru</h5>
                <a href="<?= BASE_URL ?>petugas/verifikasi" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengajuanBaru)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nasabah</th>
                                    <th>Jenis Kredit</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($pengajuanBaru, 0, 5) as $item): ?>
                                <tr>
                                    <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                                    <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                                    <td><?= $item['nama_kredit'] ?></td>
                                    <td>Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>petugas/verifikasi/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Proses
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Tidak ada pengajuan baru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Menunggu Survei -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Menunggu Survei</h5>
                <a href="<?= BASE_URL ?>petugas/survei" class="btn btn-sm btn-warning">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengajuanSurvei)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nasabah</th>
                                    <th>Alamat Survei</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($pengajuanSurvei, 0, 5) as $item): ?>
                                <tr>
                                    <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                                    <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars(substr($item['alamat_ktp'], 0, 40)) ?>...</td>
                                    <td>
                                        <a href="<?= BASE_URL ?>petugas/survei/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-geo-alt"></i> Survei
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-geo-alt display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Tidak ada survei menunggu</p>
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
                        <a href="<?= BASE_URL ?>petugas/verifikasi" class="btn btn-outline-primary w-100">
                            <i class="bi bi-check-circle me-2"></i>Verifikasi Dokumen
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>petugas/survei" class="btn btn-outline-warning w-100">
                            <i class="bi bi-geo-alt me-2"></i>Input Survei
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>petugas/riwayat" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-clock-history me-2"></i>Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Dashboard Petugas';
$pageSubtitle = 'Selamat datang, ' . $_SESSION['nama_lengkap'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
