<?php
ob_start();
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Pengajuan</p>
                        <h3 class="mb-0"><?= $stats['total'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Sedang Diproses</p>
                        <h3 class="mb-0"><?= $stats['proses'] ?? 0 ?></h3>
                    </div>
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stats-card">
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
        <div class="card stats-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ajukan Baru</p>
                        <a href="<?= BASE_URL ?>nasabah/ajukanBaru" class="btn btn-primary btn-sm mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Ajukan Kredit
                        </a>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Pengajuan -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Pengajuan Kredit Terbaru</h5>
            <a href="<?= BASE_URL ?>nasabah/pengajuan" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($pengajuan)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-3">Belum ada pengajuan kredit</p>
                <a href="<?= BASE_URL ?>nasabah/ajukanBaru" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Ajukan Kredit Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pengajuan</th>
                            <th>Jenis Kredit</th>
                            <th>Jumlah Pinjaman</th>
                            <th>Tenor</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengajuan as $item): ?>
                            <tr>
                                <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                                <td><?= $item['nama_kredit'] ?></td>
                                <td>Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?></td>
                                <td><?= $item['tenor'] ?> bulan</td>
                                <td><?= getStatusBadge($item['status_pengajuan']) ?></td>
                                <td><?= date('d/m/Y', strtotime($item['created_at'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>nasabah/detailPengajuan/<?= $item['id_pengajuan'] ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
function getStatusBadge($status) {
    $badges = [
        'draft' => '<span class="badge-status bg-secondary">Draft</span>',
        'diajukan' => '<span class="badge-status bg-info text-white">Diajukan</span>',
        'verifikasi' => '<span class="badge-status bg-warning text-dark">Verifikasi</span>',
        'survei' => '<span class="badge-status bg-primary">Survei</span>',
        'analisis' => '<span class="badge-status bg-primary">Analisis</span>',
        'menunggu_keputusan' => '<span class="badge-status bg-warning text-dark">Menunggu Keputusan</span>',
        'disetujui' => '<span class="badge-status bg-success">Disetujui</span>',
        'ditolak' => '<span class="badge-status bg-danger">Ditolak</span>',
        'revisi' => '<span class="badge-status bg-warning text-dark">Perlu Revisi</span>',
        'dicairkan' => '<span class="badge-status bg-success">Dicairkan</span>'
    ];
    return $badges[$status] ?? '<span class="badge-status bg-secondary">' . $status . '</span>';
}

$content = ob_get_clean();

$pageTitle = 'Dashboard';
$pageSubtitle = 'Selamat datang, ' . $_SESSION['nama_lengkap'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
