# 🚀 PANDUAN INSTALASI CEPAT
## Sistem Informasi Pengajuan Kredit BRI Lubuk Sikaping

### ⏱️ Waktu Instalasi: 5-10 menit

---

## LANGKAH 1: Persiapan

### Install Laragon
1. Download [Laragon](https://laragon.org/download/)
2. Install dengan setting default
3. Start Laragon (Apache & MySQL)

---

## LANGKAH 2: Setup Project

### A. Copy Project
```
Ekstrak folder PengajuanKredit ke:
C:\laragon\www\PengajuanKredit\
```

### B. Struktur Folder
Pastikan struktur seperti ini:
```
C:\laragon\www\PengajuanKredit\
├── app/
├── config/
├── core/
├── database/
└── public/
```

---

## LANGKAH 3: Setup Database

### Via phpMyAdmin (Mudah)
1. Buka browser: `http://localhost/phpmyadmin`
2. Klik tab **"New"** / **"Baru"**
3. Database name: `db_pengajuan_kredit`
4. Klik **"Create"**
5. Klik tab **"Import"**
6. Choose file: `C:\laragon\www\PengajuanKredit\database\schema.sql`
7. Klik **"Go"** / **"Kirim"**
8. ✅ Done! Database siap

### Via MySQL CLI (Advanced)
```bash
# Buka Laragon Terminal (Klik kanan icon Laragon > Terminal)
mysql -u root -p

# Masukkan password (default: kosong, tekan Enter)
CREATE DATABASE db_pengajuan_kredit;
exit;

# Import schema
mysql -u root db_pengajuan_kredit < database/schema.sql
```

---

## LANGKAH 4: Konfigurasi

### A. Database Config
Edit: `config/database.php`
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_pengajuan_kredit');
define('DB_USER', 'root');
define('DB_PASS', '');  // Kosongkan jika tidak ada password
```

### B. Base URL Config (PENTING!)
Edit: `config/config.php`
```php
define('BASE_URL', 'http://localhost/PengajuanKredit/public/');
```

⚠️ **Catatan**: Pastikan ada `/public/` di akhir URL!

---

## LANGKAH 5: Test Aplikasi

### Akses Aplikasi
Buka browser:
```
http://localhost/PengajuanKredit/public/
```

### Login Demo
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bri.co.id | password |
| Petugas | petugas@bri.co.id | password |
| Analis | analis@bri.co.id | password |
| Pimpinan | pimpinan@bri.co.id | password |

### Register Nasabah
Klik **"Daftar Akun"** di halaman login

---

## ✅ CHECKLIST INSTALASI

- [ ] Laragon sudah running (Apache & MySQL hijau)
- [ ] Folder project di `C:\laragon\www\PengajuanKredit`
- [ ] Database `db_pengajuan_kredit` sudah dibuat
- [ ] File `schema.sql` sudah di-import
- [ ] Config database di `config/database.php` sudah benar
- [ ] BASE_URL di `config/config.php` sudah benar
- [ ] Bisa akses `http://localhost/PengajuanKredit/public/`
- [ ] Bisa login dengan akun demo

---

## 🐛 Troubleshooting

### ❌ Error: "Database connection failed"
**Solusi:**
1. Cek MySQL running di Laragon
2. Verifikasi nama database: `db_pengajuan_kredit`
3. Cek `config/database.php`

### ❌ Error: "Page not found" / 404
**Solusi:**
1. Pastikan URL ada `/public/` di akhir
2. Restart Apache di Laragon
3. Clear browser cache (Ctrl + Shift + Delete)

### ❌ Style CSS tidak muncul
**Solusi:**
1. Clear browser cache (Ctrl + F5)
2. Cek BASE_URL di `config/config.php`
3. Pastikan internet connect (untuk load Bootstrap CDN)

### ❌ Upload file gagal
**Solusi:**
1. Klik kanan folder `public/assets/uploads`
2. Properties > Security > Edit
3. Berikan Full Control untuk "Users"

---

## 📱 Fitur Utama

### Untuk Nasabah:
✅ Daftar akun online
✅ Isi profil lengkap
✅ Ajukan kredit (4 langkah mudah)
✅ Upload dokumen
✅ Tracking status real-time

### Untuk Petugas:
✅ Verifikasi dokumen
✅ Input survei lapangan
✅ Upload foto survei

### Untuk Analis:
✅ Analisis kredit 5C
✅ Auto-calculate DSR
✅ Scoring otomatis

### Untuk Pimpinan:
✅ Review pengajuan
✅ Approve/reject dengan alasan
✅ Lihat laporan statistik

---

## 🎓 Tutorial Penggunaan

### Cara Mengajukan Kredit (Nasabah)
1. Login sebagai nasabah
2. Klik **"Ajukan Kredit Baru"**
3. **Step 1**: Pilih jenis kredit > Isi data pinjaman
4. **Step 2**: Tambah data agunan
5. **Step 3**: Upload dokumen (KTP, KK, dll)
6. **Step 4**: Review > Centang persetujuan > **Kirim**
7. ✅ Dapat nomor pengajuan
8. Track status di menu **"Tracking Pengajuan"**

### Cara Verifikasi (Petugas)
1. Login sebagai petugas
2. Dashboard > Klik pengajuan baru
3. Review dokumen satu per satu
4. Klik ✅ (valid) atau ❌ (tidak valid)
5. Isi form verifikasi lengkap
6. Pilih rekomendasi
7. Simpan

### Cara Analisis (Analis)
1. Login sebagai analis
2. Pilih pengajuan yang sudah survei
3. Isi skor 5C (0-100)
4. Sistem auto-calculate skor total & DSR
5. Input plafond & tenor rekomendasi
6. Pilih kesimpulan
7. Simpan

### Cara Approve (Pimpinan)
1. Login sebagai pimpinan
2. Pilih pengajuan menunggu keputusan
3. Review analisis lengkap
4. Pilih keputusan (Setuju/Tolak/Revisi)
5. Jika setuju: Isi plafond, tenor, bunga
6. Tulis alasan keputusan
7. Simpan

---

## 🔐 Keamanan

✅ Password encrypted (bcrypt)
✅ SQL Injection protected (PDO)
✅ XSS protected (sanitized input)
✅ File upload validated
✅ Session secured
✅ Role-based access

---

## 📞 Butuh Bantuan?

**Masalah Teknis:**
- Cek file `README.md` untuk troubleshooting lengkap
- Screenshot error > kirim ke developer

**Pertanyaan Fitur:**
- Baca dokumentasi lengkap di `README.md`
- Test dengan akun demo terlebih dahulu

---

## ✨ Selamat!
Aplikasi siap digunakan! 🎉

Login dan explore fitur-fiturnya.
