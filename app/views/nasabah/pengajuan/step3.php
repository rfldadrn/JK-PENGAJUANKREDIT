<?php
ob_start();
?>

<!-- Step 3: Upload Dokumen -->
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
                    <div class="step-item active">
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
                <h5 class="mb-0"><i class="bi bi-3-circle me-2"></i>Upload Dokumen Persyaratan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Upload dokumen sesuai persyaratan jenis kredit <strong><?= htmlspecialchars($pengajuan['nama_kredit']) ?></strong>.
                    Format: PDF, JPG, PNG (Maks. 5MB per file)
                </div>

                <!-- Checklist Dokumen -->
                <h6 class="fw-bold mb-3">Dokumen yang Diperlukan:</h6>
                <div class="row">
                    <?php foreach ($syaratDokumen as $syarat): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($syarat) ?></h6>
                                        <?php
                                        $uploaded = false;
                                        foreach ($dokumenUploaded as $dok) {
                                            if ($dok['jenis_dokumen'] === $syarat) {
                                                $uploaded = true;
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php if ($uploaded): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Sudah Upload
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-exclamation-circle me-1"></i>Belum Upload
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if (!$uploaded): ?>
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="jenis_dokumen" value="<?= htmlspecialchars($syarat) ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </div>
                                </form>
                                <?php else: ?>
                                <?php foreach ($dokumenUploaded as $dok): ?>
                                    <?php if ($dok['jenis_dokumen'] === $syarat): ?>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-file-earmark me-1"></i><?= htmlspecialchars($dok['nama_file']) ?>
                                            (<?= number_format($dok['ukuran_file'], 0) ?> KB)
                                        </small>
                                        <a href="<?= BASE_URL ?>nasabah/deleteDokumen/<?= $dok['id_dokumen'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Hapus dokumen ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                    <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($dokumenUploaded)): ?>
                <hr class="my-4">
                <h6 class="fw-bold mb-3">Dokumen yang Sudah Diunggah:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Dokumen</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dokumenUploaded as $dok): ?>
                            <tr>
                                <td><?= htmlspecialchars($dok['jenis_dokumen']) ?></td>
                                <td><i class="bi bi-file-earmark-pdf text-danger me-1"></i><?= htmlspecialchars($dok['nama_file']) ?></td>
                                <td><?= number_format($dok['ukuran_file'], 0) ?> KB</td>
                                <td><?= date('d/m/Y H:i', strtotime($dok['tanggal_upload'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>nasabah/deleteDokumen/<?= $dok['id_dokumen'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Hapus dokumen ini?')">
                                        <i class="bi bi-trash"></i>
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

        <!-- Navigation -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>nasabah/step2/<?= $pengajuan['id_pengajuan'] ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="action" value="next">
                        <button type="submit" class="btn btn-primary">
                            Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
$pageSubtitle = 'Step 3: Upload dokumen persyaratan';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
