# INSTALASI FITUR LAPORAN

## Langkah 1: Install Composer Dependencies

Jalankan command berikut di root folder project:

```bash
composer install
```

Jika composer.json sudah ada, jalankan:

```bash
composer require tecnickcom/tcpdf:^6.6
composer require phpoffice/phpspreadsheet:^1.29
```

## Langkah 2: Verifikasi Autoload

Pastikan autoload composer sudah berjalan dengan benar. Di file `app/helpers/PdfHelper.php` dan `app/helpers/ExcelHelper.php` sudah ada:

```php
require_once __DIR__ . '/../../vendor/autoload.php';
```

## Langkah 3: Tambahkan Logo BRI (Opsional)

Jika ingin menampilkan logo di PDF, letakkan file logo di:
```
public/assets/img/logo-bri.png
```

Ukuran rekomendasi: 300x100 pixels

## Langkah 4: Akses Halaman Laporan

### Untuk Admin:
```
http://localhost/PengajuanKredit/public/admin/laporan
```

### Untuk Pimpinan:
```
http://localhost/PengajuanKredit/public/pimpinan/laporan
```

## Fitur Yang Tersedia

1. **Laporan Data Nasabah**
   - Menampilkan semua data nasabah dengan total pengajuan dan yang disetujui
   - Filter: tanggal, pekerjaan, search

2. **Laporan Data Pengajuan Kredit**
   - Menampilkan semua pengajuan kredit
   - Filter: tanggal, jenis kredit, status, search

3. **Laporan Data Petugas Bank**
   - Menampilkan data petugas, analis, dan pimpinan
   - Filter: tanggal, role, search

4. **Laporan Status Pengajuan (Disetujui/Ditolak)**
   - Menampilkan pengajuan yang sudah diputuskan
   - Filter: tanggal, jenis kredit, keputusan, search

## Export Options

- **Export PDF**: Generate PDF dengan format landscape, dilengkapi header, footer, dan ringkasan data
- **Export Excel**: Generate Excel dengan format yang rapi dan mudah diedit

## Troubleshooting

### Error: "Class 'TCPDF' not found"
Solusi: Jalankan `composer install` atau `composer update`

### Error: "Class 'PhpOffice\PhpSpreadsheet\Spreadsheet' not found"
Solusi: Jalankan `composer require phpoffice/phpspreadsheet`

### PDF tidak menampilkan logo
Solusi: Pastikan file logo ada di `public/assets/img/logo-bri.png`

### Data tidak muncul
Solusi: Pastikan ada data di database dan filter sudah benar
