<?php
$title = 'Laporan';
ob_start();
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Laporan</h2>
        <div>
            <span class="text-muted">Dashboard / Laporan</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Nasabah</p>
                            <h3 class="mb-0"><?= number_format($stats['total_nasabah'] ?? 0) ?></h3>
                        </div>
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Pengajuan</p>
                            <h3 class="mb-0"><?= number_format($stats['total_pengajuan'] ?? 0) ?></h3>
                        </div>
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-file-earmark-text-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Petugas</p>
                            <h3 class="mb-0"><?= number_format($stats['total_petugas'] ?? 0) ?></h3>
                        </div>
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-person-badge-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Disetujui</p>
                            <h3 class="mb-0"><?= number_format($stats['pengajuan_disetujui'] ?? 0) ?></h3>
                        </div>
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Report Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="" id="filterForm">
                <div class="row g-3">
                    <!-- Jenis Laporan -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenis Laporan</label>
                        <select name="jenis_laporan" class="form-select" onchange="updateFilters()">
                            <option value="nasabah" <?= ($filters['jenis_laporan'] ?? '') == 'nasabah' ? 'selected' : '' ?>>Data Nasabah</option>
                            <option value="pengajuan" <?= ($filters['jenis_laporan'] ?? '') == 'pengajuan' ? 'selected' : '' ?>>Data Pengajuan Kredit</option>
                            <option value="petugas" <?= ($filters['jenis_laporan'] ?? '') == 'petugas' ? 'selected' : '' ?>>Data Petugas Bank</option>
                            <option value="status" <?= ($filters['jenis_laporan'] ?? '') == 'status' ? 'selected' : '' ?>>Status Pengajuan (Disetujui/Ditolak)</option>
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?? '' ?>">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?? '' ?>">
                    </div>

                    <!-- Dynamic Filters based on Report Type -->
                    <?php if ((($filters['jenis_laporan'] ?? '') == 'pengajuan') || (($filters['jenis_laporan'] ?? '') == 'status')): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenis Kredit</label>
                        <select name="jenis_kredit" class="form-select">
                            <option value="">Semua Jenis</option>
                            <?php foreach (($jenisKredit ?? []) as $jk): ?>
                            <option value="<?= $jk['id_jenis_kredit'] ?>" <?= (($filters['jenis_kredit'] ?? '') == $jk['id_jenis_kredit']) ? 'selected' : '' ?>>
                                <?= $jk['nama_kredit'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if (($filters['jenis_laporan'] ?? '') == 'pengajuan'): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Pengajuan</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="diajukan" <?= (($filters['status'] ?? '') == 'diajukan') ? 'selected' : '' ?>>Diajukan</option>
                            <option value="verifikasi" <?= (($filters['status'] ?? '') == 'verifikasi') ? 'selected' : '' ?>>Verifikasi</option>
                            <option value="survei" <?= (($filters['status'] ?? '') == 'survei') ? 'selected' : '' ?>>Survei</option>
                            <option value="analisis" <?= (($filters['status'] ?? '') == 'analisis') ? 'selected' : '' ?>>Analisis</option>
                            <option value="menunggu_keputusan" <?= (($filters['status'] ?? '') == 'menunggu_keputusan') ? 'selected' : '' ?>>Menunggu Keputusan</option>
                            <option value="disetujui" <?= (($filters['status'] ?? '') == 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= (($filters['status'] ?? '') == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if (($filters['jenis_laporan'] ?? '') == 'status'): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Keputusan</label>
                        <select name="keputusan" class="form-select">
                            <option value="">Semua Keputusan</option>
                            <option value="disetujui" <?= (($filters['keputusan'] ?? '') == 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= (($filters['keputusan'] ?? '') == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                            <option value="revisi" <?= (($filters['keputusan'] ?? '') == 'revisi') ? 'selected' : '' ?>>Revisi</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if (($filters['jenis_laporan'] ?? '') == 'petugas'): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="petugas" <?= (($filters['role'] ?? '') == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                            <option value="analis" <?= (($filters['role'] ?? '') == 'analis') ? 'selected' : '' ?>>Analis</option>
                            <option value="pimpinan" <?= (($filters['role'] ?? '') == 'pimpinan') ? 'selected' : '' ?>>Pimpinan</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if (($filters['jenis_laporan'] ?? '') == 'nasabah'): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" placeholder="Filter berdasarkan pekerjaan" value="<?= $filters['pekerjaan'] ?? '' ?>">
                    </div>
                    <?php endif; ?>

                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pencarian</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari..." value="<?= $filters['search'] ?? '' ?>">
                    </div>

                    <!-- Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill me-2"></i>Tampilkan Laporan
                        </button>
                        <a href="<?= BASE_URL ?>admin/laporan" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Buttons & Report Table -->
    <?php if (!empty($data)): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hasil Laporan</h5>
                <div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportPdf()">
                        <i class="bi bi-file-pdf-fill me-1"></i>Export PDF
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="exportExcel()">
                        <i class="bi bi-file-excel-fill me-1"></i>Export Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <?php if ($filters['jenis_laporan'] == 'nasabah'): ?>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Pekerjaan</th>
                            <th>Penghasilan</th>
                            <th class="text-center">Total Pengajuan</th>
                            <th class="text-center">Disetujui</th>
                        </tr>
                        <?php elseif ($filters['jenis_laporan'] == 'pengajuan'): ?>
                        <tr>
                            <th>No</th>
                            <th>No. Pengajuan</th>
                            <th>Nasabah</th>
                            <th>Produk</th>
                            <th>Jumlah Pinjaman</th>
                            <th>Tenor</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                        <?php elseif ($filters['jenis_laporan'] == 'petugas'): ?>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>No. Telepon</th>
                            <th class="text-center">Total Tugas</th>
                            <th>Terdaftar</th>
                        </tr>
                        <?php elseif ($filters['jenis_laporan'] == 'status'): ?>
                        <tr>
                            <th>No</th>
                            <th>No. Pengajuan</th>
                            <th>Nasabah</th>
                            <th>Produk</th>
                            <th>Jumlah Pinjaman</th>
                            <th class="text-center">Keputusan</th>
                            <th>Tanggal Keputusan</th>
                            <th>Pimpinan</th>
                        </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $index => $row): ?>
                        <?php if ($filters['jenis_laporan'] == 'nasabah'): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['nik'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['no_telepon'] ?></td>
                            <td><?= $row['pekerjaan'] ?></td>
                            <td>Rp <?= number_format($row['penghasilan_per_bulan'], 0, ',', '.') ?></td>
                            <td class="text-center"><span class="badge bg-info"><?= $row['total_pengajuan'] ?></span></td>
                            <td class="text-center"><span class="badge bg-success"><?= $row['pengajuan_disetujui'] ?></span></td>
                        </tr>
                        <?php elseif ($filters['jenis_laporan'] == 'pengajuan'): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $row['no_pengajuan'] ?></td>
                            <td><?= $row['nama_nasabah'] ?></td>
                            <td><?= $row['nama_produk'] ?></td>
                            <td>Rp <?= number_format($row['jumlah_pinjaman'], 0, ',', '.') ?></td>
                            <td><?= $row['tenor'] ?> bulan</td>
                            <td><span class="badge bg-<?= $row['status_pengajuan'] == 'disetujui' ? 'success' : ($row['status_pengajuan'] == 'ditolak' ? 'danger' : 'warning') ?>"><?= ucfirst($row['status_pengajuan']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                        </tr>
                        <?php elseif ($filters['jenis_laporan'] == 'petugas'): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><span class="badge bg-primary"><?= ucfirst($row['role']) ?></span></td>
                            <td><?= $row['no_telepon'] ?></td>
                            <td class="text-center"><span class="badge bg-info"><?= $row['total_verifikasi'] + $row['total_survei'] + $row['total_analisis'] + $row['total_persetujuan'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php elseif ($filters['jenis_laporan'] == 'status'): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $row['no_pengajuan'] ?></td>
                            <td><?= $row['nama_nasabah'] ?></td>
                            <td><?= $row['nama_produk'] ?></td>
                            <td>Rp <?= number_format($row['jumlah_pinjaman'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?= $row['keputusan'] == 'disetujui' ? 'success' : 'danger' ?>">
                                    <?= strtoupper($row['keputusan']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal_keputusan'])) ?></td>
                            <td><?= $row['nama_pimpinan'] ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (count($data) > 0): ?>
            <div class="mt-3">
                <small class="text-muted">Total data: <?= count($data) ?> baris</small>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle me-2"></i>
        Tidak ada data untuk ditampilkan. Silakan pilih filter dan klik "Tampilkan Laporan".
    </div>
    <?php endif; ?>
</div>

<script>
function updateFilters() {
    // Submit form when jenis laporan changes
    document.getElementById('filterForm').submit();
}

function exportPdf() {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    window.open('<?= BASE_URL ?>admin/exportPdf?' + params, '_blank');
}

function exportExcel() {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    window.location.href = '<?= BASE_URL ?>admin/exportExcel?' + params;
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/dashboard.php';
?>

