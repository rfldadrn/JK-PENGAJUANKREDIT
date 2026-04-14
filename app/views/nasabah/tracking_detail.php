<?php
ob_start();
?>

<!-- Tracking Detail Pengajuan -->
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Detail Pengajuan #<?= $pengajuan['no_pengajuan'] ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Jenis Kredit</label>
                        <div class="fw-bold"><?= $pengajuan['nama_kredit'] ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Status Terkini</label>
                        <div>
                            <?php
                            $statusConfig = [
                                'draft' => ['badge' => 'secondary', 'text' => 'Draft'],
                                'diajukan' => ['badge' => 'info', 'text' => 'Diajukan'],
                                'verifikasi' => ['badge' => 'primary', 'text' => 'Verifikasi Dokumen'],
                                'survei' => ['badge' => 'primary', 'text' => 'Survei Lapangan'],
                                'analisis' => ['badge' => 'warning', 'text' => 'Analisis Kredit'],
                                'menunggu_keputusan' => ['badge' => 'warning', 'text' => 'Menunggu Keputusan'],
                                'disetujui' => ['badge' => 'success', 'text' => 'Disetujui'],
                                'ditolak' => ['badge' => 'danger', 'text' => 'Ditolak'],
                                'revisi' => ['badge' => 'warning', 'text' => 'Perlu Revisi'],
                                'dicairkan' => ['badge' => 'success', 'text' => 'Dicairkan']
                            ];
                            $status = $statusConfig[$pengajuan['status_pengajuan']] ?? ['badge' => 'secondary', 'text' => $pengajuan['status_pengajuan']];
                            ?>
                            <span class="badge bg-<?= $status['badge'] ?> fs-6"><?= $status['text'] ?></span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Jumlah Pengajuan</label>
                        <div class="fw-bold text-primary fs-5">Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tenor</label>
                        <div class="fw-bold"><?= $pengajuan['tenor'] ?> Bulan</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Tujuan Kredit</label>
                    <div><?= nl2br(htmlspecialchars($pengajuan['tujuan_kredit'])) ?></div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Tanggal Pengajuan</label>
                    <div><?= $pengajuan['tanggal_pengajuan'] ? date('d F Y, H:i', strtotime($pengajuan['tanggal_pengajuan'])) : '-' ?></div>
                </div>

                <?php if ($pengajuan['tanggal_keputusan']): ?>
                <div class="mb-3">
                    <label class="text-muted small">Tanggal Keputusan</label>
                    <div><?= date('d F Y, H:i', strtotime($pengajuan['tanggal_keputusan'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($pengajuan['status_pengajuan'] === 'revisi'): ?>
            <div class="card-footer bg-white border-top">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Pengajuan memerlukan revisi.</strong> Silakan perbaiki dokumen atau data sesuai catatan dari petugas.
                </div>
                <a href="<?= BASE_URL ?>nasabah/revisi/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-warning w-100">
                    <i class="bi bi-pencil-square me-2"></i>Perbaiki Pengajuan
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Timeline Status -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Progress Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php
                    // Base workflow steps
                    $baseStatus = ['diajukan', 'verifikasi', 'survei', 'analisis', 'menunggu_keputusan'];

                    // Determine final status
                    $finalStatus = ['disetujui']; // default
                    if ($pengajuan['status_pengajuan'] === 'ditolak') {
                        $finalStatus = ['ditolak'];
                    } elseif ($pengajuan['status_pengajuan'] === 'dicairkan') {
                        $finalStatus = ['disetujui', 'dicairkan'];
                    } elseif ($pengajuan['status_pengajuan'] === 'revisi') {
                        // For revisi, only show up to current step
                        $baseStatus = ['diajukan', 'verifikasi']; // revisi happens at verification
                        $finalStatus = ['revisi'];
                    }

                    $allStatus = array_merge($baseStatus, $finalStatus);
                    $currentStatusIndex = array_search($pengajuan['status_pengajuan'], $allStatus);

                    foreach ($allStatus as $index => $statusItem):
                        $isDone = $index <= $currentStatusIndex;
                        $isCurrent = $statusItem === $pengajuan['status_pengajuan'];
                    ?>
                    <div class="timeline-item <?= $isDone ? 'done' : '' ?> <?= $isCurrent ? 'active' : '' ?> <?= in_array($statusItem, ['ditolak', 'revisi']) ? 'warning' : '' ?>">
                        <div class="timeline-marker">
                            <?php if ($isDone): ?>
                                <?php if ($statusItem === 'ditolak'): ?>
                                    <i class="bi bi-x-circle-fill"></i>
                                <?php elseif ($statusItem === 'revisi'): ?>
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                <?php else: ?>
                                    <i class="bi bi-check-circle-fill"></i>
                                <?php endif; ?>
                            <?php else: ?>
                                <i class="bi bi-circle"></i>
                            <?php endif; ?>
                        </div>
                        <div class="timeline-content">
                            <div class="fw-bold"><?= ucwords(str_replace('_', ' ', $statusItem)) ?></div>
                            <?php if ($isCurrent && $pengajuan['status_pengajuan'] === 'ditolak'): ?>
                                <small class="text-danger">Ditolak</small>
                            <?php elseif ($isCurrent && $pengajuan['status_pengajuan'] === 'revisi'): ?>
                                <small class="text-warning">Perlu Perbaikan</small>
                            <?php elseif ($isCurrent && in_array($pengajuan['status_pengajuan'], ['disetujui', 'dicairkan'])): ?>
                                <small class="text-success">Selesai</small>
                            <?php elseif ($isCurrent): ?>
                                <small class="text-primary">Sedang diproses</small>
                            <?php elseif ($isDone): ?>
                                <small class="text-success">Selesai</small>
                            <?php else: ?>
                                <small class="text-muted">Belum diproses</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="<?= BASE_URL ?>nasabah/tracking" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
    </a>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    padding: 15px 0;
    position: relative;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: 11px;
    top: 40px;
    bottom: -15px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item.done:not(:last-child):before {
    background: #28a745;
}

.timeline-marker {
    width: 24px;
    height: 24px;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #dee2e6;
}

.timeline-item.done .timeline-marker {
    color: #28a745;
}

.timeline-item.warning .timeline-marker {
    color: #dc3545;
}

.timeline-item.warning.active .timeline-marker {
    color: #ffc107;
}

.timeline-item.active .timeline-marker {
    color: #0066cc;
}

.timeline-content {
    flex: 1;
}
</style>

<?php
$content = ob_get_clean();

$pageTitle = 'Detail Pengajuan';
$pageSubtitle = 'Tracking pengajuan #' . ($pengajuan['no_pengajuan'] ?? '');

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
