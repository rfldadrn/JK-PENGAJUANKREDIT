<?php
ob_start();
?>

<!-- Step 4: Review & Submit -->
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
                    <div class="step-item completed">
                        <div class="step-number"><i class="bi bi-check"></i></div>
                        <div class="step-label">Data Agunan</div>
                    </div>
                    <div class="step-item completed">
                        <div class="step-number"><i class="bi bi-check"></i></div>
                        <div class="step-label">Upload Dokumen</div>
                    </div>
                    <div class="step-item active">
                        <div class="step-number">4</div>
                        <div class="step-label">Review & Submit</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Periksa Kembali Data Anda!</strong> Pastikan semua informasi yang Anda masukkan sudah benar sebelum mengirim pengajuan.
        </div>

        <!-- Data Pengajuan -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Data Pengajuan Kredit</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Nomor Pengajuan</label>
                        <div class="fw-bold"><?= $pengajuan['no_pengajuan'] ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Jenis Kredit</label>
                        <div class="fw-bold"><?= $pengajuan['nama_kredit'] ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Jumlah Pinjaman</label>
                        <div class="fw-bold text-primary">Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tenor</label>
                        <div class="fw-bold"><?= $pengajuan['tenor'] ?> Bulan</div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Tujuan Kredit</label>
                        <div><?= nl2br(htmlspecialchars($pengajuan['tujuan_kredit'])) ?></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Sumber Pengembalian</label>
                        <div><?= nl2br(htmlspecialchars($pengajuan['sumber_pengembalian'])) ?></div>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>nasabah/step1/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>

        <!-- Data Nasabah -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Data Pemohon</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Nama Lengkap</label>
                        <div class="fw-bold"><?= htmlspecialchars($pengajuan['nama_lengkap']) ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">NIK</label>
                        <div class="fw-bold"><?= $pengajuan['no_nik'] ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Email</label>
                        <div><?= htmlspecialchars($pengajuan['email']) ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">No. HP</label>
                        <div><?= $pengajuan['no_hp'] ?></div>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="text-muted small">Alamat</label>
                        <div><?= htmlspecialchars($pengajuan['alamat_ktp']) ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Pekerjaan</label>
                        <div><?= htmlspecialchars($pengajuan['pekerjaan']) ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Penghasilan Bulanan</label>
                        <div class="fw-bold">Rp <?= number_format($pengajuan['penghasilan_bulanan'], 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Agunan -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-shield-check me-2"></i>Data Agunan (<?= count($agunan) ?> item)</h6>
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
                            <tr class="table-light">
                                <td colspan="3" class="fw-bold text-end">Total Nilai Agunan:</td>
                                <td class="fw-bold text-success">Rp <?= number_format(array_sum(array_column($agunan, 'nilai_taksasi')), 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    <a href="<?= BASE_URL ?>nasabah/step2/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h6 class="mb-0"><i class="bi bi-file-earmark-arrow-up me-2"></i>Dokumen Terlampir (<?= count($dokumen) ?> file)</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($dokumen as $dok): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                <strong><?= htmlspecialchars($dok['jenis_dokumen']) ?></strong>
                                <div class="small text-muted"><?= htmlspecialchars($dok['nama_file']) ?> (<?= number_format($dok['ukuran_file'], 0) ?> KB)</div>
                            </div>
                            <span class="badge bg-success">Uploaded</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="p-3">
                    <a href="<?= BASE_URL ?>nasabah/step3/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Pernyataan -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="agreement" required>
                    <label class="form-check-label" for="agreement">
                        Saya menyatakan bahwa data yang saya masukkan adalah <strong>benar dan dapat dipertanggungjawabkan</strong>.
                        Saya bersedia untuk diproses lebih lanjut oleh pihak bank dan memenuhi seluruh persyaratan yang ditetapkan.
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= BASE_URL ?>nasabah/step3/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <form method="POST" action="" onsubmit="return confirm('Apakah Anda yakin ingin mengirim pengajuan ini?')">
                        <button type="submit" class="btn btn-success btn-lg" id="btnSubmit" disabled>
                            <i class="bi bi-send me-2"></i>Kirim Pengajuan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('agreement').addEventListener('change', function() {
    document.getElementById('btnSubmit').disabled = !this.checked;
});
</script>

<style>
.step-item {text-align: center; flex: 1;}
.step-number {
    width: 40px; height: 40px; border-radius: 50%; background: #e9ecef; color: #6c757d;
    display: inline-flex; align-items: center; justify-content: center; font-weight: 600; margin-bottom: 8px;
}
.step-item.active .step-number {background: var(--primary-color, #003d7a); color: #fff;}
.step-item.completed .step-number {background: #28a745; color: #fff;}
.step-label {font-size: 13px; color: #6c757d;}
.step-item.active .step-label {color: #000; font-weight: 600;}
</style>

<?php
$content = ob_get_clean();

$pageTitle = 'Ajukan Kredit Baru';
$pageSubtitle = 'Step 4: Review dan submit pengajuan';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
