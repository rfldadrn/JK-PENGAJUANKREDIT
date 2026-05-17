# PERBAIKAN ERROR DATABASE

## Error yang Diperbaiki

### Error Awal:
```
Fatal error: SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'db_pengajuan_kredit.users' doesn't exist
```

## Perubahan yang Dilakukan

### 1. Model Laporan.php - Nama Table
Semua query diperbaiki untuk menggunakan prefix `tb_`:
- ❌ `users` → ✅ `tb_users`
- ❌ `nasabah` → ✅ `tb_nasabah`
- ❌ `pengajuan_kredit` → ✅ `tb_pengajuan_kredit`
- ❌ `jenis_kredit` → ✅ `tb_jenis_kredit`
- ❌ `persetujuan` → ✅ `tb_persetujuan`
- ❌ `verifikasi` → ✅ `tb_verifikasi`
- ❌ `survei` → ✅ `tb_survei`
- ❌ `analisis_kredit` → ✅ `tb_analisis_kredit`

### 2. Model Laporan.php - Nama Kolom
Diperbaiki untuk menggunakan nama kolom yang sesuai schema database:
- ❌ `u.id` → ✅ `u.id_user`
- ❌ `n.nik` → ✅ `n.no_nik as nik`
- ❌ `n.alamat` → ✅ `COALESCE(n.alamat_domisili, n.alamat_ktp) as alamat`
- ❌ `n.penghasilan_per_bulan` → ✅ `n.penghasilan_bulanan as penghasilan_per_bulan`
- ❌ `u.no_telepon` → ✅ `u.no_hp as no_telepon`
- ❌ `jk.nama_produk` → ✅ `jk.nama_kredit as nama_produk`
- ❌ `pk.id` → ✅ `pk.id_pengajuan`
- ❌ `jk.id` → ✅ `jk.id_jenis_kredit`

### 3. Model Laporan.php - Foreign Keys
Diperbaiki JOIN untuk menggunakan kolom ID yang benar:
- ❌ `pk ON u.id = pk.id_nasabah` → ✅ `pk ON n.id_nasabah = pk.id_nasabah`
- ❌ `v ON u.id = v.id_petugas` → ✅ `v ON u.id_user = v.id_petugas`
- ❌ `pim ON p.id_pimpinan = pim.id` → ✅ `pim ON p.id_pimpinan = pim.id_user`

### 4. View Files - Nama Kolom Master Data
Diperbaiki di `admin/laporan.php` dan `pimpinan/laporan.php`:
- ❌ `$jk['id']` → ✅ `$jk['id_jenis_kredit']`
- ❌ `$jk['nama_produk']` → ✅ `$jk['nama_kredit']`

### 5. Model Laporan.php - WHERE Clause
- Menambahkan filter `WHERE pk.status_pengajuan != 'draft'` di getLaporanPengajuan()
- Menambahkan filter `WHERE status_pengajuan != 'draft'` di getStatistics()

## Query yang Diperbaiki

### 1. getLaporanNasabah()
✅ Menggunakan `tb_users`, `tb_nasabah`, `tb_pengajuan_kredit`, `tb_persetujuan`
✅ JOIN via `n.id_nasabah = pk.id_nasabah`
✅ Alias kolom yang sesuai

### 2. getLaporanPengajuan()
✅ Menggunakan semua table dengan prefix `tb_`
✅ JOIN sequence: pk → n → u → jk → p
✅ Filter draft pengajuan

### 3. getLaporanPetugas()
✅ JOIN dengan kolom ID yang benar (`id_user`, bukan `id`)
✅ Kolom ID verifikasi: `id_verifikasi`, survei: `id_survei`, analisis: `id_analisis`, persetujuan: `id_persetujuan`

### 4. getLaporanStatusPengajuan()
✅ JOIN pimpinan: `pim ON p.id_pimpinan = pim.id_user`
✅ LEFT JOIN analisis dengan kolom yang benar

### 5. getStatistics()
✅ Semua table dengan prefix `tb_`
✅ Filter draft di total_pengajuan dan pengajuan_pending

## Testing

Jalankan command untuk test:
```bash
php -l app/models/Laporan.php
```

Expected output:
```
No syntax errors detected in app/models/Laporan.php
```

## Status

✅ Syntax check passed
✅ All table names corrected
✅ All column names corrected
✅ All JOINs corrected
✅ View files updated
✅ Ready for testing in browser

## Next Steps

1. Buka browser dan akses:
   - http://localhost/PengajuanKredit/public/admin/laporan
   - http://localhost/PengajuanKredit/public/pimpinan/laporan

2. Test semua jenis laporan:
   - Laporan Data Nasabah
   - Laporan Data Pengajuan Kredit
   - Laporan Data Petugas Bank
   - Laporan Status Pengajuan

3. Test export:
   - Export PDF
   - Export Excel

Jika masih ada error, periksa:
- Koneksi database di config/database.php
- Pastikan database sudah di-import
- Pastikan ada data di table
