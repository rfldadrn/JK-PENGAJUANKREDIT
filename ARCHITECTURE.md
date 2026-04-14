# 📋 SYSTEM ARCHITECTURE & FILE STRUCTURE

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│  (Bootstrap 5 Views - Responsive & Modern UI)               │
│  - Auth Pages (Login, Register)                             │
│  - Nasabah Views (Dashboard, Multi-step Form, Tracking)     │
│  - Petugas Views (Verifikasi, Survei)                       │
│  - Analis Views (Analisis 5C, Scoring)                      │
│  - Pimpinan Views (Approval, Reports)                       │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                   APPLICATION LAYER                          │
│  (PHP Native - MVC Pattern)                                 │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │ Controllers │←→│   Models     │←→│   Core MVC   │       │
│  │ (Business   │  │  (Database   │  │  (Framework) │       │
│  │  Logic)     │  │   Access)    │  │              │       │
│  └─────────────┘  └──────────────┘  └──────────────┘       │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                              │
│  (MySQL Database)                                            │
│  - 12 Tables dengan relasi 1:1 dan 1:N                      │
│  - PDO with Prepared Statements                              │
│  - Stored in: db_pengajuan_kredit                           │
└─────────────────────────────────────────────────────────────┘
```

## 📁 Complete File Structure

```
PengajuanKredit/
│
├── 📁 app/
│   ├── 📁 controllers/
│   │   ├── AuthController.php          # Login, Register, Logout
│   │   ├── HomeController.php          # Landing page
│   │   ├── NasabahController.php       # Dashboard, Multi-step form, Tracking
│   │   ├── PetugasController.php       # Verifikasi, Survei
│   │   ├── AnalisController.php        # Analisis 5C, Auto-scoring
│   │   └── PimpinanController.php      # Persetujuan, Laporan
│   │
│   ├── 📁 models/
│   │   ├── User.php                    # User authentication & management
│   │   ├── Nasabah.php                 # Nasabah profile CRUD
│   │   ├── JenisKredit.php             # Produk kredit, validation
│   │   ├── PengajuanKredit.php         # Pengajuan CRUD, status flow
│   │   ├── Dokumen.php                 # Upload, verifikasi dokumen
│   │   ├── Agunan.php                  # CRUD agunan/jaminan
│   │   ├── Verifikasi.php              # Data verifikasi petugas
│   │   ├── Survei.php                  # Laporan survei lapangan
│   │   ├── AnalisisKredit.php          # Scoring 5C, DSR calculation
│   │   ├── Persetujuan.php             # Keputusan pimpinan
│   │   ├── Notifikasi.php              # Notification system
│   │   └── LogAktivitas.php            # Activity logging
│   │
│   └── 📁 views/
│       ├── 📁 auth/
│       │   ├── login.php
│       │   └── register.php
│       │
│       ├── 📁 nasabah/
│       │   ├── dashboard.php           # Stats, riwayat, notifikasi
│       │   ├── profile.php             # Form profil lengkap
│       │   ├── tracking.php            # List pengajuan
│       │   ├── tracking_detail.php     # Timeline status
│       │   └── 📁 pengajuan/
│       │       ├── step1.php           # Pilih kredit, data pinjaman
│       │       ├── step2.php           # Input agunan
│       │       ├── step3.php           # Upload dokumen
│       │       └── step4.php           # Review & submit
│       │
│       ├── 📁 petugas/
│       │   ├── dashboard.php
│       │   ├── verifikasi.php          # List pengajuan
│       │   ├── verifikasi_detail.php   # Form verifikasi
│       │   ├── survei.php              # List survei
│       │   └── survei_form.php         # Input survei lapangan
│       │
│       ├── 📁 analis/
│       │   ├── dashboard.php
│       │   ├── analisis.php            # List pengajuan
│       │   └── analisis_form.php       # Form scoring 5C
│       │
│       ├── 📁 pimpinan/
│       │   ├── dashboard.php
│       │   ├── persetujuan.php         # List pengajuan
│       │   └── persetujuan_form.php    # Form keputusan
│       │
│       └── 📁 layouts/
│           ├── dashboard.php           # Main layout with sidebar
│           ├── auth.php                # Auth layout
│           └── app.php                 # Public layout
│
├── 📁 config/
│   ├── config.php                      # App config (BASE_URL, timezone, etc)
│   └── database.php                    # DB credentials & PDO options
│
├── 📁 core/                            # MVC Framework Core
│   ├── App.php                         # Routing, URL parsing
│   ├── Controller.php                  # Base controller (validation, upload, etc)
│   ├── Model.php                       # Base model (CRUD operations)
│   └── Database.php                    # Singleton PDO connection
│
├── 📁 database/
│   └── schema.sql                      # Full database schema + seed data
│
├── 📁 public/                          # Document root
│   ├── index.php                       # Application entry point
│   ├── .htaccess                       # URL rewriting
│   └── 📁 assets/
│       └── 📁 uploads/                 # User uploaded files
│           ├── dokumen/                # KTP, KK, NPWP, etc
│           ├── agunan/                 # Sertifikat agunan
│           └── survei/                 # Foto survei
│
├── .htaccess                           # Root htaccess
├── README.md                           # Full documentation
├── INSTALL.md                          # Quick installation guide
├── test_db.php                         # Database connection test
└── ARCHITECTURE.md                     # This file
```

## 🎯 Design Patterns Used

### 1. MVC (Model-View-Controller)
- **Model**: Database interaction via PDO
- **View**: HTML templates with PHP
- **Controller**: Business logic & data flow

### 2. Singleton Pattern
- `Database.php` - Single PDO connection instance

### 3. Front Controller Pattern
- All requests → `public/index.php` → routing via `App.php`

### 4. Repository Pattern (Light)
- Models act as data repositories
- Separation of data access from business logic

### 5. Template Method Pattern
- Base `Controller` provides common methods
- Child controllers override specific behaviors

## 🔐 Security Implementation

### Authentication & Authorization
```php
// Session-based authentication
$_SESSION['user_id']
$_SESSION['role']

