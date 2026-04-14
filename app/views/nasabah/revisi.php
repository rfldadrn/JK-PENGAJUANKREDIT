<?php
ob_start();
?>

<!-- Revisi Pengajuan -->
<div class="row g-4">
    <!-- Alert Revisi -->
    <div class="col-12">
        <div class="alert alert-warning border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 2rem;"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">Pengajuan Memerlukan Revisi</h5>
                    <p class="mb-0">Pengajuan kredit Anda memerlukan perbaikan. Silakan periksa catatan dari petugas dan perbaiki dokumen atau data yang diperlukan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Catatan Verifikasi -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Catatan Verifikasi</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($verifikasi)): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Kelengkapan Dokumen</label>
                        <div>
                            <?php
                            $badgeMap = [
                                'lengkap' => 'success',
                                'tidak_lengkap' => 'danger',
                                'perlu_perbaikan' => 'warning'
                            ];
                            $badge = $badgeMap[$verifikasi['kelengkapan_dokumen'] ?? ''] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $badge ?>">
                                <?= ucwords(str_replace('_', ' ', $verifikasi['kelengkapan_dokumen'] ?? 'Belum ada')) ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Kesesuaian Data</label>
                        <div>
                            <?php
                            $badgeData = [
                                'sesuai' => 'success',
                                'tidak_sesuai' => 'danger'
                            ];
                            $badgeD = $badgeData[$verifikasi['kesesuaian_data'] ?? ''] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $badgeD ?>">
                                <?= ucwords(str_replace('_', ' ', $verifikasi['kesesuaian_data'] ?? 'Belum ada')) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Catatan dari Petugas</label>
                    <div class="alert alert-light border">
                        <?= nl2br(htmlspecialchars($verifikasi['catatan_verifikasi'] ?? 'Tidak ada catatan')) ?>
                    </div>
                </div>

                <div>
                    <label class="text-muted small">Diverifikasi Oleh</label>
                    <div><?= htmlspecialchars($verifikasi['nama_petugas'] ?? '-') ?></div>
                    <small class="text-muted">
                        <?= isset($verifikasi['tanggal_verifikasi']) ? date('d/m/Y H:i', strtotime($verifikasi['tanggal_verifikasi'])) : '-' ?>
                    </small>
                </div>
                <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-info-circle me-2"></i>
                    Belum ada catatan verifikasi
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dokumen yang Perlu Diperbaiki -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Dokumen</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($dokumen)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Dokumen</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dokumen as $dok): ?>
                            <tr>
                                <td><?= htmlspecialchars($dok['jenis_dokumen']) ?></td>
                                <td>
                                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                                    <?= htmlspecialchars($dok['nama_file']) ?>
                                </td>
                                <td>
                                    <?php
                                    $statusBadge = [
                                        'belum_diverifikasi' => 'secondary',
                                        'valid' => 'success',
                                        'tidak_valid' => 'danger'
                                    ];
                                    $badge = $statusBadge[$dok['status_dokumen']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucwords(str_replace('_', ' ', $dok['status_dokumen'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>assets/uploads/<?= $dok['path_file'] ?>"
                                       target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <?php if ($dok['status_dokumen'] === 'tidak_valid'): ?>
                                    <button onclick="if(confirm('Hapus dokumen ini?')) window.location.href='<?= BASE_URL ?>nasabah/deleteDokumen/<?= $dok['id_dokumen'] ?>'"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="<?= BASE_URL ?>nasabah/step3/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Upload Dokumen Baru
                    </a>
                </div>
                <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox display-4"></i>
                    <p class="mt-2 mb-0">Belum ada dokumen</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info & Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">No. Pengajuan</label>
                    <div class="fw-bold"><?= $pengajuan['no_pengajuan'] ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Jenis Kredit</label>
                    <div><?= htmlspecialchars($pengajuan['nama_kredit'] ?? '-') ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Jumlah Pinjaman</label>
                    <div class="fw-bold text-primary">
                        Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Tenor</label>
                    <div><?= $pengajuan['tenor'] ?> Bulan</div>
                </div>
                <div>
                    <label class="text-muted small">Status</label>
                    <div>
                        <span class="badge bg-warning">Perlu Revisi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Langkah Revisi</h5>
            </div>
            <div class="card-body">
                <ol class="ps-3 mb-0">
                    <li class="mb-2">Baca catatan verifikasi dari petugas</li>
                    <li class="mb-2">Perbaiki dokumen atau data yang tidak valid</li>
                    <li class="mb-2">Upload dokumen baru jika diperlukan</li>
                    <li class="mb-2">Klik tombol "Submit Ulang" untuk verifikasi ulang</li>
                </ol>
            </div>
            <div class="card-footer bg-white">
                <form method="POST" action="<?= BASE_URL ?>nasabah/submitRevisi/<?= $pengajuan['id_pengajuan'] ?>"
                      onsubmit="return confirm('Apakah Anda yakin sudah memperbaiki semua yang diperlukan dan ingin submit ulang untuk verifikasi?')">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send me-2"></i>Submit Ulang untuk Verifikasi
                        </button>
                        <a href="<?= BASE_URL ?>nasabah/tracking/<?= $pengajuan['id_pengajuan'] ?>"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Revisi Pengajuan';
$pageSubtitle = 'Perbaiki pengajuan kredit Anda';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
