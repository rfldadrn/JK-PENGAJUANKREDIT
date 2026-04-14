<?php
ob_start();
?>

<!-- List Persetujuan -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Daftar Pengajuan Menunggu Keputusan</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($pengajuanList)): ?>
            <div class="text-center py-5">
                <i class="bi bi-clipboard-check display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada pengajuan menunggu keputusan</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pengajuan</th>
                            <th>Nasabah</th>
                            <th>Jenis Kredit</th>
                            <th>Jumlah Pengajuan</th>
                            <th>Plafond Rekomendasi</th>
                            <th>Kesimpulan Analis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengajuanList as $item): ?>
                        <tr>
                            <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                            <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                            <td><?= $item['nama_kredit'] ?></td>
                            <td>Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?></td>
                            <td>-</td>
                            <td>
                                <span class="badge bg-warning">Review</span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>pimpinan/persetujuan/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-success">
                                    <i class="bi bi-clipboard-check"></i> Proses
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

$pageTitle = 'Persetujuan Kredit';
$pageSubtitle = 'Review dan keputusan pengajuan kredit';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
