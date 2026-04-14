<?php
ob_start();
?>

<!-- Tracking Pengajuan List -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Tracking Pengajuan Kredit</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($pengajuan)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">Belum ada pengajuan kredit</p>
                <a href="<?= BASE_URL ?>nasabah/step1" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Ajukan Kredit Baru
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pengajuan</th>
                            <th>Jenis Kredit</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
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
                            <td><?= $item['tanggal_pengajuan'] ? date('d/m/Y H:i', strtotime($item['tanggal_pengajuan'])) : '-' ?></td>
                            <td>
                                <?php
                                $statusConfig = [
                                    'draft' => ['badge' => 'secondary', 'icon' => 'file-earmark'],
                                    'diajukan' => ['badge' => 'info', 'icon' => 'send'],
                                    'verifikasi' => ['badge' => 'primary', 'icon' => 'check-circle'],
                                    'survei' => ['badge' => 'primary', 'icon' => 'geo-alt'],
                                    'analisis' => ['badge' => 'warning', 'icon' => 'calculator'],
                                    'menunggu_keputusan' => ['badge' => 'warning', 'icon' => 'clock-history'],
                                    'disetujui' => ['badge' => 'success', 'icon' => 'check-circle-fill'],
                                    'ditolak' => ['badge' => 'danger', 'icon' => 'x-circle-fill'],
                                    'revisi' => ['badge' => 'warning', 'icon' => 'arrow-repeat'],
                                    'dicairkan' => ['badge' => 'success', 'icon' => 'cash-stack']
                                ];
                                $status = $statusConfig[$item['status_pengajuan']] ?? ['badge' => 'secondary', 'icon' => 'question'];
                                ?>
                                <span class="badge bg-<?= $status['badge'] ?>">
                                    <i class="bi bi-<?= $status['icon'] ?> me-1"></i>
                                    <?= ucwords(str_replace('_', ' ', $item['status_pengajuan'])) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>nasabah/tracking/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i>Detail
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
$content = ob_get_clean();

$pageTitle = 'Tracking Pengajuan';
$pageSubtitle = 'Pantau status pengajuan kredit Anda';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
