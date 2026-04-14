<?php
ob_start();
?>

<!-- Step 1: Pilih Jenis Kredit & Data Pinjaman -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Progress Stepper -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between position-relative">
                    <div class="step-item active">
                        <div class="step-number">1</div>
                        <div class="step-label">Data Pinjaman</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-label">Data Agunan</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-label">Upload Dokumen</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-label">Review & Submit</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-1-circle me-2"></i>Data Pengajuan Pinjaman</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <!-- Pilih Jenis Kredit -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Jenis Kredit</label>
                        <div class="row g-3">
                            <?php foreach ($jenisKredit as $jk): ?>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="id_jenis_kredit"
                                       id="kredit_<?= $jk['id_jenis_kredit'] ?>"
                                       value="<?= $jk['id_jenis_kredit'] ?>"
                                       <?= (isset($pengajuan) && $pengajuan['id_jenis_kredit'] == $jk['id_jenis_kredit']) ? 'checked' : '' ?>
                                       onchange="updateRangeInfo(this)">
                                <label class="card kredit-card" for="kredit_<?= $jk['id_jenis_kredit'] ?>">
                                    <div class="card-body text-center">
                                        <i class="bi bi-credit-card display-4 text-primary mb-3"></i>
                                        <h6 class="fw-bold"><?= $jk['nama_kredit'] ?></h6>
                                        <p class="text-muted small mb-2"><?= substr($jk['deskripsi'], 0, 80) ?>...</p>
                                        <hr>
                                        <div class="small">
                                            <div><strong>Plafond:</strong> Rp <?= number_format($jk['plafond_min'], 0, ',', '.') ?> - <?= number_format($jk['plafond_max'], 0, ',', '.') ?></div>
                                            <div><strong>Tenor:</strong> <?= $jk['tenor_min'] ?>-<?= $jk['tenor_max'] ?> bulan</div>
                                            <div><strong>Bunga:</strong> <?= $jk['bunga_per_tahun'] ?>% / tahun</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (isset($errors['id_jenis_kredit'])): ?>
                            <div class="text-danger small mt-2"><?= $errors['id_jenis_kredit'] ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <!-- Data Pinjaman -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Pinjaman (Rp)</label>
                            <input type="number" name="jumlah_pinjaman" class="form-control form-control-lg"
                                   value="<?= $pengajuan['jumlah_pinjaman'] ?? ($old['jumlah_pinjaman'] ?? '') ?>"
                                   placeholder="Contoh: 50000000" required>
                            <?php if (isset($errors['jumlah_pinjaman'])): ?>
                                <div class="text-danger small mt-1"><?= $errors['jumlah_pinjaman'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tenor (Bulan)</label>
                            <select name="tenor" class="form-select form-select-lg" required>
                                <option value="">Pilih Tenor</option>
                                <?php for ($i = 6; $i <= 60; $i += 6): ?>
                                <option value="<?= $i ?>" <?= (isset($pengajuan) && $pengajuan['tenor'] == $i) ? 'selected' : '' ?>>
                                    <?= $i ?> Bulan
                                </option>
                                <?php endfor; ?>
                            </select>
                            <?php if (isset($errors['tenor'])): ?>
                                <div class="text-danger small mt-1"><?= $errors['tenor'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Tujuan Penggunaan Kredit</label>
                            <textarea name="tujuan_kredit" class="form-control" rows="3"
                                      placeholder="Jelaskan tujuan penggunaan kredit..." required><?= $pengajuan['tujuan_kredit'] ?? ($old['tujuan_kredit'] ?? '') ?></textarea>
                            <?php if (isset($errors['tujuan_kredit'])): ?>
                                <div class="text-danger small mt-1"><?= $errors['tujuan_kredit'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Sumber Pengembalian Dana</label>
                            <textarea name="sumber_pengembalian" class="form-control" rows="3"
                                      placeholder="Jelaskan sumber dana untuk pengembalian kredit..." required><?= $pengajuan['sumber_pengembalian'] ?? ($old['sumber_pengembalian'] ?? '') ?></textarea>
                            <?php if (isset($errors['sumber_pengembalian'])): ?>
                                <div class="text-danger small mt-1"><?= $errors['sumber_pengembalian'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan_nasabah" class="form-control" rows="2"
                                      placeholder="Catatan atau informasi tambahan..."><?= $pengajuan['catatan_nasabah'] ?? ($old['catatan_nasabah'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>nasabah/dashboard" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.step-item {
    text-align: center;
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 8px;
}

.step-item.active .step-number {
    background: var(--primary-color, #003d7a);
    color: #fff;
}

.step-item.completed .step-number {
    background: #28a745;
    color: #fff;
}

.step-label {
    font-size: 13px;
    color: #6c757d;
}

.step-item.active .step-label {
    color: #000;
    font-weight: 600;
}

.kredit-card {
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid #dee2e6;
}

.kredit-card:hover {
    border-color: var(--primary-color, #003d7a);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-check:checked + .kredit-card {
    border-color: var(--primary-color, #003d7a);
    background: rgba(0, 61, 122, 0.05);
}
</style>

<?php
$content = ob_get_clean();

$pageTitle = 'Ajukan Kredit Baru';
$pageSubtitle = 'Step 1: Pilih jenis kredit dan data pinjaman';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
