<?php
ob_start();
?>

<!-- Step 2: Data Agunan -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Progress Stepper -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="step-item completed">
                        <div class="step-number"><i class="bi bi-check"></i></div>
                        <div class="step-label">Data Pinjaman</div>
                    </div>
                    <div class="step-item active">
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

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-2-circle me-2"></i>Data Agunan / Jaminan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Agunan adalah jaminan yang diberikan untuk memastikan pengembalian kredit. Anda bisa menambahkan lebih dari satu agunan.
                </div>

                <!-- Form Tambah Agunan -->
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Agunan</label>
                            <select name="jenis_agunan" class="form-select" required>
                                <option value="">Pilih Jenis Agunan</option>
                                <option value="tanah">Tanah</option>
                                <option value="bangunan">Bangunan / Rumah</option>
                                <option value="kendaraan">Kendaraan (Mobil/Motor)</option>
                                <option value="tabungan">Tabungan / Deposito</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama / Deskripsi Agunan</label>
                            <input type="text" name="nama_agunan" class="form-control"
                                   placeholder="Contoh: Sertifikat Tanah, BPKB Mobil, dll" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Sertifikat / BPKB</label>
                            <input type="text" name="no_sertifikat" class="form-control"
                                   placeholder="Nomor sertifikat atau dokumen kepemilikan">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Atas Nama</label>
                            <input type="text" name="atas_nama" class="form-control"
                                   placeholder="Nama pemilik agunan" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nilai Pasar (Rp)</label>
                            <input type="number" name="nilai_pasar" class="form-control"
                                   placeholder="Perkiraan nilai pasar" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nilai Taksasi Bank (Rp)</label>
                            <input type="number" name="nilai_taksasi" class="form-control"
                                   placeholder="Nilai taksasi dari bank" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Luas (m²)</label>
                            <input type="text" name="luas" class="form-control"
                                   placeholder="Luas tanah/bangunan">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Lokasi Agunan</label>
                            <textarea name="lokasi_agunan" class="form-control" rows="2"
                                      placeholder="Alamat lengkap lokasi agunan"></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Agunan
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Daftar Agunan -->
                <h6 class="fw-bold mb-3">Daftar Agunan yang Ditambahkan</h6>
                <?php if (empty($agunan)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Belum ada agunan ditambahkan. Silakan tambahkan minimal 1 agunan untuk melanjutkan.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama Agunan</th>
                                    <th>Atas Nama</th>
                                    <th>Nilai Taksasi</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agunan as $ag): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= ucfirst($ag['jenis_agunan']) ?></span></td>
                                    <td><?= htmlspecialchars($ag['nama_agunan']) ?></td>
                                    <td><?= htmlspecialchars($ag['atas_nama']) ?></td>
                                    <td>Rp <?= number_format($ag['nilai_taksasi'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars(substr($ag['lokasi_agunan'], 0, 50)) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>nasabah/deleteAgunan/<?= $ag['id_agunan'] ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Hapus agunan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-success mt-3">
                        <strong>Total Nilai Agunan:</strong> Rp <?= number_format(array_sum(array_column($agunan, 'nilai_taksasi')), 0, ',', '.') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navigation -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>nasabah/step1/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <?php if (!empty($agunan)): ?>
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="action" value="next">
                        <button type="submit" class="btn btn-primary">
                            Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                    <?php else: ?>
                    <button type="button" class="btn btn-primary" disabled>
                        Tambahkan Agunan Terlebih Dahulu
                    </button>
                    <?php endif; ?>
                </div>
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
</style>

<?php
$content = ob_get_clean();

$pageTitle = 'Ajukan Kredit Baru';
$pageSubtitle = 'Step 2: Data agunan';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
