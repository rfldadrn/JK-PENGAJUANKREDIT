<?php
ob_start();
?>

<!-- Verifikasi Detail -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Data Pengajuan -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Data Pengajuan #<?= $pengajuan['no_pengajuan'] ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Nama Nasabah</label>
                        <div class="fw-bold"><?= htmlspecialchars($pengajuan['nama_lengkap']) ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">NIK</label>
                        <div class="fw-bold"><?= $pengajuan['no_nik'] ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Jenis Kredit</label>
                        <div><?= $pengajuan['nama_kredit'] ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Jumlah Pinjaman</label>
                        <div class="fw-bold text-primary">Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Tenor</label>
                        <div><?= $pengajuan['tenor'] ?> Bulan</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Penghasilan Bulanan</label>
                        <div>Rp <?= number_format($pengajuan['penghasilan_bulanan'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="text-muted small">Tujuan Kredit</label>
                        <div><?= nl2br(htmlspecialchars($pengajuan['tujuan_kredit'])) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Dokumen (<?= count($dokumen) ?>)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Dokumen</th>
                                <th>File</th>
                                <th>Ukuran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dokumen as $dok): ?>
                            <tr>
                                <td><?= htmlspecialchars($dok['jenis_dokumen']) ?></td>
                                <td><i class="bi bi-file-earmark-pdf text-danger me-1"></i><?= htmlspecialchars($dok['nama_file']) ?></td>
                                <td><?= number_format($dok['ukuran_file'], 0) ?> KB</td>
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
                                        <?= ucfirst(str_replace('_', ' ', $dok['status_dokumen'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="verifikasiDokumen(<?= $dok['id_dokumen'] ?>, 'valid')">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="verifikasiDokumen(<?= $dok['id_dokumen'] ?>, 'tidak_valid')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Agunan -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-shield-check me-2"></i>Agunan (<?= count($agunan) ?>)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis</th>
                                <th>Nama Agunan</th>
                                <th>Atas Nama</th>
                                <th>Nilai Taksasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agunan as $ag): ?>
                            <tr>
                                <td><span class="badge bg-primary"><?= ucfirst($ag['jenis_agunan']) ?></span></td>
                                <td><?= htmlspecialchars($ag['nama_agunan']) ?></td>
                                <td><?= htmlspecialchars($ag['atas_nama']) ?></td>
                                <td>Rp <?= number_format($ag['nilai_taksasi'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Verifikasi -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Form Verifikasi</h6>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); endif; ?>

                <form method="POST" action="">
                    <?php
                    // Ensure $verifikasi is array
                    $verifikasi = is_array($verifikasi) ? $verifikasi : [];
                    $kelengkapan = $verifikasi['kelengkapan_dokumen'] ?? '';
                    $kesesuaian = $verifikasi['kesesuaian_data'] ?? '';
                    $catatan = $verifikasi['catatan_verifikasi'] ?? '';
                    $rekomendasi = $verifikasi['rekomendasi'] ?? '';
                    ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kelengkapan Dokumen</label>
                        <select name="kelengkapan_dokumen" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="lengkap" <?= $kelengkapan == 'lengkap' ? 'selected' : '' ?>>Lengkap</option>
                            <option value="tidak_lengkap" <?= $kelengkapan == 'tidak_lengkap' ? 'selected' : '' ?>>Tidak Lengkap</option>
                            <option value="perlu_perbaikan" <?= $kelengkapan == 'perlu_perbaikan' ? 'selected' : '' ?>>Perlu Perbaikan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kesesuaian Data</label>
                        <select name="kesesuaian_data" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="sesuai" <?= $kesesuaian == 'sesuai' ? 'selected' : '' ?>>Sesuai</option>
                            <option value="tidak_sesuai" <?= $kesesuaian == 'tidak_sesuai' ? 'selected' : '' ?>>Tidak Sesuai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Verifikasi</label>
                        <textarea name="catatan_verifikasi" class="form-control" rows="4"
                                  placeholder="Catatan detail hasil verifikasi..."><?= htmlspecialchars($catatan) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Rekomendasi</label>
                        <select name="rekomendasi" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="lanjut_survei" <?= $rekomendasi == 'lanjut_survei' ? 'selected' : '' ?>>Lanjut ke Survei</option>
                            <option value="revisi" <?= $rekomendasi == 'revisi' ? 'selected' : '' ?>>Perlu Revisi</option>
                            <option value="tolak" <?= $rekomendasi == 'tolak' ? 'selected' : '' ?>>Tolak</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-2"></i>Simpan Verifikasi
                        </button>
                        <a href="<?= BASE_URL ?>petugas/verifikasi" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function verifikasiDokumen(idDokumen, status) {
    if (!confirm('Verifikasi dokumen sebagai ' + status + '?')) return;

    const formData = new FormData();
    formData.append('status_dokumen', status);
    formData.append('catatan_verifikasi', prompt('Catatan (opsional):') || '');

    fetch('<?= BASE_URL ?>petugas/verifikasiDokumen/' + idDokumen, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}
</script>

<?php
$content = ob_get_clean();

$pageTitle = 'Verifikasi Dokumen';
$pageSubtitle = 'Detail pengajuan #' . $pengajuan['no_pengajuan'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
