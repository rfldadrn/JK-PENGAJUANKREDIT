<?php
ob_start();
?>

<!-- List Verifikasi -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Daftar Pengajuan untuk Verifikasi</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($pengajuanList)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada pengajuan untuk diverifikasi</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pengajuan</th>
                            <th>Nasabah</th>
                            <th>NIK</th>
                            <th>Jenis Kredit</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengajuanList as $item): ?>
                        <tr>
                            <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                            <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                            <td><?= $item['no_nik'] ?></td>
                            <td><?= $item['nama_kredit'] ?></td>
                            <td>Rp <?= number_format($item['jumlah_pinjaman'], 0, ',', '.') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($item['tanggal_pengajuan'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $item['status_pengajuan'] == 'diajukan' ? 'info' : 'primary' ?>">
                                    <?= ucfirst($item['status_pengajuan']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>petugas/verifikasi/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Verifikasi
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

$pageTitle = 'Verifikasi Dokumen';
$pageSubtitle = 'Verifikasi dokumen pengajuan kredit';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
