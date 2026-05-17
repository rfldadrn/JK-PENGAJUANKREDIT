# DOKUMENTASI SISTEM LAPORAN

## Overview
Sistem laporan telah berhasil ditambahkan untuk role Admin dan Pimpinan dengan 4 jenis laporan:

1. **Laporan Data Nasabah**
2. **Laporan Data Pengajuan Kredit**
3. **Laporan Data Petugas Bank**
4. **Laporan Status Pengajuan Kredit (Disetujui/Ditolak)**

## Fitur Utama

### 1. Filter Dinamis
- Filter berdasarkan jenis laporan
- Filter tanggal (mulai dan akhir)
- Filter spesifik per jenis laporan:
  - Nasabah: pekerjaan
  - Pengajuan: status, jenis kredit
  - Petugas: role
  - Status: keputusan, jenis kredit
- Search global

### 2. Export
- **Export PDF**: 
  - Format landscape
  - Header dengan logo BRI
  - Footer dengan nomor halaman dan tanggal cetak
  - Tabel data yang rapi
  - Ringkasan statistik
  - Data sesuai filter yang dipilih

- **Export Excel**:
  - Format xlsx
  - Header dengan judul dan periode
  - Tabel dengan styling
  - Ringkasan statistik
  - Data numerik dapat diedit

### 3. Tampilan Data
- Tabel responsif
- Badge untuk status
- Format currency untuk nominal
- Paginasi otomatis di PDF (multi-halaman)

## File yang Dibuat/Dimodifikasi

### 1. Composer & Dependencies
- `composer.json` - Dependency management
- Installed: TCPDF 6.11.3 dan PhpSpreadsheet 1.30.4

### 2. Helpers
- `app/helpers/PdfHelper.php` - Class untuk generate PDF
- `app/helpers/ExcelHelper.php` - Class untuk generate Excel

### 3. Models
- `app/models/Laporan.php` - Model dengan query untuk 4 jenis laporan

### 4. Controllers
- `app/controllers/AdminController.php` - Updated dengan method laporan, exportPdf, exportExcel
- `app/controllers/PimpinanController.php` - Updated dengan method laporan, exportPdf, exportExcel

### 5. Views
- `app/views/admin/laporan.php` - UI laporan untuk admin
- `app/views/pimpinan/laporan.php` - UI laporan untuk pimpinan

### 6. Documentation
- `LAPORAN_SETUP.md` - Panduan instalasi dan troubleshooting
- `LAPORAN_DOKUMENTASI.md` - Dokumentasi lengkap fitur

## Cara Penggunaan

### 1. Akses Halaman Laporan
- Admin: `/admin/laporan`
- Pimpinan: `/pimpinan/laporan`

### 2. Pilih Jenis Laporan
Gunakan dropdown "Jenis Laporan" untuk memilih:
- Data Nasabah
- Data Pengajuan Kredit
- Data Petugas Bank
- Status Pengajuan (Disetujui/Ditolak)

### 3. Terapkan Filter
- Pilih tanggal mulai dan akhir (opsional)
- Pilih filter tambahan sesuai jenis laporan
- Masukkan keyword pencarian (opsional)
- Klik "Tampilkan Laporan"

### 4. Export Data
- Klik "Export PDF" untuk download PDF
- Klik "Export Excel" untuk download Excel
- File akan otomatis terdownload dengan nama:
  - Format: `laporan_[jenis]_[tanggal].pdf/xlsx`
  - Contoh: `laporan_data_nasabah_20260517143022.pdf`

## Technical Details

### Query Optimization
- Menggunakan JOIN untuk menggabungkan data dari multiple tables
- Parameterized queries untuk mencegah SQL injection
- Filter dinamis berdasarkan parameter yang diberikan

### PDF Generation
- Library: TCPDF 6.11.3
- Paper: Landscape A4
- Custom header dan footer
- Auto page break
- Unicode support

### Excel Generation
- Library: PhpSpreadsheet 1.30.4
- Format: XLSX (Excel 2007+)
- Styling: Header berwarna, borders, alignment
- Auto-width columns

### Security
- Role-based access (requireRole)
- Input sanitization
- SQL injection prevention dengan prepared statements
- XSS prevention dengan htmlspecialchars di view

## Database Schema
Laporan menggunakan tabel-tabel berikut:
- `users` - Data user/nasabah/petugas
- `nasabah` - Data detail nasabah
- `pengajuan_kredit` - Data pengajuan
- `jenis_kredit` - Master produk kredit
- `persetujuan` - Data keputusan pimpinan
- `verifikasi` - Data verifikasi petugas
- `survei` - Data survei petugas
- `analisis_kredit` - Data analisis analis

## API Endpoints

### GET /admin/laporan atau /pimpinan/laporan
Query parameters:
- `jenis_laporan`: nasabah|pengajuan|petugas|status
- `start_date`: YYYY-MM-DD
- `end_date`: YYYY-MM-DD
- `status`: (untuk pengajuan)
- `jenis_kredit`: ID jenis kredit
- `keputusan`: disetujui|ditolak|revisi
- `role`: petugas|analis|pimpinan
- `pekerjaan`: string
- `search`: string

### GET /admin/exportPdf atau /pimpinan/exportPdf
Query parameters: (sama seperti laporan)
Response: PDF file download

### GET /admin/exportExcel atau /pimpinan/exportExcel
Query parameters: (sama seperti laporan)
Response: Excel file download

## Maintenance & Updates

### Menambah Jenis Laporan Baru
1. Tambahkan method query di `app/models/Laporan.php`
2. Tambahkan case di switch statement di controller
3. Tambahkan option di dropdown view
4. Update export methods untuk handle jenis baru

### Mengubah Format PDF
Edit method di `app/helpers/PdfHelper.php`:
- `Header()` - Untuk header PDF
- `Footer()` - Untuk footer PDF
- `createTable()` - Untuk styling tabel

### Mengubah Format Excel
Edit method di `app/helpers/ExcelHelper.php`:
- `setHeader()` - Untuk header Excel
- `createTable()` - Untuk styling tabel

## Browser Compatibility
- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- IE11: Not supported (ES6 JavaScript)

## Performance Notes
- Large datasets (>1000 rows) may take longer to export
- PDF generation is slower than Excel
- Recommend adding pagination for very large reports
- Consider adding background job for very large exports

## Future Enhancements
- [ ] Add charts/graphs to reports
- [ ] Add email report feature
- [ ] Add scheduled reports
- [ ] Add report templates
- [ ] Add custom column selection
- [ ] Add report caching
