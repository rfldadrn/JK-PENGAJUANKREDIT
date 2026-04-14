-- Database: db_pengajuan_kredit
-- Sistem Informasi Pengajuan Kredit BRI Lubuk Sikaping

CREATE DATABASE IF NOT EXISTS db_pengajuan_kredit DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_pengajuan_kredit;

-- Table: tb_users
CREATE TABLE tb_users (
    id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    role ENUM('nasabah', 'petugas', 'analis', 'pimpinan', 'admin') NOT NULL DEFAULT 'nasabah',
    status_akun ENUM('aktif', 'nonaktif', 'pending') NOT NULL DEFAULT 'pending',
    foto_profil VARCHAR(255) DEFAULT NULL,
    token_reset VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status_akun)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_nasabah
CREATE TABLE tb_nasabah (
    id_nasabah INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    no_nik VARCHAR(16) UNIQUE DEFAULT NULL,
    tempat_lahir VARCHAR(100) DEFAULT NULL,
    tanggal_lahir DATE DEFAULT NULL,
    jenis_kelamin ENUM('L', 'P') DEFAULT NULL,
    status_perkawinan ENUM('belum_kawin', 'kawin', 'cerai') DEFAULT NULL,
    alamat_ktp TEXT DEFAULT NULL,
    alamat_domisili TEXT DEFAULT NULL,
    kelurahan VARCHAR(100) DEFAULT NULL,
    kecamatan VARCHAR(100) DEFAULT NULL,
    kota_kabupaten VARCHAR(100) DEFAULT NULL,
    provinsi VARCHAR(100) DEFAULT NULL,
    pekerjaan VARCHAR(100) DEFAULT NULL,
    nama_perusahaan VARCHAR(150) DEFAULT NULL,
    penghasilan_bulanan DECIMAL(15,2) DEFAULT 0,
    no_npwp VARCHAR(20) DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (id_user) REFERENCES tb_users(id_user) ON DELETE CASCADE,
    INDEX idx_nik (no_nik),
    INDEX idx_user (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_jenis_kredit
CREATE TABLE tb_jenis_kredit (
    id_jenis_kredit INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kredit VARCHAR(100) NOT NULL,
    kode_kredit VARCHAR(20) NOT NULL UNIQUE,
    deskripsi TEXT DEFAULT NULL,
    plafond_min DECIMAL(15,2) NOT NULL DEFAULT 0,
    plafond_max DECIMAL(15,2) NOT NULL DEFAULT 0,
    bunga_per_tahun DECIMAL(5,2) NOT NULL DEFAULT 0,
    tenor_min INT NOT NULL DEFAULT 6,
    tenor_max INT NOT NULL DEFAULT 60,
    syarat_dokumen TEXT DEFAULT NULL COMMENT 'JSON array',
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at DATETIME NOT NULL,
    INDEX idx_kode (kode_kredit),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_pengajuan_kredit
CREATE TABLE tb_pengajuan_kredit (
    id_pengajuan INT(11) AUTO_INCREMENT PRIMARY KEY,
    no_pengajuan VARCHAR(30) NOT NULL UNIQUE,
    id_nasabah INT(11) NOT NULL,
    id_jenis_kredit INT(11) NOT NULL,
    jumlah_pinjaman DECIMAL(15,2) NOT NULL DEFAULT 0,
    tenor INT NOT NULL COMMENT 'Dalam bulan',
    tujuan_kredit TEXT DEFAULT NULL,
    sumber_pengembalian TEXT DEFAULT NULL,
    status_pengajuan ENUM('draft', 'diajukan', 'verifikasi', 'survei', 'analisis', 'menunggu_keputusan', 'disetujui', 'ditolak', 'revisi', 'dicairkan') NOT NULL DEFAULT 'draft',
    tanggal_pengajuan DATETIME DEFAULT NULL,
    tanggal_keputusan DATETIME DEFAULT NULL,
    catatan_nasabah TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (id_nasabah) REFERENCES tb_nasabah(id_nasabah) ON DELETE CASCADE,
    FOREIGN KEY (id_jenis_kredit) REFERENCES tb_jenis_kredit(id_jenis_kredit) ON DELETE RESTRICT,
    INDEX idx_no_pengajuan (no_pengajuan),
    INDEX idx_nasabah (id_nasabah),
    INDEX idx_status (status_pengajuan),
    INDEX idx_tanggal (tanggal_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_dokumen
CREATE TABLE tb_dokumen (
    id_dokumen INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    jenis_dokumen VARCHAR(100) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    path_file VARCHAR(255) NOT NULL,
    ukuran_file INT NOT NULL COMMENT 'Dalam KB',
    tipe_file VARCHAR(50) NOT NULL,
    status_dokumen ENUM('belum_diverifikasi', 'valid', 'tidak_valid') NOT NULL DEFAULT 'belum_diverifikasi',
    catatan_verifikasi TEXT DEFAULT NULL,
    diverifikasi_oleh INT(11) DEFAULT NULL,
    tanggal_upload DATETIME NOT NULL,
    tanggal_verifikasi DATETIME DEFAULT NULL,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (diverifikasi_oleh) REFERENCES tb_users(id_user) ON DELETE SET NULL,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_status (status_dokumen)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_agunan
CREATE TABLE tb_agunan (
    id_agunan INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    jenis_agunan ENUM('tanah', 'bangunan', 'kendaraan', 'tabungan', 'lainnya') NOT NULL,
    nama_agunan VARCHAR(200) NOT NULL,
    no_sertifikat VARCHAR(100) DEFAULT NULL,
    atas_nama VARCHAR(100) DEFAULT NULL,
    nilai_pasar DECIMAL(15,2) NOT NULL DEFAULT 0,
    nilai_taksasi DECIMAL(15,2) NOT NULL DEFAULT 0,
    lokasi_agunan TEXT DEFAULT NULL,
    luas VARCHAR(50) DEFAULT NULL COMMENT 'm2',
    dokumen_agunan TEXT DEFAULT NULL COMMENT 'JSON array paths',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    INDEX idx_pengajuan (id_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_verifikasi
CREATE TABLE tb_verifikasi (
    id_verifikasi INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_petugas INT(11) NOT NULL,
    kelengkapan_dokumen ENUM('lengkap', 'tidak_lengkap', 'perlu_perbaikan') NOT NULL,
    kesesuaian_data ENUM('sesuai', 'tidak_sesuai') NOT NULL,
    catatan_verifikasi TEXT DEFAULT NULL,
    rekomendasi ENUM('lanjut_survei', 'tolak', 'revisi') NOT NULL,
    tanggal_verifikasi DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_petugas (id_petugas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_survei
CREATE TABLE tb_survei (
    id_survei INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_petugas INT(11) NOT NULL,
    tanggal_survei DATE NOT NULL,
    alamat_survei TEXT NOT NULL,
    kondisi_usaha ENUM('baik', 'cukup', 'kurang') NOT NULL,
    omzet_usaha DECIMAL(15,2) NOT NULL DEFAULT 0,
    kondisi_agunan ENUM('baik', 'cukup', 'kurang') NOT NULL,
    nilai_agunan_estimasi DECIMAL(15,2) NOT NULL DEFAULT 0,
    lingkungan_sekitar TEXT DEFAULT NULL,
    catatan_survei TEXT DEFAULT NULL,
    foto_survei TEXT DEFAULT NULL COMMENT 'JSON array paths',
    rekomendasi_survei ENUM('layak', 'tidak_layak', 'perlu_pertimbangan') NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_analisis_kredit
CREATE TABLE tb_analisis_kredit (
    id_analisis INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_analis INT(11) NOT NULL,
    skor_karakter INT NOT NULL DEFAULT 0 COMMENT '0-100',
    skor_kapasitas INT NOT NULL DEFAULT 0 COMMENT '0-100',
    skor_modal INT NOT NULL DEFAULT 0 COMMENT '0-100',
    skor_agunan INT NOT NULL DEFAULT 0 COMMENT '0-100',
    skor_kondisi INT NOT NULL DEFAULT 0 COMMENT '0-100',
    skor_total DECIMAL(5,2) NOT NULL DEFAULT 0 COMMENT 'Rata-rata tertimbang',
    rasio_dsr DECIMAL(5,2) NOT NULL DEFAULT 0 COMMENT 'Debt Service Ratio %',
    plafond_rekomendasi DECIMAL(15,2) NOT NULL DEFAULT 0,
    tenor_rekomendasi INT NOT NULL DEFAULT 0,
    catatan_analisis TEXT DEFAULT NULL,
    kesimpulan ENUM('layak', 'tidak_layak', 'layak_dengan_syarat') NOT NULL,
    tanggal_analisis DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_analis) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_persetujuan
CREATE TABLE tb_persetujuan (
    id_persetujuan INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_pimpinan INT(11) NOT NULL,
    keputusan ENUM('disetujui', 'ditolak', 'revisi') NOT NULL,
    plafond_disetujui DECIMAL(15,2) DEFAULT 0,
    tenor_disetujui INT DEFAULT 0,
    bunga_disetujui DECIMAL(5,2) DEFAULT 0,
    angsuran_per_bulan DECIMAL(15,2) DEFAULT 0,
    alasan_keputusan TEXT DEFAULT NULL,
    syarat_pencairan TEXT DEFAULT NULL,
    tanggal_keputusan DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_pimpinan) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_notifikasi
CREATE TABLE tb_notifikasi (
    id_notif INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    id_pengajuan INT(11) DEFAULT NULL,
    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    jenis_notif ENUM('info', 'sukses', 'peringatan', 'error') NOT NULL DEFAULT 'info',
    status_baca TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=belum, 1=sudah',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_user) REFERENCES tb_users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE SET NULL,
    INDEX idx_user (id_user),
    INDEX idx_status (status_baca)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tb_log_aktivitas
CREATE TABLE tb_log_aktivitas (
    id_log INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    modul VARCHAR(100) NOT NULL,
    ip_address VARCHAR(50) NOT NULL,
    user_agent TEXT DEFAULT NULL,
    data_lama TEXT DEFAULT NULL COMMENT 'JSON',
    data_baru TEXT DEFAULT NULL COMMENT 'JSON',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (id_user) REFERENCES tb_users(id_user) ON DELETE CASCADE,
    INDEX idx_user (id_user),
    INDEX idx_modul (modul),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default jenis kredit
INSERT INTO tb_jenis_kredit (nama_kredit, kode_kredit, deskripsi, plafond_min, plafond_max, bunga_per_tahun, tenor_min, tenor_max, syarat_dokumen, status, created_at) VALUES
('KUR Mikro', 'KUR-MIKRO', 'Kredit Usaha Rakyat untuk usaha mikro dengan plafond hingga Rp 50 juta', 1000000, 50000000, 6.00, 6, 36, '["KTP", "KK", "NPWP", "Surat Usaha", "Foto Usaha"]', 'aktif', NOW()),
('KUR Kecil', 'KUR-KECIL', 'Kredit Usaha Rakyat untuk usaha kecil dengan plafond Rp 50 juta - Rp 500 juta', 50000000, 500000000, 6.00, 12, 48, '["KTP", "KK", "NPWP", "Surat Usaha", "Laporan Keuangan", "Sertifikat Agunan"]', 'aktif', NOW()),
('Kredit Konsumtif', 'KRD-KONSUMTIF', 'Kredit untuk kebutuhan konsumtif seperti renovasi rumah, pendidikan, kesehatan', 5000000, 200000000, 9.00, 12, 60, '["KTP", "KK", "NPWP", "Slip Gaji", "Rekening Koran"]', 'aktif', NOW());

-- Insert default admin user
INSERT INTO tb_users (nama_lengkap, email, password, no_hp, role, status_akun, created_at, updated_at) VALUES
('Administrator', 'admin@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin', 'aktif', NOW(), NOW()),
('Petugas AO', 'petugas@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'petugas', 'aktif', NOW(), NOW()),
('Analis Kredit', 'analis@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'analis', 'aktif', NOW(), NOW()),
('Pimpinan Cabang', 'pimpinan@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567893', 'pimpinan', 'aktif', NOW(), NOW());

-- Default password untuk semua user demo: password
