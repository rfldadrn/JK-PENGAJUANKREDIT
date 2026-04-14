<?php
ob_start();
?>

<!-- Dashboard Content -->
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Admin</h2>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill fs-3 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h3 class="mb-0"><?= number_format($data['stats']['total_users']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-check-fill fs-3 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Nasabah</h6>
                            <h3 class="mb-0"><?= number_format($data['stats']['total_nasabah']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-file-earmark-text-fill fs-3 text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Pengajuan</h6>
                            <h3 class="mb-0"><?= number_format($data['stats']['total_pengajuan']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pengajuan Aktif</h6>
                            <h3 class="mb-0"><?= number_format($data['stats']['pengajuan_aktif']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Pengajuan -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Pengajuan Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($data['recentPengajuan'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nasabah</th>
                                    <th>Jenis Kredit</th>
                                    <th>Plafond</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['recentPengajuan'] as $p): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= $p['no_pengajuan'] ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($p['email']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($p['nama_kredit']) ?></td>
                                    <td>Rp <?= number_format($p['jumlah_pinjaman'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = [
                                            'diajukan' => 'bg-info',
                                            'verifikasi' => 'bg-primary',
                                            'survei' => 'bg-warning',
                                            'analisis' => 'bg-secondary',
                                            'menunggu_keputusan' => 'bg-warning',
                                            'disetujui' => 'bg-success',
                                            'ditolak' => 'bg-danger',
                                            'dicairkan' => 'bg-success'
                                        ];
                                        $class = $badgeClass[$p['status_pengajuan']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $class ?>"><?= ucwords(str_replace('_', ' ', $p['status_pengajuan'])) ?></span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        Belum ada pengajuan
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($data['recentLogs'])): ?>
                    <div class="timeline">
                        <?php foreach ($data['recentLogs'] as $log): ?>
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 small"><?= htmlspecialchars($log['aktivitas']) ?></p>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> <?= htmlspecialchars($log['nama_lengkap']) ?>
                                        <br>
                                        <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($log['waktu'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clock-history fs-1 d-block mb-3"></i>
                        Belum ada aktivitas
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 14px;
    top: 30px;
    height: calc(100% - 10px);
    width: 2px;
    background: #e9ecef;
}
</style>

<?php
$content = ob_get_clean();

$pageTitle = 'Dashboard Admin';
$pageSubtitle = 'Selamat datang, ' . $_SESSION['nama_lengkap'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
