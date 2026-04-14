-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_pengajuan_kredit
CREATE DATABASE IF NOT EXISTS `db_pengajuan_kredit` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_pengajuan_kredit`;

-- Dumping structure for table db_pengajuan_kredit.tb_agunan
CREATE TABLE IF NOT EXISTS `tb_agunan` (
  `id_agunan` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `jenis_agunan` enum('tanah','bangunan','kendaraan','tabungan','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_agunan` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nilai_pasar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `nilai_taksasi` decimal(15,2) NOT NULL DEFAULT '0.00',
  `lokasi_agunan` text COLLATE utf8mb4_unicode_ci,
  `luas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'm2',
  `dokumen_agunan` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array paths',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_agunan`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  CONSTRAINT `tb_agunan_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_agunan: ~0 rows (approximately)
REPLACE INTO `tb_agunan` (`id_agunan`, `id_pengajuan`, `jenis_agunan`, `nama_agunan`, `no_sertifikat`, `atas_nama`, `nilai_pasar`, `nilai_taksasi`, `lokasi_agunan`, `luas`, `dokumen_agunan`, `created_at`) VALUES
	(1, 1, 'bangunan', 'Sertifikat Tanah', '123123123', 'Rifaldi Adrian', 150000000.00, 140000000.00, 'Jl. Rawa sari jakarta pusat', '33', NULL, '2026-04-07 20:25:36');

-- Dumping structure for table db_pengajuan_kredit.tb_analisis_kredit
CREATE TABLE IF NOT EXISTS `tb_analisis_kredit` (
  `id_analisis` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `id_analis` int NOT NULL,
  `skor_karakter` int NOT NULL DEFAULT '0' COMMENT '0-100',
  `skor_kapasitas` int NOT NULL DEFAULT '0' COMMENT '0-100',
  `skor_modal` int NOT NULL DEFAULT '0' COMMENT '0-100',
  `skor_agunan` int NOT NULL DEFAULT '0' COMMENT '0-100',
  `skor_kondisi` int NOT NULL DEFAULT '0' COMMENT '0-100',
  `skor_total` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Rata-rata tertimbang',
  `rasio_dsr` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Debt Service Ratio %',
  `plafond_rekomendasi` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tenor_rekomendasi` int NOT NULL DEFAULT '0',
  `catatan_analisis` text COLLATE utf8mb4_unicode_ci,
  `kesimpulan` enum('layak','tidak_layak','layak_dengan_syarat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_analisis` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_analisis`),
  KEY `id_analis` (`id_analis`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  CONSTRAINT `tb_analisis_kredit_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE CASCADE,
  CONSTRAINT `tb_analisis_kredit_ibfk_2` FOREIGN KEY (`id_analis`) REFERENCES `tb_users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_analisis_kredit: ~1 rows (approximately)
REPLACE INTO `tb_analisis_kredit` (`id_analisis`, `id_pengajuan`, `id_analis`, `skor_karakter`, `skor_kapasitas`, `skor_modal`, `skor_agunan`, `skor_kondisi`, `skor_total`, `rasio_dsr`, `plafond_rekomendasi`, `tenor_rekomendasi`, `catatan_analisis`, `kesimpulan`, `tanggal_analisis`, `created_at`) VALUES
	(1, 1, 3, 80, 100, 80, 100, 75, 89.50, 11.08, 20000000.00, 24, 'oke sih', 'layak', '2026-04-07 20:57:19', '2026-04-07 20:57:19');

-- Dumping structure for table db_pengajuan_kredit.tb_dokumen
CREATE TABLE IF NOT EXISTS `tb_dokumen` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `jenis_dokumen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran_file` int NOT NULL COMMENT 'Dalam KB',
  `tipe_file` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_dokumen` enum('belum_diverifikasi','valid','tidak_valid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_diverifikasi',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `diverifikasi_oleh` int DEFAULT NULL,
  `tanggal_upload` datetime NOT NULL,
  `tanggal_verifikasi` datetime DEFAULT NULL,
  PRIMARY KEY (`id_dokumen`),
  KEY `diverifikasi_oleh` (`diverifikasi_oleh`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  KEY `idx_status` (`status_dokumen`),
  CONSTRAINT `tb_dokumen_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE CASCADE,
  CONSTRAINT `tb_dokumen_ibfk_2` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `tb_users` (`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_dokumen: ~5 rows (approximately)
REPLACE INTO `tb_dokumen` (`id_dokumen`, `id_pengajuan`, `jenis_dokumen`, `nama_file`, `path_file`, `ukuran_file`, `tipe_file`, `status_dokumen`, `catatan_verifikasi`, `diverifikasi_oleh`, `tanggal_upload`, `tanggal_verifikasi`) VALUES
	(1, 1, 'KTP', 'stuk.pdf', 'dokumen/69d5060898fed_1775568392.pdf', 204, 'application/pdf', 'valid', 'Oke', 2, '2026-04-07 20:26:32', '2026-04-07 20:28:50'),
	(2, 1, 'KK', 'stuk.pdf', 'dokumen/69d5060d4b15e_1775568397.pdf', 204, 'application/pdf', 'valid', 'Oke', 2, '2026-04-07 20:26:37', '2026-04-07 20:28:57'),
	(3, 1, 'NPWP', 'stuk.pdf', 'dokumen/69d506115a24f_1775568401.pdf', 204, 'application/pdf', 'valid', 'Oke', 2, '2026-04-07 20:26:41', '2026-04-07 20:29:02'),
	(6, 1, 'Surat Usaha', 'Nota Transaksi.pdf', 'dokumen/69d50b2c94b20_1775569708.pdf', 211, 'application/pdf', 'valid', 'Oke', 2, '2026-04-07 20:48:28', '2026-04-07 20:49:15'),
	(7, 1, 'Foto Usaha', 'bukti.pdf', 'dokumen/69d50b3709612_1775569719.pdf', 151, 'application/pdf', 'valid', 'Oke', 2, '2026-04-07 20:48:39', '2026-04-07 20:49:20');

-- Dumping structure for table db_pengajuan_kredit.tb_jenis_kredit
CREATE TABLE IF NOT EXISTS `tb_jenis_kredit` (
  `id_jenis_kredit` int NOT NULL AUTO_INCREMENT,
  `nama_kredit` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kredit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `plafond_min` decimal(15,2) NOT NULL DEFAULT '0.00',
  `plafond_max` decimal(15,2) NOT NULL DEFAULT '0.00',
  `bunga_per_tahun` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tenor_min` int NOT NULL DEFAULT '6',
  `tenor_max` int NOT NULL DEFAULT '60',
  `syarat_dokumen` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_jenis_kredit`),
  UNIQUE KEY `kode_kredit` (`kode_kredit`),
  KEY `idx_kode` (`kode_kredit`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_jenis_kredit: ~3 rows (approximately)
REPLACE INTO `tb_jenis_kredit` (`id_jenis_kredit`, `nama_kredit`, `kode_kredit`, `deskripsi`, `plafond_min`, `plafond_max`, `bunga_per_tahun`, `tenor_min`, `tenor_max`, `syarat_dokumen`, `status`, `created_at`) VALUES
	(1, 'KUR Mikro', 'KUR-MIKRO', 'Kredit Usaha Rakyat untuk usaha mikro dengan plafond hingga Rp 50 juta', 1000000.00, 50000000.00, 6.00, 6, 36, '["KTP", "KK", "NPWP", "Surat Usaha", "Foto Usaha"]', 'aktif', '2026-04-07 19:45:48'),
	(2, 'KUR Kecil', 'KUR-KECIL', 'Kredit Usaha Rakyat untuk usaha kecil dengan plafond Rp 50 juta - Rp 500 juta', 50000000.00, 500000000.00, 6.00, 12, 48, '["KTP", "KK", "NPWP", "Surat Usaha", "Laporan Keuangan", "Sertifikat Agunan"]', 'aktif', '2026-04-07 19:45:48'),
	(3, 'Kredit Konsumtif', 'KRD-KONSUMTIF', 'Kredit untuk kebutuhan konsumtif seperti renovasi rumah, pendidikan, kesehatan', 5000000.00, 200000000.00, 9.00, 12, 60, '["KTP", "KK", "NPWP", "Slip Gaji", "Rekening Koran"]', 'aktif', '2026-04-07 19:45:48');

-- Dumping structure for table db_pengajuan_kredit.tb_log_aktivitas
CREATE TABLE IF NOT EXISTS `tb_log_aktivitas` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modul` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `data_lama` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON',
  `data_baru` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_log`),
  KEY `idx_user` (`id_user`),
  KEY `idx_modul` (`modul`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `tb_log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_log_aktivitas: ~0 rows (approximately)

-- Dumping structure for table db_pengajuan_kredit.tb_nasabah
CREATE TABLE IF NOT EXISTS `tb_nasabah` (
  `id_nasabah` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `no_nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_perkawinan` enum('belum_kawin','kawin','cerai') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ktp` text COLLATE utf8mb4_unicode_ci,
  `alamat_domisili` text COLLATE utf8mb4_unicode_ci,
  `kelurahan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota_kabupaten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_perusahaan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_bulanan` decimal(15,2) DEFAULT '0.00',
  `no_npwp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id_nasabah`),
  UNIQUE KEY `no_nik` (`no_nik`),
  KEY `idx_nik` (`no_nik`),
  KEY `idx_user` (`id_user`),
  CONSTRAINT `tb_nasabah_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_nasabah: ~2 rows (approximately)
REPLACE INTO `tb_nasabah` (`id_nasabah`, `id_user`, `no_nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `status_perkawinan`, `alamat_ktp`, `alamat_domisili`, `kelurahan`, `kecamatan`, `kota_kabupaten`, `provinsi`, `pekerjaan`, `nama_perusahaan`, `penghasilan_bulanan`, `no_npwp`, `created_at`, `updated_at`) VALUES
	(1, 6, '1371022505998881', 'Bandung', '1999-01-01', 'L', 'belum_kawin', 'Jl. Pramuka Sari 5 No 9, Rawasari Kec. Cempaka Putih, Central Jakarta', '', 'Rawasari', 'Cempaka putih', 'Jakarta', 'DKI Jakarta', 'Karyawan Swasta', 'PT. Astra International, Tbkk', 8000000.00, '138123129381209', '2026-04-07 20:09:25', '2026-04-07 20:23:20'),
	(2, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, '2026-04-14 20:28:37', '2026-04-14 20:28:37');

-- Dumping structure for table db_pengajuan_kredit.tb_notifikasi
CREATE TABLE IF NOT EXISTS `tb_notifikasi` (
  `id_notif` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_pengajuan` int DEFAULT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_notif` enum('info','sukses','peringatan','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `status_baca` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=belum, 1=sudah',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_notif`),
  KEY `id_pengajuan` (`id_pengajuan`),
  KEY `idx_user` (`id_user`),
  KEY `idx_status` (`status_baca`),
  CONSTRAINT `tb_notifikasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `tb_notifikasi_ibfk_2` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_notifikasi: ~8 rows (approximately)
REPLACE INTO `tb_notifikasi` (`id_notif`, `id_user`, `id_pengajuan`, `judul`, `pesan`, `jenis_notif`, `status_baca`, `created_at`) VALUES
	(1, 6, 1, 'Pengajuan Kredit Berhasil Dikirim', 'Pengajuan kredit Anda dengan nomor PKR202604079476 telah berhasil dikirim.', 'sukses', 0, '2026-04-07 20:27:03'),
	(2, 6, 1, 'Status Verifikasi Pengajuan', 'Pengajuan kredit Anda telah diverifikasi. Status: Revisi', 'info', 0, '2026-04-07 20:31:13'),
	(3, 2, 1, 'Pengajuan Revisi Disubmit Ulang', 'Pengajuan PKR202604079476 telah diperbaiki dan siap diverifikasi ulang', 'info', 0, '2026-04-07 20:48:59'),
	(4, 6, 1, 'Status Verifikasi Pengajuan', 'Pengajuan kredit Anda telah diverifikasi. Status: Survei', 'info', 0, '2026-04-07 20:49:36'),
	(5, 6, 1, 'Survei Lapangan Telah Dilakukan', 'Survei lapangan untuk pengajuan kredit Anda telah selesai dan sedang dalam tahap analisis kredit.', 'info', 0, '2026-04-07 20:53:45'),
	(6, 6, 1, 'Analisis Kredit Selesai', 'Analisis kredit untuk pengajuan Anda telah selesai dan sedang menunggu persetujuan pimpinan.', 'info', 0, '2026-04-07 20:57:19'),
	(7, 6, 1, 'Analisis Kredit Selesai', 'Analisis kredit untuk pengajuan Anda telah selesai dan sedang menunggu persetujuan pimpinan.', 'info', 0, '2026-04-07 20:57:26'),
	(8, 6, 1, 'Analisis Kredit Selesai', 'Analisis kredit untuk pengajuan Anda telah selesai dan sedang menunggu persetujuan pimpinan.', 'info', 0, '2026-04-07 20:57:30'),
	(9, 6, 1, 'Analisis Kredit Selesai', 'Analisis kredit untuk pengajuan Anda telah selesai dan sedang menunggu persetujuan pimpinan.', 'info', 0, '2026-04-07 20:57:51'),
	(10, 6, 1, 'Keputusan Pengajuan Kredit', 'Selamat! Pengajuan kredit Anda telah disetujui dengan plafond Rp 20.000.000 untuk tenor 24 bulan.', 'sukses', 0, '2026-04-07 21:10:17');

-- Dumping structure for table db_pengajuan_kredit.tb_pengajuan_kredit
CREATE TABLE IF NOT EXISTS `tb_pengajuan_kredit` (
  `id_pengajuan` int NOT NULL AUTO_INCREMENT,
  `no_pengajuan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_nasabah` int NOT NULL,
  `id_jenis_kredit` int NOT NULL,
  `jumlah_pinjaman` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tenor` int NOT NULL COMMENT 'Dalam bulan',
  `tujuan_kredit` text COLLATE utf8mb4_unicode_ci,
  `sumber_pengembalian` text COLLATE utf8mb4_unicode_ci,
  `status_pengajuan` enum('draft','diajukan','verifikasi','survei','analisis','menunggu_keputusan','disetujui','ditolak','revisi','dicairkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `tanggal_pengajuan` datetime DEFAULT NULL,
  `tanggal_keputusan` datetime DEFAULT NULL,
  `catatan_nasabah` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id_pengajuan`),
  UNIQUE KEY `no_pengajuan` (`no_pengajuan`),
  KEY `id_jenis_kredit` (`id_jenis_kredit`),
  KEY `idx_no_pengajuan` (`no_pengajuan`),
  KEY `idx_nasabah` (`id_nasabah`),
  KEY `idx_status` (`status_pengajuan`),
  KEY `idx_tanggal` (`tanggal_pengajuan`),
  CONSTRAINT `tb_pengajuan_kredit_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `tb_nasabah` (`id_nasabah`) ON DELETE CASCADE,
  CONSTRAINT `tb_pengajuan_kredit_ibfk_2` FOREIGN KEY (`id_jenis_kredit`) REFERENCES `tb_jenis_kredit` (`id_jenis_kredit`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_pengajuan_kredit: ~1 rows (approximately)
REPLACE INTO `tb_pengajuan_kredit` (`id_pengajuan`, `no_pengajuan`, `id_nasabah`, `id_jenis_kredit`, `jumlah_pinjaman`, `tenor`, `tujuan_kredit`, `sumber_pengembalian`, `status_pengajuan`, `tanggal_pengajuan`, `tanggal_keputusan`, `catatan_nasabah`, `created_at`, `updated_at`) VALUES
	(1, 'PKR202604079476', 1, 1, 20000000.00, 24, 'Pembelian motor', 'Dari gaji', 'disetujui', '2026-04-07 20:27:03', '2026-04-07 21:10:17', 'Tolong di approve', '2026-04-07 20:24:19', '2026-04-07 20:57:51');

-- Dumping structure for table db_pengajuan_kredit.tb_persetujuan
CREATE TABLE IF NOT EXISTS `tb_persetujuan` (
  `id_persetujuan` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `id_pimpinan` int NOT NULL,
  `keputusan` enum('disetujui','ditolak','revisi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `plafond_disetujui` decimal(15,2) DEFAULT '0.00',
  `tenor_disetujui` int DEFAULT '0',
  `bunga_disetujui` decimal(5,2) DEFAULT '0.00',
  `angsuran_per_bulan` decimal(15,2) DEFAULT '0.00',
  `alasan_keputusan` text COLLATE utf8mb4_unicode_ci,
  `syarat_pencairan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_keputusan` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_persetujuan`),
  KEY `id_pimpinan` (`id_pimpinan`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  CONSTRAINT `tb_persetujuan_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE CASCADE,
  CONSTRAINT `tb_persetujuan_ibfk_2` FOREIGN KEY (`id_pimpinan`) REFERENCES `tb_users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_persetujuan: ~0 rows (approximately)
REPLACE INTO `tb_persetujuan` (`id_persetujuan`, `id_pengajuan`, `id_pimpinan`, `keputusan`, `plafond_disetujui`, `tenor_disetujui`, `bunga_disetujui`, `angsuran_per_bulan`, `alasan_keputusan`, `syarat_pencairan`, `tanggal_keputusan`, `created_at`) VALUES
	(1, 1, 4, 'disetujui', 20000000.00, 24, 6.00, 886412.21, 'Kasihan aja', 'Tolong lapor pajak', '2026-04-07 21:10:17', '2026-04-07 21:10:17');

-- Dumping structure for table db_pengajuan_kredit.tb_survei
CREATE TABLE IF NOT EXISTS `tb_survei` (
  `id_survei` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `id_petugas` int NOT NULL,
  `tanggal_survei` date NOT NULL,
  `alamat_survei` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kondisi_usaha` enum('baik','cukup','kurang') COLLATE utf8mb4_unicode_ci NOT NULL,
  `omzet_usaha` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kondisi_agunan` enum('baik','cukup','kurang') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_agunan_estimasi` decimal(15,2) NOT NULL DEFAULT '0.00',
  `lingkungan_sekitar` text COLLATE utf8mb4_unicode_ci,
  `catatan_survei` text COLLATE utf8mb4_unicode_ci,
  `foto_survei` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array paths',
  `rekomendasi_survei` enum('layak','tidak_layak','perlu_pertimbangan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_survei`),
  KEY `id_petugas` (`id_petugas`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  CONSTRAINT `tb_survei_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE CASCADE,
  CONSTRAINT `tb_survei_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `tb_users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_survei: ~0 rows (approximately)
REPLACE INTO `tb_survei` (`id_survei`, `id_pengajuan`, `id_petugas`, `tanggal_survei`, `alamat_survei`, `kondisi_usaha`, `omzet_usaha`, `kondisi_agunan`, `nilai_agunan_estimasi`, `lingkungan_sekitar`, `catatan_survei`, `foto_survei`, `rekomendasi_survei`, `created_at`) VALUES
	(1, 1, 2, '2026-04-07', 'Jl. Pramuka Sari 5 No 9, Rawasari Kec. Cempaka Putih, Central Jakarta', 'baik', 2000000.00, 'baik', 130000000.00, 'Baik', 'Oke sih', '["survei\\/69d50c69d3f3e_1775570025.jpg","survei\\/69d50c69d545c_1775570025.jpg","survei\\/69d50c69d6097_1775570025.png"]', 'layak', '2026-04-07 20:53:45');

-- Dumping structure for table db_pengajuan_kredit.tb_users
CREATE TABLE IF NOT EXISTS `tb_users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('nasabah','petugas','analis','pimpinan','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nasabah',
  `status_akun` enum('aktif','nonaktif','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_reset` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_status` (`status_akun`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_users: ~7 rows (approximately)
REPLACE INTO `tb_users` (`id_user`, `nama_lengkap`, `email`, `password`, `no_hp`, `role`, `status_akun`, `foto_profil`, `token_reset`, `created_at`, `updated_at`) VALUES
	(1, 'Administrator', 'admin@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin', 'aktif', NULL, NULL, '2026-04-07 19:45:48', '2026-04-14 20:33:18'),
	(2, 'Petugas AO', 'petugas@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'petugas', 'aktif', NULL, NULL, '2026-04-07 19:45:48', '2026-04-07 20:50:42'),
	(3, 'Analis Kredit', 'analis@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'analis', 'aktif', NULL, NULL, '2026-04-07 19:45:48', '2026-04-07 20:54:00'),
	(4, 'Pimpinan Cabang', 'pimpinan@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567893', 'pimpinan', 'aktif', NULL, NULL, '2026-04-07 19:45:48', '2026-04-14 20:33:36'),
	(5, 'Mario Bross', 'mariobross@bri.co.id', '$2y$10$bIJhli8CVwL9EHR4Vb5hwuNMhbgK2t8muly/ziQV5dN5m3qHbKwha', '081270892182', 'admin', 'aktif', NULL, NULL, '2026-04-07 20:00:09', '2026-04-07 20:00:09'),
	(6, 'Super Mario', 'supermario@bri.co.id', '$2y$10$qdRzBH5x3SC0QZVWvWsDK.qy7th3nSoOjp7EkqLPjLRXrU5ixA9e2', '081270892182', 'nasabah', 'aktif', NULL, NULL, '2026-04-07 20:09:25', '2026-04-14 20:35:15'),
	(7, 'Erna Wilis', 'ernawilis@gmail.com', '$2y$10$ugbUbSEyW2VbLfEPqs5j/OOyxT1cod0zy0l5VefOFGhfFmzjJY/WW', '081277019283', 'nasabah', 'aktif', NULL, NULL, '2026-04-14 20:28:37', '2026-04-14 20:28:47');

-- Dumping structure for table db_pengajuan_kredit.tb_verifikasi
CREATE TABLE IF NOT EXISTS `tb_verifikasi` (
  `id_verifikasi` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `id_petugas` int NOT NULL,
  `kelengkapan_dokumen` enum('lengkap','tidak_lengkap','perlu_perbaikan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kesesuaian_data` enum('sesuai','tidak_sesuai') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `rekomendasi` enum('lanjut_survei','tolak','revisi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_verifikasi` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_verifikasi`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  KEY `idx_petugas` (`id_petugas`),
  CONSTRAINT `tb_verifikasi_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `tb_pengajuan_kredit` (`id_pengajuan`) ON DELETE CASCADE,
  CONSTRAINT `tb_verifikasi_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `tb_users` (`id_user`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_pengajuan_kredit.tb_verifikasi: ~1 rows (approximately)
REPLACE INTO `tb_verifikasi` (`id_verifikasi`, `id_pengajuan`, `id_petugas`, `kelengkapan_dokumen`, `kesesuaian_data`, `catatan_verifikasi`, `rekomendasi`, `tanggal_verifikasi`, `created_at`) VALUES
	(1, 1, 2, 'lengkap', 'sesuai', 'Sudah Oke', 'lanjut_survei', '2026-04-07 20:31:13', '2026-04-07 20:31:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
