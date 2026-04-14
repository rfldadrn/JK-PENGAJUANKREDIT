<?php
// Menu items for each role
// Format: ['url' => 'controller/method', 'icon' => 'bi-icon-name', 'label' => 'Menu Label']

$menus = [
    'admin' => [
        ['url' => 'admin/dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'admin/users', 'icon' => 'bi-people', 'label' => 'Manajemen User'],
        ['url' => 'admin/produk', 'icon' => 'bi-box-seam', 'label' => 'Produk Kredit'],
        ['url' => 'admin/laporan', 'icon' => 'bi-graph-up', 'label' => 'Laporan'],
    ],
    'nasabah' => [
        ['url' => 'nasabah/dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'nasabah/pengajuan', 'icon' => 'bi-file-earmark-text', 'label' => 'Pengajuan Kredit'],
        ['url' => 'nasabah/ajukanBaru', 'icon' => 'bi-plus-circle', 'label' => 'Ajukan Kredit Baru'],
        ['url' => 'nasabah/profile', 'icon' => 'bi-person', 'label' => 'Profil Saya'],
        ['url' => 'nasabah/notifikasi', 'icon' => 'bi-bell', 'label' => 'Notifikasi'],
    ],
    'petugas' => [
        ['url' => 'petugas/dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'petugas/verifikasi', 'icon' => 'bi-file-earmark-check', 'label' => 'Verifikasi Dokumen'],
        ['url' => 'petugas/survei', 'icon' => 'bi-geo-alt', 'label' => 'Survei Lapangan'],
        ['url' => 'petugas/riwayat', 'icon' => 'bi-clock-history', 'label' => 'Riwayat'],
    ],
    'analis' => [
        ['url' => 'analis/dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'analis/analisis', 'icon' => 'bi-calculator', 'label' => 'Analisis Kredit'],
        ['url' => 'analis/riwayat', 'icon' => 'bi-clock-history', 'label' => 'Riwayat Analisis'],
    ],
    'pimpinan' => [
        ['url' => 'pimpinan/dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'pimpinan/persetujuan', 'icon' => 'bi-check-circle', 'label' => 'Persetujuan'],
        ['url' => 'pimpinan/riwayat', 'icon' => 'bi-clock-history', 'label' => 'Riwayat Keputusan'],
        ['url' => 'pimpinan/laporan', 'icon' => 'bi-graph-up', 'label' => 'Laporan'],
    ],
];

// Get current URL path for active state
$currentUrl = $_SERVER['REQUEST_URI'];
$currentPath = parse_url($currentUrl, PHP_URL_PATH);
$currentPath = str_replace('/PengajuanKredit/public/', '', $currentPath);

// Get menu for current role
$userRole = $_SESSION['role'] ?? 'nasabah';
$roleMenus = $menus[$userRole] ?? [];

// Generate menu HTML
$menuHtml = '';
foreach ($roleMenus as $menu) {
    $isActive = (strpos($currentPath, $menu['url']) === 0) ? 'active' : '';
    $menuHtml .= sprintf(
        '<li><a class="nav-link %s" href="%s%s"><i class="%s"></i> %s</a></li>',
        $isActive,
        BASE_URL,
        $menu['url'],
        $menu['icon'],
        $menu['label']
    );
}

return $menuHtml;
