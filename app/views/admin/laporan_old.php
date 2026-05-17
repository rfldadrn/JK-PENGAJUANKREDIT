<?php
ob_start();
?>

<!-- Laporan & Analytics -->
<div class="container-fluid">
    <h2 class="mb-4">Laporan & Analitik</h2>

    <!-- Status Distribution -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Distribusi Status Pengajuan</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['statusStats'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = array_sum(array_column($data['statusStats'], 'jumlah'));
                                $statusLabels = [
                                    'diajukan' => 'Diajukan',
                                    'verifikasi' => 'Verifikasi',
                                    'survei' => 'Survei',
                                    'analisis' => 'Analisis',
                                    'menunggu_keputusan' => 'Menunggu Keputusan',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'dicairkan' => 'Dicairkan'
                                ];
                                ?>
                                <?php foreach ($data['statusStats'] as $stat): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= $statusLabels[$stat['status_pengajuan']] ?? ucfirst($stat['status_pengajuan']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end"><strong><?= number_format($stat['jumlah']) ?></strong></td>
                                    <td class="text-end">
                                        <?php
                                        $percentage = $total > 0 ? ($stat['jumlah'] / $total * 100) : 0;
                                        ?>
                                        <div class="progress" style="height: 20px; min-width: 100px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: <?= $percentage ?>%"
                                                 aria-valuenow="<?= $percentage ?>"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?= number_format($percentage, 1) ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-light fw-bold">
                                    <td>Total</td>
                                    <td class="text-end"><?= number_format($total) ?></td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-pie-chart fs-1 d-block mb-3"></i>
                        Belum ada data
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Product Performance -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Performa Produk</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['productStats'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Pengajuan</th>
                                    <th class="text-end">Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['productStats'] as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['nama_kredit']) ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-primary"><?= number_format($product['jumlah_pengajuan']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp <?= number_format($product['total_nilai'] ?? 0, 0, ',', '.') ?></strong>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-light fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">
                                        <?= number_format(array_sum(array_column($data['productStats'], 'jumlah_pengajuan'))) ?>
                                    </td>
                                    <td class="text-end">
                                        Rp <?= number_format(array_sum(array_column($data['productStats'], 'total_nilai')), 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bar-chart fs-1 d-block mb-3"></i>
                        Belum ada data
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Statistik Bulanan (12 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['monthlyStats'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Jumlah Pengajuan</th>
                                    <th class="text-end">Total Nilai Pinjaman</th>
                                    <th class="text-end">Rata-rata Pinjaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPengajuan = 0;
                                $totalNilai = 0;
                                ?>
                                <?php foreach ($data['monthlyStats'] as $stat): ?>
                                <?php
                                $totalPengajuan += $stat['jumlah'];
                                $totalNilai += $stat['total_nilai'];
                                $rataRata = $stat['jumlah'] > 0 ? $stat['total_nilai'] / $stat['jumlah'] : 0;

                                // Format bulan
                                $bulan = date('F Y', strtotime($stat['bulan'] . '-01'));
                                $bulanIndo = [
                                    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                                    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                                    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                                    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                                ];
                                $bulan = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $bulan);
                                ?>
                                <tr>
                                    <td><strong><?= $bulan ?></strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-info"><?= number_format($stat['jumlah']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp <?= number_format($stat['total_nilai'], 0, ',', '.') ?></strong>
                                    </td>
                                    <td class="text-end text-muted">
                                        Rp <?= number_format($rataRata, 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-light fw-bold">
                                    <td>Total</td>
                                    <td class="text-end"><?= number_format($totalPengajuan) ?></td>
                                    <td class="text-end">Rp <?= number_format($totalNilai, 0, ',', '.') ?></td>
                                    <td class="text-end">
                                        Rp <?= number_format($totalPengajuan > 0 ? $totalNilai / $totalPengajuan : 0, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-graph-up fs-1 d-block mb-3"></i>
                        Belum ada data statistik bulanan
                    </div>
                    <?php endif; ?>
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
                            <h6 class="mb-1">Export Laporan</h6>
                            <small class="text-muted">Download laporan dalam berbagai format</small>
                        </div>
                        <div>
                            <button class="btn btn-outline-success btn-sm me-2" disabled>
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </button>
                            <button class="btn btn-outline-danger btn-sm" disabled>
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Laporan & Analitik';
$pageSubtitle = 'Statistik dan laporan sistem';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
