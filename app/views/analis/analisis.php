<?php
ob_start();
?>

<!-- List Analisis -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning">
                <h6 class="mb-0 text-white"><i class="bi bi-inbox me-2"></i>Menunggu Analisis</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pengajuanBaru)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Tidak ada pengajuan menunggu</p>
                    </div>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Analisis Selesai</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pengajuanSelesai)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle display-4 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada analisis selesai</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nasabah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengajuanSelesai as $item): ?>
                                <tr>
                                    <td><strong><?= $item['no_pengajuan'] ?></strong></td>
                                    <td><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                                    <td><span class="badge bg-warning">Menunggu Pimpinan</span></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>analis/analisis/<?= $item['id_pengajuan'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
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
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Analisis Kredit';
$pageSubtitle = 'Daftar pengajuan untuk analisis 5C';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
