<?php
ob_start();
?>

<!-- Laporan -->
<div class="row g-4 mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Pengajuan</p>
                        <h3 class="mb-0"><?= number_format($stats['total_pengajuan']) ?></h3>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-file-earmark-text"></i>
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
                        <h3 class="mb-0"><?= number_format($stats['disetujui']) ?></h3>
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
                        <h3 class="mb-0"><?= number_format($stats['ditolak']) ?></h3>
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
                        <p class="text-muted mb-1">Dalam Proses</p>
                        <h3 class="mb-0"><?= number_format($stats['proses']) ?></h3>
                    </div>
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Statistics -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Bulanan</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($monthlyStats)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Bulan</th>
                                <th class="text-end">Jumlah Pengajuan</th>
                                <th>Grafik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $maxJumlah = max(array_column($monthlyStats, 'jumlah'));
                            foreach ($monthlyStats as $stat):
                                // Format bulan
                                $bulan = date('F Y', strtotime($stat['bulan'] . '-01'));
                                $bulanIndo = [
                                    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                                    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                                    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                                    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                                ];
                                $bulan = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $bulan);

                                $percentage = $maxJumlah > 0 ? ($stat['jumlah'] / $maxJumlah * 100) : 0;
                            ?>
                            <tr>
                                <td><strong><?= $bulan ?></strong></td>
                                <td class="text-end">
                                    <span class="badge bg-primary"><?= number_format($stat['jumlah']) ?></span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: <?= $percentage ?>%"
                                             aria-valuenow="<?= $percentage ?>"
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($percentage, 0) ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-graph-up display-4 text-muted"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada data statistik</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Ringkasan</h5>
            </div>
            <div class="card-body">
                <?php
                $totalKeputusan = $stats['disetujui'] + $stats['ditolak'];
                $approvalRate = $totalKeputusan > 0 ? ($stats['disetujui'] / $totalKeputusan * 100) : 0;
                $rejectionRate = $totalKeputusan > 0 ? ($stats['ditolak'] / $totalKeputusan * 100) : 0;
                ?>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tingkat Persetujuan</span>
                        <strong class="text-success"><?= number_format($approvalRate, 1) ?>%</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: <?= $approvalRate ?>%"
                             aria-valuenow="<?= $approvalRate ?>"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tingkat Penolakan</span>
                        <strong class="text-danger"><?= number_format($rejectionRate, 1) ?>%</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-danger" role="progressbar"
                             style="width: <?= $rejectionRate ?>%"
                             aria-valuenow="<?= $rejectionRate ?>"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Total Disetujui</span>
                        <strong class="text-success"><?= number_format($stats['disetujui']) ?></strong>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Total Ditolak</span>
                        <strong class="text-danger"><?= number_format($stats['ditolak']) ?></strong>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Menunggu Keputusan</span>
                        <strong class="text-warning"><?= number_format($stats['proses']) ?></strong>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between border-top">
                        <span class="fw-bold">Total Pengajuan</span>
                        <strong class="text-primary"><?= number_format($stats['total_pengajuan']) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><i class="bi bi-download me-2"></i>Export Laporan</h6>
                        <small class="text-muted">Download laporan dalam berbagai format</small>
                    </div>
                    <div>
                        <button class="btn btn-outline-success btn-sm me-2" disabled>
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </button>
                        <button class="btn btn-outline-danger btn-sm" disabled>
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Laporan';
$pageSubtitle = 'Laporan dan statistik pengajuan kredit';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
