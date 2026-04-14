-- Database: db_pengajuan_kredit
-- Sistem Informasi Pengajuan Kredit BRI Lubuk Sikaping

CREATE DATABASE IF NOT EXISTS db_pengajuan_kredit;
USE db_pengajuan_kredit;

-- ============================================
-- Tabel: tb_users
-- ============================================
CREATE TABLE tb_users (
    id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(15),
    role ENUM('nasabah','petugas','analis','pimpinan','admin') NOT NULL DEFAULT 'nasabah',
    status_akun ENUM('aktif','nonaktif','pending') NOT NULL DEFAULT 'pending',
    foto_profil VARCHAR(255),
    token_reset VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status_akun)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_nasabah
-- ============================================
CREATE TABLE tb_nasabah (
    id_nasabah INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    no_nik VARCHAR(16) UNIQUE,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L','P'),
    status_perkawinan ENUM('belum_kawin','kawin','cerai'),
    alamat_ktp TEXT,
    alamat_domisili TEXT,
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    kota_kabupaten VARCHAR(100),
    provinsi VARCHAR(100),
    pekerjaan VARCHAR(100),
    nama_perusahaan VARCHAR(150),
    penghasilan_bulanan DECIMAL(15,2),
    no_npwp VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES tb_users(id_user) ON DELETE CASCADE,
    INDEX idx_nik (no_nik),
    INDEX idx_user (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_jenis_kredit
-- ============================================
CREATE TABLE tb_jenis_kredit (
    id_jenis_kredit INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kredit VARCHAR(100) NOT NULL,
    kode_kredit VARCHAR(20) NOT NULL UNIQUE,
    deskripsi TEXT,
    plafond_min DECIMAL(15,2) NOT NULL,
    plafond_max DECIMAL(15,2) NOT NULL,
    bunga_per_tahun DECIMAL(5,2) NOT NULL,
    tenor_min INT NOT NULL,
    tenor_max INT NOT NULL,
    syarat_dokumen TEXT,
    status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_kode (kode_kredit),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_pengajuan_kredit
-- ============================================
CREATE TABLE tb_pengajuan_kredit (
    id_pengajuan INT(11) AUTO_INCREMENT PRIMARY KEY,
    no_pengajuan VARCHAR(30) NOT NULL UNIQUE,
    id_nasabah INT(11) NOT NULL,
    id_jenis_kredit INT(11) NOT NULL,
    jumlah_pinjaman DECIMAL(15,2) NOT NULL,
    tenor INT NOT NULL,
    tujuan_kredit TEXT,
    sumber_pengembalian TEXT,
    status_pengajuan ENUM('draft','diajukan','verifikasi','survei','analisis','menunggu_keputusan','disetujui','ditolak','revisi','dicairkan') NOT NULL DEFAULT 'draft',
    tanggal_pengajuan DATETIME,
    tanggal_keputusan DATETIME,
    catatan_nasabah TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nasabah) REFERENCES tb_nasabah(id_nasabah) ON DELETE CASCADE,
    FOREIGN KEY (id_jenis_kredit) REFERENCES tb_jenis_kredit(id_jenis_kredit) ON DELETE RESTRICT,
    INDEX idx_no_pengajuan (no_pengajuan),
    INDEX idx_nasabah (id_nasabah),
    INDEX idx_status (status_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_dokumen
-- ============================================
CREATE TABLE tb_dokumen (
    id_dokumen INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    jenis_dokumen VARCHAR(100) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    path_file VARCHAR(255) NOT NULL,
    ukuran_file INT NOT NULL,
    tipe_file VARCHAR(50) NOT NULL,
    status_dokumen ENUM('belum_diverifikasi','valid','tidak_valid') NOT NULL DEFAULT 'belum_diverifikasi',
    catatan_verifikasi TEXT,
    diverifikasi_oleh INT(11),
    tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_verifikasi DATETIME,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (diverifikasi_oleh) REFERENCES tb_users(id_user) ON DELETE SET NULL,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_status (status_dokumen)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_verifikasi
-- ============================================
CREATE TABLE tb_verifikasi (
    id_verifikasi INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_petugas INT(11) NOT NULL,
    kelengkapan_dokumen ENUM('lengkap','tidak_lengkap','perlu_perbaikan') NOT NULL,
    kesesuaian_data ENUM('sesuai','tidak_sesuai') NOT NULL,
    catatan_verifikasi TEXT,
    rekomendasi ENUM('lanjut_survei','tolak','revisi') NOT NULL,
    tanggal_verifikasi DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_petugas (id_petugas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_survei
-- ============================================
CREATE TABLE tb_survei (
    id_survei INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_petugas INT(11) NOT NULL,
    tanggal_survei DATE NOT NULL,
    alamat_survei TEXT NOT NULL,
    kondisi_usaha ENUM('baik','cukup','kurang') NOT NULL,
    omzet_usaha DECIMAL(15,2),
    kondisi_agunan ENUM('baik','cukup','kurang'),
    nilai_agunan_estimasi DECIMAL(15,2),
    lingkungan_sekitar TEXT,
    catatan_survei TEXT,
    foto_survei TEXT,
    rekomendasi_survei ENUM('layak','tidak_layak','perlu_pertimbangan') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_petugas (id_petugas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_analisis_kredit
-- ============================================
CREATE TABLE tb_analisis_kredit (
    id_analisis INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_analis INT(11) NOT NULL,
    skor_karakter INT NOT NULL,
    skor_kapasitas INT NOT NULL,
    skor_modal INT NOT NULL,
    skor_agunan INT NOT NULL,
    skor_kondisi INT NOT NULL,
    skor_total DECIMAL(5,2) NOT NULL,
    rasio_dsr DECIMAL(5,2) NOT NULL,
    plafond_rekomendasi DECIMAL(15,2) NOT NULL,
    tenor_rekomendasi INT NOT NULL,
    catatan_analisis TEXT,
    kesimpulan ENUM('layak','tidak_layak','layak_dengan_syarat') NOT NULL,
    tanggal_analisis DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_analis) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_analis (id_analis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_agunan
-- ============================================
CREATE TABLE tb_agunan (
    id_agunan INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    jenis_agunan ENUM('tanah','bangunan','kendaraan','tabungan','lainnya') NOT NULL,
    nama_agunan VARCHAR(200) NOT NULL,
    no_sertifikat VARCHAR(100),
    atas_nama VARCHAR(100) NOT NULL,
    nilai_pasar DECIMAL(15,2) NOT NULL,
    nilai_taksasi DECIMAL(15,2),
    lokasi_agunan TEXT,
    luas VARCHAR(50),
    dokumen_agunan TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    INDEX idx_pengajuan (id_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_persetujuan
-- ============================================
CREATE TABLE tb_persetujuan (
    id_persetujuan INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT(11) NOT NULL,
    id_pimpinan INT(11) NOT NULL,
    keputusan ENUM('disetujui','ditolak','revisi') NOT NULL,
    plafond_disetujui DECIMAL(15,2),
    tenor_disetujui INT,
    bunga_disetujui DECIMAL(5,2),
    angsuran_per_bulan DECIMAL(15,2),
    alasan_keputusan TEXT,
    syarat_pencairan TEXT,
    tanggal_keputusan DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_pimpinan) REFERENCES tb_users(id_user) ON DELETE RESTRICT,
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_pimpinan (id_pimpinan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_notifikasi
-- ============================================
CREATE TABLE tb_notifikasi (
    id_notif INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    id_pengajuan INT(11),
    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    jenis_notif ENUM('info','sukses','peringatan','error') NOT NULL DEFAULT 'info',
    status_baca TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES tb_users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_pengajuan) REFERENCES tb_pengajuan_kredit(id_pengajuan) ON DELETE CASCADE,
    INDEX idx_user (id_user),
    INDEX idx_status (status_baca)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_log_aktivitas
-- ============================================
CREATE TABLE tb_log_aktivitas (
    id_log INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    modul VARCHAR(100) NOT NULL,
    ip_address VARCHAR(50),
    user_agent TEXT,
    data_lama TEXT,
    data_baru TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES tb_users(id_user) ON DELETE CASCADE,
    INDEX idx_user (id_user),
    INDEX idx_modul (modul)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Data Seed: Jenis Kredit
-- ============================================
INSERT INTO tb_jenis_kredit (nama_kredit, kode_kredit, deskripsi, plafond_min, plafond_max, bunga_per_tahun, tenor_min, tenor_max, syarat_dokumen, status) VALUES
('KUR Mikro', 'KUR-MIKRO', 'Kredit Usaha Rakyat untuk usaha mikro dan kecil dengan plafond hingga Rp50 juta', 1000000, 50000000, 6.00, 12, 36, '["KTP","KK","NPWP","Surat Izin Usaha","Laporan Keuangan"]', 'aktif'),
('KUR Kecil', 'KUR-KECIL', 'Kredit Usaha Rakyat untuk usaha kecil dengan plafond Rp50 juta - Rp500 juta', 50000000, 500000000, 6.00, 12, 48, '["KTP","KK","NPWP","Surat Izin Usaha","Laporan Keuangan","Agunan"]', 'aktif'),
('Kredit Konsumtif', 'KK-KONSUMTIF', 'Kredit untuk keperluan konsumsi seperti renovasi rumah, pendidikan, kesehatan', 5000000, 200000000, 9.00, 12, 60, '["KTP","KK","Slip Gaji","Rekening Koran","Agunan"]', 'aktif');

-- ============================================
-- Data Seed: Admin Default
-- ============================================
INSERT INTO tb_users (nama_lengkap, email, password, no_hp, role, status_akun) VALUES
('Administrator', 'admin@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin', 'aktif');
-- Password: password

-- ============================================
-- END OF SCHEMA
-- ============================================
