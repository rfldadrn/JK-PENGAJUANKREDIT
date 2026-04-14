<?php
ob_start();
?>

<!-- Form Analisis Kredit (5C) -->
<div class="row">
    <div class="col-lg-8">
        <!-- Data Pengajuan -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Pengajuan #<?= $pengajuan['no_pengajuan'] ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small">Nasabah</label>
                        <div class="fw-bold"><?= htmlspecialchars($pengajuan['nama_lengkap']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Jenis Kredit</label>
                        <div><?= $pengajuan['nama_kredit'] ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Jumlah Pengajuan</label>
                        <div class="fw-bold text-primary">Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tenor</label>
                        <div><?= $pengajuan['tenor'] ?> Bulan</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Penghasilan Bulanan</label>
                        <div>Rp <?= number_format($pengajuan['penghasilan_bulanan'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Estimasi Angsuran</label>
                        <div class="fw-bold">Rp <?= $autoCalc['angsuran'] ?? '0' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Verifikasi -->
        <?php if ($verifikasi): ?>
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Hasil Verifikasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small">Kelengkapan Dokumen</label>
                        <div><span class="badge bg-<?= $verifikasi['kelengkapan_dokumen'] == 'lengkap' ? 'success' : 'warning' ?>">
                            <?= ucfirst(str_replace('_', ' ', $verifikasi['kelengkapan_dokumen'])) ?>
                        </span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Rekomendasi Verifikasi</label>
                        <div><span class="badge bg-primary"><?= ucfirst(str_replace('_', ' ', $verifikasi['rekomendasi'])) ?></span></div>
                    </div>
                    <div class="col-12 mt-2">
                        <label class="text-muted small">Catatan</label>
                        <div><?= nl2br(htmlspecialchars($verifikasi['catatan_verifikasi'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Hasil Survei -->
        <?php if ($survei): ?>
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Hasil Survei Lapangan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="text-muted small">Kondisi Usaha</label>
                        <div><span class="badge bg-<?= $survei['kondisi_usaha'] == 'baik' ? 'success' : ($survei['kondisi_usaha'] == 'cukup' ? 'warning' : 'danger') ?>">
                            <?= ucfirst($survei['kondisi_usaha']) ?>
                        </span></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Omzet Usaha</label>
                        <div>Rp <?= number_format($survei['omzet_usaha'], 0, ',', '.') ?>/bulan</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Kondisi Agunan</label>
                        <div><span class="badge bg-<?= $survei['kondisi_agunan'] == 'baik' ? 'success' : ($survei['kondisi_agunan'] == 'cukup' ? 'warning' : 'danger') ?>">
                            <?= ucfirst($survei['kondisi_agunan']) ?>
                        </span></div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="text-muted small">Nilai Agunan Survei</label>
                        <div>Rp <?= number_format($survei['nilai_agunan_estimasi'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="text-muted small">Rekomendasi Survei</label>
                        <div><span class="badge bg-<?= $survei['rekomendasi_survei'] == 'layak' ? 'success' : 'warning' ?>">
                            <?= ucfirst(str_replace('_', ' ', $survei['rekomendasi_survei'])) ?>
                        </span></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Form Analisis 5C -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Analisis Kredit (5C)</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php
                    // Ensure $analisis is array
                    $analisis = is_array($analisis) ? $analisis : [];
                    $kesimpulan = $analisis['kesimpulan'] ?? '';
                    ?>

                    <!-- Character -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Character (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="skor_karakter" class="form-control" min="0" max="100"
                               value="<?= $analisis['skor_karakter'] ?? '' ?>" required>
                        <small class="text-muted">Bobot 25% - Rekam jejak, reputasi</small>
                    </div>

                    <!-- Capacity -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Capacity (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="skor_kapasitas" class="form-control" min="0" max="100"
                               value="<?= $analisis['skor_kapasitas'] ?? $autoCalc['skor_kapasitas'] ?? '' ?>" required>
                        <small class="text-muted">Bobot 30% - Kemampuan bayar</small>
                        <div class="alert alert-info p-2 mt-1">
                            <small><strong>DSR:</strong> <?= $autoCalc['dsr'] ?? '0' ?>% (Maks 40%)</small>
                        </div>
                    </div>

                    <!-- Capital -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Capital (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="skor_modal" class="form-control" min="0" max="100"
                               value="<?= $analisis['skor_modal'] ?? '' ?>" required>
                        <small class="text-muted">Bobot 15% - Modal, aset</small>
                    </div>

                    <!-- Collateral -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Collateral (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="skor_agunan" class="form-control" min="0" max="100"
                               value="<?= $analisis['skor_agunan'] ?? $autoCalc['skor_agunan'] ?? '' ?>" required>
                        <small class="text-muted">Bobot 20% - Agunan</small>
                        <div class="alert alert-info p-2 mt-1">
                            <small><strong>Total Agunan:</strong> Rp <?= $autoCalc['total_agunan'] ?? '0' ?></small>
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Condition (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="skor_kondisi" class="form-control" min="0" max="100"
                               value="<?= $analisis['skor_kondisi'] ?? '' ?>" required>
                        <small class="text-muted">Bobot 10% - Kondisi ekonomi</small>
                    </div>

                    <hr>

                    <!-- DSR -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Debt Service Ratio (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="rasio_dsr" class="form-control"
                               value="<?= $analisis['rasio_dsr'] ?? $autoCalc['dsr'] ?? '' ?>" required readonly>
                        <small class="text-muted">Angsuran / Penghasilan × 100%</small>
                    </div>

                    <!-- Plafond Rekomendasi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Plafond Rekomendasi (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="plafond_rekomendasi" class="form-control"
                               value="<?= $analisis['plafond_rekomendasi'] ?? $pengajuan['jumlah_pinjaman'] ?>" required>
                    </div>

                    <!-- Tenor Rekomendasi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tenor Rekomendasi (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="tenor_rekomendasi" class="form-control"
                               value="<?= $analisis['tenor_rekomendasi'] ?? $pengajuan['tenor'] ?>" required>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Analisis</label>
                        <textarea name="catatan_analisis" class="form-control" rows="4"
                                  placeholder="Catatan lengkap hasil analisis..."><?= htmlspecialchars($analisis['catatan_analisis'] ?? '') ?></textarea>
                    </div>

                    <!-- Kesimpulan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kesimpulan <span class="text-danger">*</span></label>
                        <select name="kesimpulan" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="layak" <?= $kesimpulan == 'layak' ? 'selected' : '' ?>>Layak</option>
                            <option value="tidak_layak" <?= $kesimpulan == 'tidak_layak' ? 'selected' : '' ?>>Tidak Layak</option>
                            <option value="layak_dengan_syarat" <?= $kesimpulan == 'layak_dengan_syarat' ? 'selected' : '' ?>>Layak dengan Syarat</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-2"></i>Simpan Analisis
                        </button>
                        <a href="<?= BASE_URL ?>analis/analisis" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Analisis Kredit';
$pageSubtitle = 'Form analisis 5C - #' . $pengajuan['no_pengajuan'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
