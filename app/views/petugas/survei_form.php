<?php
ob_start();
?>

<!-- Form Survei Lapangan -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-3">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Form Survei Lapangan - <?= $pengajuan['no_pengajuan'] ?></h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Nasabah:</strong> <?= htmlspecialchars($pengajuan['nama_lengkap']) ?> |
                    <strong>Kredit:</strong> <?= $pengajuan['nama_kredit'] ?> |
                    <strong>Jumlah:</strong> Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?>
                </div>

                <form method="POST" action="" enctype="multipart/form-data">
                    <?php
                    // Ensure $survei is array
                    $survei = is_array($survei) ? $survei : [];
                    $kondisiUsaha = $survei['kondisi_usaha'] ?? '';
                    $kondisiAgunan = $survei['kondisi_agunan'] ?? '';
                    $rekomendasiSurvei = $survei['rekomendasi_survei'] ?? '';
                    ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Survei <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_survei" class="form-control"
                                   value="<?= $survei['tanggal_survei'] ?? ($old['tanggal_survei'] ?? date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Alamat Survei <span class="text-danger">*</span></label>
                            <input type="text" name="alamat_survei" class="form-control"
                                   value="<?= $survei['alamat_survei'] ?? ($old['alamat_survei'] ?? $pengajuan['alamat_ktp']) ?>"
                                   placeholder="Alamat lokasi survei" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Kondisi Usaha <span class="text-danger">*</span></label>
                            <select name="kondisi_usaha" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="baik" <?= $kondisiUsaha == 'baik' ? 'selected' : '' ?>>Baik</option>
                                <option value="cukup" <?= $kondisiUsaha == 'cukup' ? 'selected' : '' ?>>Cukup</option>
                                <option value="kurang" <?= $kondisiUsaha == 'kurang' ? 'selected' : '' ?>>Kurang</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Omzet Usaha/Bulan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="omzet_usaha" class="form-control"
                                   value="<?= $survei['omzet_usaha'] ?? ($old['omzet_usaha'] ?? '') ?>"
                                   placeholder="Estimasi omzet bulanan" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Kondisi Agunan <span class="text-danger">*</span></label>
                            <select name="kondisi_agunan" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="baik" <?= $kondisiAgunan == 'baik' ? 'selected' : '' ?>>Baik</option>
                                <option value="cukup" <?= $kondisiAgunan == 'cukup' ? 'selected' : '' ?>>Cukup</option>
                                <option value="kurang" <?= $kondisiAgunan == 'kurang' ? 'selected' : '' ?>>Kurang</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nilai Agunan Estimasi (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_agunan_estimasi" class="form-control"
                                   value="<?= $survei['nilai_agunan_estimasi'] ?? ($old['nilai_agunan_estimasi'] ?? '') ?>"
                                   placeholder="Estimasi nilai agunan dari survei" required>
                            <?php if (!empty($agunan)): ?>
                                <small class="text-muted">Nilai taksasi sebelumnya: Rp <?= number_format(array_sum(array_column($agunan, 'nilai_taksasi')), 0, ',', '.') ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lingkungan Sekitar</label>
                            <textarea name="lingkungan_sekitar" class="form-control" rows="2"
                                      placeholder="Kondisi lingkungan usaha/tempat tinggal"><?= htmlspecialchars($survei['lingkungan_sekitar'] ?? ($old['lingkungan_sekitar'] ?? '')) ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Catatan Survei</label>
                            <textarea name="catatan_survei" class="form-control" rows="4"
                                      placeholder="Catatan lengkap hasil survei lapangan..."><?= htmlspecialchars($survei['catatan_survei'] ?? ($old['catatan_survei'] ?? '')) ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto Survei (Multiple)</label>
                            <input type="file" name="foto_survei[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">Upload foto lokasi usaha, agunan, dll (Maks 5 foto)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rekomendasi Survei <span class="text-danger">*</span></label>
                            <select name="rekomendasi_survei" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="layak" <?= $rekomendasiSurvei == 'layak' ? 'selected' : '' ?>>Layak</option>
                                <option value="tidak_layak" <?= $rekomendasiSurvei == 'tidak_layak' ? 'selected' : '' ?>>Tidak Layak</option>
                                <option value="perlu_pertimbangan" <?= $rekomendasiSurvei == 'perlu_pertimbangan' ? 'selected' : '' ?>>Perlu Pertimbangan</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Agunan Preview -->
                    <?php if (!empty($agunan)): ?>
                    <h6 class="fw-bold mb-3">Daftar Agunan yang Diajukan:</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama Agunan</th>
                                    <th>Lokasi</th>
                                    <th>Nilai Taksasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agunan as $ag): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= ucfirst($ag['jenis_agunan']) ?></span></td>
                                    <td><?= htmlspecialchars($ag['nama_agunan']) ?></td>
                                    <td><?= htmlspecialchars($ag['lokasi_agunan']) ?></td>
                                    <td>Rp <?= number_format($ag['nilai_taksasi'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>petugas/survei" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-2"></i>Simpan Laporan Survei
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Survei Lapangan';
$pageSubtitle = 'Input hasil survei #' . $pengajuan['no_pengajuan'];

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
