<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengajuan Kredit BRI Lubuk Sikaping</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bri-blue: #0066CC;
            --bri-dark: #003d7a;
            --bri-light: #e8f4ff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--bri-blue) 0%, var(--bri-dark) 100%);
            color: white;
            padding: 100px 0;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.25rem;
            opacity: 0.9;
        }

        .feature-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: var(--bri-light);
            color: var(--bri-blue);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 1rem;
        }

        .product-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s;
            height: 100%;
        }

        .product-card:hover {
            border-color: var(--bri-blue);
            box-shadow: 0 8px 24px rgba(0, 102, 204, 0.15);
        }

        .btn-primary {
            background-color: var(--bri-blue);
            border-color: var(--bri-blue);
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--bri-dark);
            border-color: var(--bri-dark);
        }

        .btn-outline-light:hover {
            background-color: white;
            color: var(--bri-blue);
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--bri-blue) !important;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <i class="bi bi-bank2"></i> BRI Lubuk Sikaping
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#produk">Produk Kredit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>auth/login">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>auth/register">Daftar Sekarang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Ajukan Kredit dengan Mudah & Cepat</h1>
                    <p class="lead">Platform digital untuk pengajuan kredit BRI Lubuk Sikaping. Proses transparan, tracking real-time, keputusan lebih cepat.</p>
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>auth/register" class="btn btn-light btn-lg me-3">
                            <i class="bi bi-person-plus me-2"></i>Daftar Gratis
                        </a>
                        <a href="<?= BASE_URL ?>auth/login" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-credit-card" style="font-size: 200px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Kredit -->
    <section class="py-5" id="produk">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Produk Kredit Kami</h2>
                <p class="text-muted">Pilih produk kredit yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card product-card">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="bi bi-shop"></i>
                            </div>
                            <h5 class="fw-bold">KUR Mikro</h5>
                            <p class="text-muted">Kredit untuk usaha mikro dan kecil</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i>Plafond: Rp 1 juta - Rp 50 juta</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Bunga: 6% per tahun</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Tenor: 12-36 bulan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card product-card">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <h5 class="fw-bold">KUR Kecil</h5>
                            <p class="text-muted">Kredit untuk usaha kecil berkembang</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i>Plafond: Rp 50 juta - Rp 500 juta</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Bunga: 6% per tahun</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Tenor: 12-48 bulan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card product-card">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="bi bi-house-heart"></i>
                            </div>
                            <h5 class="fw-bold">Kredit Konsumtif</h5>
                            <p class="text-muted">Kredit untuk kebutuhan pribadi</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i>Plafond: Rp 5 juta - Rp 200 juta</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Bunga: 9% per tahun</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Tenor: 12-60 bulan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section class="py-5 bg-light" id="fitur">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kenapa Pilih Kami?</h2>
                <p class="text-muted">Kemudahan dan transparansi dalam setiap tahapan</p>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h5 class="fw-bold">Proses Cepat</h5>
                            <p class="text-muted">Pengajuan online 24/7 dengan proses yang lebih cepat</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-eye"></i>
                            </div>
                            <h5 class="fw-bold">Transparan</h5>
                            <p class="text-muted">Tracking status pengajuan secara real-time</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5 class="fw-bold">Aman</h5>
                            <p class="text-muted">Data Anda dilindungi dengan enkripsi tingkat tinggi</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-bell"></i>
                            </div>
                            <h5 class="fw-bold">Notifikasi</h5>
                            <p class="text-muted">Pemberitahuan otomatis setiap perubahan status</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5">
        <div class="container">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, var(--bri-blue), var(--bri-dark));">
                <div class="card-body text-center text-white p-5">
                    <h2 class="fw-bold mb-3">Siap Mengajukan Kredit?</h2>
                    <p class="lead mb-4">Daftar sekarang dan mulai proses pengajuan kredit Anda</p>
                    <a href="<?= BASE_URL ?>auth/register" class="btn btn-light btn-lg">
                        <i class="bi bi-arrow-right-circle me-2"></i>Mulai Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-bank2"></i> BRI Lubuk Sikaping</h5>
                    <p class="text-muted">Sistem Informasi Pengajuan Kredit Berbasis Web</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-muted">&copy; 2026 BRI Lubuk Sikaping. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
ob_end_flush();
?>
