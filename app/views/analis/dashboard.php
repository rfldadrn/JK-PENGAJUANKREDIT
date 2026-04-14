<?php
ob_start();
?>

<!-- Dashboard Analis -->
<div class="row g-4 mb-4">
    <!-- Statistik Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Menunggu Analisis</p>
                        <h3 class="mb-0"><?= $stats['menunggu_analisis'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calculator"></i>
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
                        <p class="text-muted mb-1">Analisis Selesai</p>
                        <h3 class="mb-0"><?= $stats['analisis_selesai'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
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
                        <p class="text-muted mb-1">Layak</p>
                        <h3 class="mb-0"><?= $stats['total_layak'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-hand-thumbs-up"></i>
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
                        <p class="text-muted mb-1">Tidak Layak</p>
                        <h3 class="mb-0"><?= $stats['total_tidak_layak'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-hand-thumbs-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pengajuan Baru untuk Analisis -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-inbox me-2"></i>Menunggu Analisis</h5>
                <a href="<?= BASE_URL ?>analis/analisis" class="btn btn-sm btn-primary">Lihat Semua</a>
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
                                        <a href="<?= BASE_URL ?>analis/analisis/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-calculator"></i> Analisis
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
                        <p class="text-muted mt-2 mb-0">Tidak ada pengajuan menunggu</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Analisis Selesai -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Analisis Selesai</h5>
                <a href="<?= BASE_URL ?>analis/riwayat" class="btn btn-sm btn-success">Riwayat</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengajuanSelesai)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nasabah</th>
                                    <th>Skor Total</th>
                                    <th>Kesimpulan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengajuanSelesai as $item): ?>
                                <tr>
                                    <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                                    <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                                    <td>
                                        <?php
                                        $skorTotal = $item['skor_total'] ?? 0;
                                        $badgeColor = 'secondary';
                                        if ($skorTotal >= 80) $badgeColor = 'success';
                                        elseif ($skorTotal >= 60) $badgeColor = 'warning';
                                        elseif ($skorTotal > 0) $badgeColor = 'danger';
                                        ?>
                                        <span class="badge bg-<?= $badgeColor ?>"><?= number_format($skorTotal, 1) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $kesimpulan = $item['kesimpulan'] ?? 'menunggu_keputusan';
                                        $kesimpulanBadge = [
                                            'layak' => 'success',
                                            'tidak_layak' => 'danger',
                                            'layak_dengan_syarat' => 'warning',
                                            'menunggu_keputusan' => 'info'
                                        ];
                                        $badge = $kesimpulanBadge[$kesimpulan] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= ucwords(str_replace('_', ' ', $kesimpulan)) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada analisis selesai</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Guide -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Panduan Analisis Kredit (5C)</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>Character (25%):</strong> Rekam jejak, reputasi, kejujuran
                    </div>
                    <div class="col-md-4">
                        <strong>Capacity (30%):</strong> Kemampuan bayar, DSR (maks 40%)
                    </div>
                    <div class="col-md-4">
                        <strong>Capital (15%):</strong> Modal, aset, kekayaan bersih
                    </div>
                    <div class="col-md-4">
                        <strong>Collateral (20%):</strong> Agunan (coverage ratio min 100%)
                    </div>
                    <div class="col-md-4">
                        <strong>Condition (10%):</strong> Kondisi ekonomi, sektor usaha
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Dashboard Analis';
$pageSubtitle = 'Selamat datang, ' . $_SESSION['nama_lengkap'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