// Role-based access control
$this->requireRole(['nasabah', 'admin']);
```

### SQL Injection Prevention
```php
// PDO Prepared Statements
$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
```

### XSS Prevention
```php
// Input sanitization
$this->sanitize($_POST['data']);
htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
```

### Password Security
```php
// bcrypt hashing
password_hash($password, PASSWORD_BCRYPT);
password_verify($input, $hash);
```

### File Upload Security
```php
// Extension whitelist
['pdf', 'jpg', 'jpeg', 'png']

// Size limit
MAX_FILE_SIZE = 5242880 // 5MB

// Unique filename
uniqid() . '_' . time() . '.' . $ext
```

## 📊 Database Schema Summary

### Core Tables
| Table | Purpose | Key Relationships |
|-------|---------|-------------------|
| tb_users | User accounts & auth | 1:1 with tb_nasabah |
| tb_nasabah | Nasabah profile | 1:N with tb_pengajuan_kredit |
| tb_jenis_kredit | Produk kredit | 1:N with tb_pengajuan_kredit |
| tb_pengajuan_kredit | Main pengajuan | Hub for all workflow tables |

### Workflow Tables
| Table | Purpose | Trigger Point |
|-------|---------|---------------|
| tb_dokumen | Uploaded files | During pengajuan submission |
| tb_agunan | Collateral data | During pengajuan submission |
| tb_verifikasi | Document verification | By Petugas AO |
| tb_survei | Field survey | By Petugas AO |
| tb_analisis_kredit | Credit analysis | By Analis |
| tb_persetujuan | Final decision | By Pimpinan |

### Support Tables
| Table | Purpose |
|-------|---------|
| tb_notifikasi | User notifications |
| tb_log_aktivitas | Audit trail |

## 🔄 Workflow Engine

### Status Flow
```
draft              → Nasabah saves form
diajukan           → Nasabah submits (Step 4)
verifikasi         → Petugas starts verification
survei             → Verification approved, ready for survey
analisis           → Survey completed, ready for analysis
menunggu_keputusan → Analysis completed
disetujui          → Pimpinan approves
ditolak            → Pimpinan rejects
revisi             → Need revision
dicairkan          → Final disbursement
```

### Notification Triggers
- Status change → Nasabah notified
- Verification complete → Nasabah notified
- Survey complete → Nasabah notified
- Analysis complete → Nasabah notified
- Final decision → Nasabah notified

## 📈 Scoring System (5C Analysis)

### Weighted Scoring
```
Character  × 25% = Score_1
Capacity   × 30% = Score_2
Capital    × 15% = Score_3
Collateral × 20% = Score_4
Condition  × 10% = Score_5
─────────────────────────────
Total Score (skor_total)
```

### Auto-Calculations

#### DSR (Debt Service Ratio)
```php
DSR = (Angsuran / Penghasilan) × 100%
Max: 40%
```

#### Collateral Score (Auto)
```php
Coverage Ratio = (Total Agunan / Plafond) × 100%

if >= 150%: score = 100
if >= 125%: score = 85
if >= 100%: score = 70
if >= 75%:  score = 50
else:       score = 30
```

#### Capacity Score (Auto)
```php
if DSR <= 30%: score = 100
if DSR <= 35%: score = 85
if DSR <= 40%: score = 70
else:          score = 40
```

## 🎨 UI/UX Design Principles

### Color Scheme
- Primary: `#003d7a` (BRI Dark Blue)
- Secondary: `#0066cc` (BRI Blue)
- Success: `#28a745`
- Warning: `#ffc107`
- Danger: `#dc3545`

### Layout
- Sidebar width: `260px`
- Navbar height: `60px`
- Card border-radius: `10px`
- Clean spacing with Bootstrap utilities

### Typography
- Font: Segoe UI, Tahoma, Geneva, sans-serif
- Headings: 600 weight
- Body: 400 weight
- Small text: 13-14px

## 🚀 Performance Optimizations

1. **Single Database Connection** (Singleton)
2. **Lazy Loading** (Models loaded on demand)
3. **Minimal Queries** (JOIN instead of multiple queries)
4. **CDN for Assets** (Bootstrap from CDN)
5. **Session-based Auth** (No DB query per request)

## 📝 Coding Standards

### Naming Conventions
```php
// Controllers
NasabahController extends Controller

// Models
PengajuanKredit extends Model

// Methods
camelCase: createPengajuan()

// Variables
snake_case: $id_pengajuan

//Database columns
snake_case: jumlah_pinjaman

// Constants
UPPER_CASE: BASE_URL
```

### File Organization
- One class per file
- Filename = Class name
- Views match controller methods

---

**Document Version**: 1.0
**Last Updated**: April 2024
**Maintained by**: Development Team
