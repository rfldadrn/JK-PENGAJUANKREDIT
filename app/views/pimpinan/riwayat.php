<?php
ob_start();
?>

<!-- Riwayat Keputusan -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Keputusan</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($riwayat)): ?>
            <div class="text-center py-5">
                <i class="bi bi-clock-history display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">Belum ada riwayat keputusan</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pengajuan</th>
                            <th>Nasabah</th>
                            <th>Jenis Kredit</th>
                            <th>Pengajuan</th>
                            <th>Disetujui</th>
                            <th>Keputusan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $item): ?>
                        <tr>
                            <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                            <td>
                                <?= htmlspecialchars($item['nama_lengkap']) ?>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($item['email']) ?></small>
                            </td>
                            <td><?= $item['nama_kredit'] ?></td>
                            <td>Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($item['plafond_disetujui']): ?>
                                    <strong class="text-success">
                                        Rp <?= number_format($item['plafond_disetujui'], 0, ',', '.') ?>
                                    </strong>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['keputusan']): ?>
                                    <?php
                                    $keputusanBadge = [
                                        'disetujui' => 'success',
                                        'ditolak' => 'danger',
                                        'revisi' => 'warning'
                                    ];
                                    $badge = $keputusanBadge[$item['keputusan']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst($item['keputusan']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $item['tanggal_keputusan'] ? date('d/m/Y', strtotime($item['tanggal_keputusan'])) : '-' ?>
                            </td>
                            <td>
                                <?php
                                $statusBadge = [
                                    'menunggu_keputusan' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                    'revisi' => 'warning',
                                    'dicairkan' => 'success'
                                ];
                                $badge = $statusBadge[$item['status_pengajuan']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= ucfirst(str_replace('_', ' ', $item['status_pengajuan'])) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($riwayat)): ?>
    <div class="card-footer bg-white border-top">
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Menampilkan <?= count($riwayat) ?> riwayat terbaru
        </small>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Riwayat Keputusan';
$pageSubtitle = 'Riwayat keputusan persetujuan kredit';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
