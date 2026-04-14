<?php
ob_start();
?>

<!-- Form Persetujuan Pimpinan -->
<div class="row">
    <div class="col-lg-8">
        <!-- Summary Pengajuan -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Ringkasan Pengajuan #<?= $pengajuan['no_pengajuan'] ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Nama Nasabah</label>
                        <div class="fw-bold"><?= htmlspecialchars($pengajuan['nama_lengkap']) ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Jenis Kredit</label>
                        <div><?= $pengajuan['nama_kredit'] ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Jumlah Pengajuan</label>
                        <div class="fw-bold text-primary fs-5">Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Tenor</label>
                        <div class="fw-bold"><?= $pengajuan['tenor'] ?> Bulan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Analisis Kredit -->
        <?php if ($analisis): ?>
        <div class="card mb-3">
            <div class="card-header bg-warning">
                <h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Hasil Analisis Kredit</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Analis</label>
                        <div class="fw-bold"><?= htmlspecialchars($analisis['nama_analis']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tanggal Analisis</label>
                        <div><?= date('d/m/Y H:i', strtotime($analisis['tanggal_analisis'])) ?></div>
                    </div>
                </div>

                <!-- Skor 5C -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6 class="fw-bold mb-2">Penilaian 5C:</h6>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-muted small">Character (25%)</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" style="width: <?= $analisis['skor_karakter'] ?>%">
                                <?= $analisis['skor_karakter'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-muted small">Capacity (30%)</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-info" style="width: <?= $analisis['skor_kapasitas'] ?>%">
                                <?= $analisis['skor_kapasitas'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-muted small">Capital (15%)</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: <?= $analisis['skor_modal'] ?>%">
                                <?= $analisis['skor_modal'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-muted small">Collateral (20%)</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-warning" style="width: <?= $analisis['skor_agunan'] ?>%">
                                <?= $analisis['skor_agunan'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-muted small">Condition (10%)</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-danger" style="width: <?= $analisis['skor_kondisi'] ?>%">
                                <?= $analisis['skor_kondisi'] ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="text-muted small">Skor Total (Tertimbang)</label>
                        <div class="fw-bold fs-4 text-primary"><?= number_format($analisis['skor_total'], 2) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">DSR (Debt Service Ratio)</label>
                        <div class="fw-bold fs-5"><?= number_format($analisis['rasio_dsr'], 2) ?>%</div>
                        <small class="text-muted">(Maks 40%)</small>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Kesimpulan Analis</label>
                        <div>
                            <?php
                            $badgeKesimpulan = [
                                'layak' => 'success',
                                'tidak_layak' => 'danger',
                                'layak_dengan_syarat' => 'warning'
                            ];
                            $badge = $badgeKesimpulan[$analisis['kesimpulan']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $badge ?> fs-6">
                                <?= ucfirst(str_replace('_', ' ', $analisis['kesimpulan'])) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Plafond Rekomendasi</label>
                        <div class="fw-bold">Rp <?= number_format($analisis['plafond_rekomendasi'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tenor Rekomendasi</label>
                        <div class="fw-bold"><?= $analisis['tenor_rekomendasi'] ?> Bulan</div>
                    </div>
                </div>

                <?php if ($analisis['catatan_analisis']): ?>
                <div class="mt-3">
                    <label class="text-muted small">Catatan Analisis</label>
                    <div class="bg-light p-3 rounded"><?= nl2br(htmlspecialchars($analisis['catatan_analisis'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Form Keputusan -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Form Keputusan</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="formKeputusan">
                    <?php
                    // Ensure $persetujuan is array
                    $persetujuan = is_array($persetujuan) ? $persetujuan : [];
                    $keputusan = $persetujuan['keputusan'] ?? '';
                    ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" class="form-select" id="keputusan" required onchange="toggleFields()">
                            <option value="">Pilih Keputusan</option>
                            <option value="disetujui" <?= $keputusan == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= $keputusan == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            <option value="revisi" <?= $keputusan == 'revisi' ? 'selected' : '' ?>>Perlu Revisi</option>
                        </select>
                    </div>

                    <!-- Field untuk Disetujui -->
                    <div id="fieldsDisetujui" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Plafond Disetujui (Rp)</label>
                            <input type="number" name="plafond_disetujui" class="form-control"
                                   value="<?= $persetujuan['plafond_disetujui'] ?? ($analisis['plafond_rekomendasi'] ?? $pengajuan['jumlah_pinjaman']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tenor Disetujui (Bulan)</label>
                            <input type="number" name="tenor_disetujui" class="form-control"
                                   value="<?= $persetujuan['tenor_disetujui'] ?? ($analisis['tenor_rekomendasi'] ?? $pengajuan['tenor']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bunga (%/tahun)</label>
                            <input type="number" step="0.01" name="bunga_disetujui" class="form-control"
                                   value="<?= $persetujuan['bunga_disetujui'] ?? $pengajuan['bunga_per_tahun'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Syarat Pencairan</label>
                            <textarea name="syarat_pencairan" class="form-control" rows="3"
                                      placeholder="Syarat yang harus dipenuhi sebelum pencairan..."><?= htmlspecialchars($persetujuan['syarat_pencairan'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alasan/Pertimbangan Keputusan <span class="text-danger">*</span></label>
                        <textarea name="alasan_keputusan" class="form-control" rows="4"
                                  placeholder="Jelaskan pertimbangan keputusan Anda..." required><?= htmlspecialchars($persetujuan['alasan_keputusan'] ?? '') ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Simpan Keputusan
                        </button>
                        <a href="<?= BASE_URL ?>pimpinan/persetujuan" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFields() {
    const keputusan = document.getElementById('keputusan').value;
    const fieldsDisetujui = document.getElementById('fieldsDisetujui');

    if (keputusan === 'disetujui') {
        fieldsDisetujui.style.display = 'block';
        fieldsDisetujui.querySelectorAll('input, textarea').forEach(el => el.required = true);
    } else {
        fieldsDisetujui.style.display = 'none';
        fieldsDisetujui.querySelectorAll('input, textarea').forEach(el => el.required = false);
    }
}

// Run on page load
document.addEventListener('DOMContentLoaded', toggleFields);
</script>

<?php
$content = ob_get_clean();

$pageTitle = 'Persetujuan Kredit';
$pageSubtitle = 'Review dan keputusan pengajuan #' . $pengajuan['no_pengajuan'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
