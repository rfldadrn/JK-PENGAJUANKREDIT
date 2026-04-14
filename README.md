# SISTEM INFORMASI PENGAJUAN KREDIT BRI LUBUK SIKAPING

## 📋 Deskripsi
Sistem berbasis web untuk mengotomatisasi dan mengintegrasikan proses pengajuan kredit mulai dari pendaftaran nasabah hingga pencairan dana.

## 🎯 Fitur Utama
- Multi-step form pengajuan kredit (4 langkah)
- Upload dokumen digital
- Workflow approval bertingkat (Petugas → Analis → Pimpinan)
- Analisis kredit 5C dengan auto-scoring
- Tracking status real-time
- Notifikasi sistem
- Dashboard per role (5 role)

## 🛠️ Tech Stack
- **Backend**: PHP 8.x Native (MVC Pattern)
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5.3.2
- **Server**: Laragon / XAMPP
- **Security**: PDO Prepared Statements, bcrypt

## 📂 Struktur Folder
```
PengajuanKredit/
├── app/
│   ├── controllers/     # AuthController, NasabahController, dll
│   ├── models/          # User, PengajuanKredit, Dokumen, dll
│   └── views/           # Semua view HTML
├── config/              # Database & app config
├── core/                # Core MVC (App, Controller, Model, Database)
├── database/            # schema.sql
└── public/              # index.php, assets, uploads
```

## ⚙️ Instalasi

### 1. Persyaratan Sistem
- PHP >= 8.0
- MySQL >= 5.7
- Apache/Nginx dengan mod_rewrite
- Laragon / XAMPP / WAMP

### 2. Clone / Download Project
```bash
# Ekstrak ke folder Laragon
C:\laragon\www\PengajuanKredit\
```

### 3. Import Database
```bash
# Buka phpMyAdmin atau MySQL CLI
mysql -u root -p

# Buat database
CREATE DATABASE db_pengajuan_kredit;

# Import schema
mysql -u root -p db_pengajuan_kredit < database/schema.sql
```

Atau via phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Create database `db_pengajuan_kredit`
3. Import file `database/schema.sql`

### 4. Konfigurasi Database
Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_pengajuan_kredit');
define('DB_USER', 'root');
define('DB_PASS', '');  // Sesuaikan password MySQL Anda
```

### 5. Konfigurasi Base URL
Edit file `config/config.php`:
```php
define('BASE_URL', 'http://localhost/PengajuanKredit/public/');
```

### 6. Set Permission Folder Upload
```bash
# Windows (via File Explorer)
Klik kanan folder public/assets/uploads → Properties → Security
Berikan Full Control untuk user IIS/Apache

# Linux/Mac
chmod -R 775 public/assets/uploads
```

### 7. Akses Aplikasi
Buka browser:
```
http://localhost/PengajuanKredit/public/
```

## 👤 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bri.co.id | password |
| Petugas | petugas@bri.co.id | password |
| Analis | analis@bri.co.id | password |
| Pimpinan | pimpinan@bri.co.id | password |

**Nasabah**: Daftar mandiri via halaman registrasi

## 📊 Database Schema

### Tabel Utama (12 tabel)
1. **tb_users** - Data akun pengguna
2. **tb_nasabah** - Profil nasabah
3. **tb_jenis_kredit** - Master produk kredit
4. **tb_pengajuan_kredit** - Data pengajuan
5. **tb_dokumen** - Dokumen upload
6. **tb_agunan** - Data jaminan
7. **tb_verifikasi** - Hasil verifikasi petugas
8. **tb_survei** - Laporan survei lapangan
9. **tb_analisis_kredit** - Analisis 5C
10. **tb_persetujuan** - Keputusan pimpinan
11. **tb_notifikasi** - Notifikasi sistem
12. **tb_log_aktivitas** - Activity log

## 🔄 Workflow Status
```
draft → diajukan → verifikasi → survei → analisis →
menunggu_keputusan → disetujui/ditolak/revisi → dicairkan
```

## 🎨 Halaman per Role

### Nasabah
- Dashboard (statistik, riwayat)
- Multi-step form pengajuan (4 step)
- Tracking pengajuan
- Profil

### Petugas (Account Officer)
- Dashboard
- Verifikasi dokumen
- Input survei lapangan
- Riwayat

### Analis Kredit
- Dashboard
- Form analisis 5C
- Auto-calculation DSR
- Riwayat analisis

### Pimpinan
- Dashboard
- Review & approval
- Laporan statistik
- Riwayat keputusan

### Admin
- Manajemen user
- Manajemen produk kredit
- Laporan global

## 🔒 Security Features
- Password hashing (bcrypt)
- PDO Prepared Statements (SQL Injection protection)
- Input sanitization
- Session management
- Role-based access control (RBAC)
- File upload validation

## 📦 Produk Kredit Default
1. **KUR Mikro** - Rp 1jt - 50jt (6% p.a.)
2. **KUR Kecil** - Rp 50jt - 500jt (6% p.a.)
3. **Kredit Konsumtif** - Rp 5jt - 200jt (9% p.a.)

## 🐛 Troubleshooting

### Error: "Unable to connect to database"
- Cek MySQL service running
- Verifikasi credentials di `config/database.php`
- Pastikan database sudah dibuat

### Error: "404 Not Found" saat akses route
- Pastikan mod_rewrite Apache enabled
- Cek file `.htaccess` di folder `public/`
- Restart Apache

### Upload file gagal
- Cek permission folder `public/assets/uploads/`
- Pastikan ukuran file < 5MB
- Format: PDF, JPG, PNG saja

### Style CSS tidak muncul
- Clear browser cache (Ctrl + F5)
- Cek BASE_URL di `config/config.php`
- Pastikan CDN Bootstrap accessible

## 📝 Development Notes

### Menambah Produk Kredit
```sql
INSERT INTO tb_jenis_kredit (nama_kredit, kode_kredit, plafond_min, plafond_max,
                               bunga_per_tahun, tenor_min, tenor_max, status, created_at)
VALUES ('Kredit Baru', 'KRD-BARU', 10000000, 100000000,
        8.5, 12, 60, 'aktif', NOW());
```

### Menambah User Manual
```sql
INSERT INTO tb_users (nama_lengkap, email, password, no_hp, role, status_akun, created_at, updated_at)
VALUES ('Nama User', 'user@email.com', '$2y$10$92IXU...', '081234567890',
        'petugas', 'aktif', NOW(), NOW());
```

### Reset Password User
```sql
UPDATE tb_users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email = 'user@email.com';
-- Password: "password"
```

## 📞 Support
Untuk bantuan teknis, hubungi tim developer atau buat issue di repository.

## 📄 License
© 2024 BRI Lubuk Sikaping. All rights reserved.

---

**Version**: 1.0.0
**Last Updated**: April 2024
