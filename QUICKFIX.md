# 🔧 QUICK FIX GUIDE

## ✅ Issue: Session Warning (SUDAH DIPERBAIKI)

### Error:
```
Warning: ini_set(): Session ini settings cannot be changed when a session is active
```

### Fix Applied:
✅ Memindahkan `session_start()` ke **setelah** load config di `public/index.php`
✅ Update `BASE_URL` menjadi `http://localhost/PengajuanKredit/public/`
✅ Update `UPLOAD_PATH` menjadi `public/assets/uploads/`

### Struktur Sudah Benar:
```php
// public/index.php
1. Load config (ini_set session settings)
2. session_start() ← Dipindahkan ke sini
3. Load core classes
4. Initialize app
```

---

## 👥 Add Missing Demo Users

Jika hanya ada 1 user (admin) di database, jalankan SQL berikut via **phpMyAdmin**:

### Cara:
1. Buka: `http://localhost/phpmyadmin`
2. Pilih database: `db_pengajuan_kredit`
3. Klik tab **"SQL"**
4. Copy-paste SQL berikut:

```sql
INSERT INTO tb_users (nama_lengkap, email, password, no_hp, role, status_akun, created_at, updated_at) VALUES
('Petugas AO', 'petugas@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'petugas', 'aktif', NOW(), NOW()),
('Analis Kredit', 'analis@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'analis', 'aktif', NOW(), NOW()),
('Pimpinan Cabang', 'pimpinan@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567893', 'pimpinan', 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE email = email;
```

5. Klik **"Go"**

### Verifikasi:
```sql
SELECT nama_lengkap, email, role FROM tb_users ORDER BY role;
```

Harus muncul 4 user:
- admin@bri.co.id (admin)
- analis@bri.co.id (analis)
- petugas@bri.co.id (petugas)
- pimpinan@bri.co.id (pimpinan)

**Password semua user**: `password`

---

## 🗂️ Folder Permissions (Windows)

Jika upload file gagal:

1. Klik kanan folder: `public/assets/uploads/`
2. **Properties** → **Security** → **Edit**
3. Pilih **Users** → Centang **Full Control**
4. **Apply** → **OK**

---

## 🌐 Access URL

### ✅ Correct URL:
```
http://localhost/PengajuanKredit/public/
```

### ❌ Wrong URL:
```
http://localhost/PengajuanKredit/          (404 error)
http://localhost/PengajuanKredit/index.php (404 error)
```

**IMPORTANT:** Harus ada `/public/` di akhir!

---

## 🧪 Test System

### 1. Test Database Connection
```bash
php test_db.php
```

Harus muncul:
```
✅ SUCCESS! Database connection established.
Database Tables (12)
Users in database: 4
```

### 2. Test Login
1. Buka: `http://localhost/PengajuanKredit/public/`
2. Login dengan:
   - Email: `admin@bri.co.id`
   - Password: `password`
3. Harus redirect ke dashboard admin

### 3. Test Nasabah Registration
1. Klik **"Daftar Akun"**
2. Isi form registrasi
3. Klik **"Daftar"**
4. Login dengan akun yang baru dibuat
5. Lengkapi profil
6. Test ajukan kredit baru

---

## 🔍 Common Issues & Solutions

### Issue: "Page not found" / 404

**Solution:**
1. Pastikan URL benar: `http://localhost/PengajuanKredit/public/`
2. Cek Apache mod_rewrite enabled
3. Restart Apache di Laragon
4. Clear browser cache (Ctrl + Shift + Delete)

### Issue: "Database connection failed"

**Solution:**
1. Check MySQL running di Laragon (ikon hijau)
2. Verifikasi `config/database.php`:
   ```php
   define('DB_NAME', 'db_pengajuan_kredit');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Kosong atau sesuai password MySQL
   ```
3. Test: `php test_db.php`

### Issue: CSS/Style tidak muncul

**Solution:**
1. Clear cache: **Ctrl + F5**
2. Cek BASE_URL di `config/config.php`
3. Pastikan ada internet (Bootstrap dari CDN)
4. Buka Network tab di browser DevTools

### Issue: Upload file gagal

**Solution:**
1. Cek permission folder `public/assets/uploads/`
2. Pastikan file < 5MB
3. Format: PDF, JPG, PNG saja
4. Cek error di browser Console (F12)

---

## 📋 Checklist Instalasi

Pastikan semua sudah ✅:

- [ ] Laragon running (Apache & MySQL hijau)
- [ ] Database `db_pengajuan_kredit` sudah dibuat
- [ ] File `schema.sql` sudah di-import
- [ ] Ada 4 user demo di database
- [ ] `config/database.php` sudah benar
- [ ] `BASE_URL` di `config/config.php` = `http://localhost/PengajuanKredit/public/`
- [ ] Folder `public/assets/uploads/` permissions OK
- [ ] Test database: `php test_db.php` → SUCCESS
- [ ] Bisa akses: `http://localhost/PengajuanKredit/public/`
- [ ] Bisa login dengan user demo
- [ ] Session warning **TIDAK ADA**

---

## 🎯 Next Steps

1. **Import database lengkap** (jika belum)
2. **Add demo users** (jika kurang)
3. **Test login** semua role
4. **Test workflow** lengkap:
   - Nasabah: Ajukan kredit
   - Petugas: Verifikasi
   - Analis: Analisis
   - Pimpinan: Approve
5. **Change password** default untuk production

---

## ✅ Status After Fix

- ✅ Session warning **FIXED**
- ✅ BASE_URL **UPDATED**
- ✅ UPLOAD_PATH **UPDATED**
- ✅ Database connection **WORKING**
- ✅ Demo users SQL **PROVIDED**

🎉 System ready to use!
