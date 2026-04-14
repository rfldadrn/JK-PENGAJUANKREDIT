<?php
ob_start();
?>

<!-- Profile Nasabah -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Profil Nasabah</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); endif; ?>

                <?php if (empty($nasabah['no_nik'])): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Profil Belum Lengkap!</strong> Lengkapi profil Anda untuk dapat mengajukan kredit.
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Data Pribadi</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="no_nik" class="form-control"
                                   value="<?= $nasabah['no_nik'] ?? ($old['no_nik'] ?? '') ?>"
                                   maxlength="16" placeholder="16 digit NIK" required>
                            <?php if (isset($errors['no_nik'])): ?>
                                <div class="text-danger small mt-1"><?= $errors['no_nik'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NPWP</label>
                            <input type="text" name="no_npwp" class="form-control"
                                   value="<?= $nasabah['no_npwp'] ?? ($old['no_npwp'] ?? '') ?>"
                                   placeholder="Nomor NPWP (opsional)">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control"
                                   value="<?= $nasabah['tempat_lahir'] ?? ($old['tempat_lahir'] ?? '') ?>"
                                   placeholder="Kota kelahiran" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                   value="<?= $nasabah['tanggal_lahir'] ?? ($old['tanggal_lahir'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="L" <?= (isset($nasabah) && $nasabah['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= (isset($nasabah) && $nasabah['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Perkawinan</label>
                            <select name="status_perkawinan" class="form-select">
                                <option value="">Pilih</option>
                                <option value="belum_kawin" <?= (isset($nasabah) && $nasabah['status_perkawinan'] == 'belum_kawin') ? 'selected' : '' ?>>Belum Kawin</option>
                                <option value="kawin" <?= (isset($nasabah) && $nasabah['status_perkawinan'] == 'kawin') ? 'selected' : '' ?>>Kawin</option>
                                <option value="cerai" <?= (isset($nasabah) && $nasabah['status_perkawinan'] == 'cerai') ? 'selected' : '' ?>>Cerai</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2 mt-4">Alamat</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Alamat Sesuai KTP <span class="text-danger">*</span></label>
                            <textarea name="alamat_ktp" class="form-control" rows="3"
                                      placeholder="Alamat lengkap sesuai KTP" required><?= $nasabah['alamat_ktp'] ?? ($old['alamat_ktp'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat Domisili</label>
                            <textarea name="alamat_domisili" class="form-control" rows="2"
                                      placeholder="Alamat domisili saat ini (kosongkan jika sama dengan KTP)"><?= $nasabah['alamat_domisili'] ?? ($old['alamat_domisili'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan" class="form-control"
                                   value="<?= $nasabah['kelurahan'] ?? ($old['kelurahan'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control"
                                   value="<?= $nasabah['kecamatan'] ?? ($old['kecamatan'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota / Kabupaten</label>
                            <input type="text" name="kota_kabupaten" class="form-control"
                                   value="<?= $nasabah['kota_kabupaten'] ?? ($old['kota_kabupaten'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control"
                                   value="<?= $nasabah['provinsi'] ?? ($old['provinsi'] ?? '') ?>">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2 mt-4">Informasi Pekerjaan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" name="pekerjaan" class="form-control"
                                   value="<?= $nasabah['pekerjaan'] ?? ($old['pekerjaan'] ?? '') ?>"
                                   placeholder="Contoh: Wiraswasta, Karyawan Swasta" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Perusahaan / Usaha</label>
                            <input type="text" name="nama_perusahaan" class="form-control"
                                   value="<?= $nasabah['nama_perusahaan'] ?? ($old['nama_perusahaan'] ?? '') ?>"
                                   placeholder="Nama tempat bekerja">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Penghasilan Bulanan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="penghasilan_bulanan" class="form-control"
                                   value="<?= $nasabah['penghasilan_bulanan'] ?? ($old['penghasilan_bulanan'] ?? '') ?>"
                                   placeholder="Penghasilan bersih per bulan" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>nasabah/dashboard" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Profil Nasabah';
$pageSubtitle = 'Lengkapi profil untuk mengajukan kredit';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
