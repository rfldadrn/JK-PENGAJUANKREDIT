<?php

class AdminController extends Controller
{
    private $userModel;
    private $jenisKreditModel;
    private $pengajuanModel;
    private $logModel;
    private $laporanModel;

    public function __construct()
    {
        $this->requireRole(['admin']);
        $this->userModel = $this->model('User');
        $this->jenisKreditModel = $this->model('JenisKredit');
        $this->pengajuanModel = $this->model('PengajuanKredit');
        $this->logModel = $this->model('LogAktivitas');
        $this->laporanModel = $this->model('Laporan');
    }

    public function index()
    {
        $this->redirect('admin/dashboard');
    }

    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_users' => $this->getTotalUsers(),
            'total_nasabah' => $this->userModel->countByRole('nasabah'),
            'total_pengajuan' => $this->getTotalPengajuan(),
            'pengajuan_aktif' => $this->getActivePengajuan()
        ];

        // Get recent activities
        $recentLogs = $this->logModel->getAllLog(10);

        // Get recent pengajuan
        $recentPengajuan = $this->getRecentPengajuan(5);

        $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentLogs' => $recentLogs,
            'recentPengajuan' => $recentPengajuan
        ]);
    }

    public function users()
    {
        $users = $this->userModel->all('created_at DESC');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'create') {
                $data = [
                    'nama_lengkap' => $this->sanitize($_POST['nama_lengkap']),
                    'email' => $this->sanitize($_POST['email']),
                    'password' => $_POST['password'],
                    'no_hp' => $this->sanitize($_POST['no_hp']),
                    'role' => $this->sanitize($_POST['role']),
                    'status_akun' => 'aktif'
                ];

                $errors = $this->validate($_POST, [
                    'nama_lengkap' => 'required|min:3',
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                    'no_hp' => 'required',
                    'role' => 'required'
                ]);

                if ($this->userModel->findByEmail($data['email'])) {
                    $errors['email'] = 'Email sudah terdaftar';
                }

                if (empty($errors)) {
                    if ($this->userModel->register($data)) {
                        $_SESSION['success'] = 'User berhasil ditambahkan';
                        $this->redirect('admin/users');
                    }
                }
            } elseif ($_POST['action'] === 'update_status') {
                $idUser = $_POST['id_user'];
                $status = $_POST['status_akun'];

                $this->userModel->update($idUser, ['status_akun' => $status], 'id_user');
                $_SESSION['success'] = 'Status user berhasil diupdate';
                $this->redirect('admin/users');
            }
        }

        $this->view('admin/users', ['users' => $users]);
    }

    public function produk()
    {
        $produkKredit = $this->jenisKreditModel->all('created_at DESC');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'create') {
                $data = [
                    'nama_kredit' => $this->sanitize($_POST['nama_kredit']),
                    'kode_kredit' => $this->sanitize($_POST['kode_kredit']),
                    'deskripsi' => $this->sanitize($_POST['deskripsi']),
                    'plafond_min' => $this->sanitize($_POST['plafond_min']),
                    'plafond_max' => $this->sanitize($_POST['plafond_max']),
                    'bunga_per_tahun' => $this->sanitize($_POST['bunga_per_tahun']),
                    'tenor_min' => $this->sanitize($_POST['tenor_min']),
                    'tenor_max' => $this->sanitize($_POST['tenor_max']),
                    'syarat_dokumen' => json_encode(array_filter(explode(',', $_POST['syarat_dokumen']))),
                    'status' => 'aktif',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $errors = $this->validate($_POST, [
                    'nama_kredit' => 'required',
                    'kode_kredit' => 'required',
                    'plafond_min' => 'required|numeric',
                    'plafond_max' => 'required|numeric',
                    'bunga_per_tahun' => 'required|numeric',
                    'tenor_min' => 'required|numeric',
                    'tenor_max' => 'required|numeric'
                ]);

                if (empty($errors)) {
                    $this->jenisKreditModel->insert($data);
                    $_SESSION['success'] = 'Produk kredit berhasil ditambahkan';
                    $this->redirect('admin/produk');
                }
            } elseif ($_POST['action'] === 'update_status') {
                $id = $_POST['id_jenis_kredit'];
                $status = $_POST['status'];

                $this->jenisKreditModel->update($id, ['status' => $status], 'id_jenis_kredit');
                $_SESSION['success'] = 'Status produk berhasil diupdate';
                $this->redirect('admin/produk');
            }
        }

        $this->view('admin/produk', ['produkKredit' => $produkKredit]);
    }

    public function laporan()
    {
        // Get filter parameters
        $filters = [
            'jenis_laporan' => $_GET['jenis_laporan'] ?? 'nasabah',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? '',
            'status' => $_GET['status'] ?? '',
            'jenis_kredit' => $_GET['jenis_kredit'] ?? '',
            'keputusan' => $_GET['keputusan'] ?? '',
            'role' => $_GET['role'] ?? '',
            'pekerjaan' => $_GET['pekerjaan'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        // Get data based on report type
        $data = [];
        $stats = $this->laporanModel->getStatistics();
        
        switch ($filters['jenis_laporan']) {
            case 'nasabah':
                $data = $this->laporanModel->getLaporanNasabah($filters);
                break;
            case 'pengajuan':
                $data = $this->laporanModel->getLaporanPengajuan($filters);
                break;
            case 'petugas':
                $data = $this->laporanModel->getLaporanPetugas($filters);
                break;
            case 'status':
                $data = $this->laporanModel->getLaporanStatusPengajuan($filters);
                break;
        }
        
        // Get master data for filters
        $jenisKredit = $this->jenisKreditModel->all();
        
        $this->view('admin/laporan', [
            'filters' => $filters,
            'data' => $data,
            'stats' => $stats,
            'jenisKredit' => $jenisKredit
        ]);
    }
    
    public function exportPdf()
    {
        require_once __DIR__ . '/../helpers/PdfHelper.php';
        
        // Get filter parameters
        $filters = [
            'jenis_laporan' => $_GET['jenis_laporan'] ?? 'nasabah',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? '',
            'status' => $_GET['status'] ?? '',
            'jenis_kredit' => $_GET['jenis_kredit'] ?? '',
            'keputusan' => $_GET['keputusan'] ?? '',
            'role' => $_GET['role'] ?? '',
            'pekerjaan' => $_GET['pekerjaan'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        // Get data based on report type
        $data = [];
        $reportTitle = '';
        $headers = [];
        $rows = [];
        
        switch ($filters['jenis_laporan']) {
            case 'nasabah':
                $data = $this->laporanModel->getLaporanNasabah($filters);
                $reportTitle = 'LAPORAN DATA NASABAH';
                $headers = ['No', 'Nama Lengkap', 'NIK', 'Email', 'No. Telepon', 'Pekerjaan', 'Penghasilan', 'Total Pengajuan', 'Disetujui'];
                foreach ($data as $index => $row) {
                    $rows[] = [
                        $index + 1,
                        $row['nama_lengkap'],
                        $row['nik'],
                        $row['email'],
                        $row['no_telepon'],
                        $row['pekerjaan'],
                        'Rp ' . number_format($row['penghasilan_per_bulan'], 0, ',', '.'),
                        $row['total_pengajuan'],
                        $row['pengajuan_disetujui']
                    ];
                }
                break;
                
            case 'pengajuan':
                $data = $this->laporanModel->getLaporanPengajuan($filters);
                $reportTitle = 'LAPORAN DATA PENGAJUAN KREDIT';
                $headers = ['No', 'No. Pengajuan', 'Nasabah', 'Produk', 'Jumlah Pinjaman', 'Tenor', 'Status', 'Tanggal'];
                foreach ($data as $index => $row) {
                    $rows[] = [
                        $index + 1,
                        $row['no_pengajuan'],
                        $row['nama_nasabah'],
                        $row['nama_produk'],
                        'Rp ' . number_format($row['jumlah_pinjaman'], 0, ',', '.'),
                        $row['tenor'] . ' bln',
                        ucfirst($row['status_pengajuan']),
                        date('d/m/Y', strtotime($row['tanggal_pengajuan']))
                    ];
                }
                break;
                
            case 'petugas':
                $data = $this->laporanModel->getLaporanPetugas($filters);
                $reportTitle = 'LAPORAN DATA PETUGAS BANK';
                $headers = ['No', 'Nama Lengkap', 'Email', 'Role', 'No. Telepon', 'Total Tugas', 'Terdaftar'];
                foreach ($data as $index => $row) {
                    $totalTugas = $row['total_verifikasi'] + $row['total_survei'] + $row['total_analisis'] + $row['total_persetujuan'];
                    $rows[] = [
                        $index + 1,
                        $row['nama_lengkap'],
                        $row['email'],
                        ucfirst($row['role']),
                        $row['no_telepon'],
                        $totalTugas,
                        date('d/m/Y', strtotime($row['created_at']))
                    ];
                }
                break;
                
            case 'status':
                $data = $this->laporanModel->getLaporanStatusPengajuan($filters);
                $reportTitle = 'LAPORAN STATUS PENGAJUAN KREDIT';
                $headers = ['No', 'No. Pengajuan', 'Nasabah', 'Produk', 'Jumlah', 'Keputusan', 'Tanggal Keputusan', 'Pimpinan'];
                foreach ($data as $index => $row) {
                    $rows[] = [
                        $index + 1,
                        $row['no_pengajuan'],
                        $row['nama_nasabah'],
                        $row['nama_produk'],
                        'Rp ' . number_format($row['jumlah_pinjaman'], 0, ',', '.'),
                        strtoupper($row['keputusan']),
                        date('d/m/Y', strtotime($row['tanggal_keputusan'])),
                        $row['nama_pimpinan']
                    ];
                }
                break;
        }
        
        // Create PDF
        $pdf = new PdfHelper('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Sistem Pengajuan Kredit BRI');
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle($reportTitle);
        
        // Set report title and period
        $pdf->setReportTitle($reportTitle);
        if ($filters['start_date'] && $filters['end_date']) {
            $period = 'Periode: ' . date('d/m/Y', strtotime($filters['start_date'])) . ' - ' . date('d/m/Y', strtotime($filters['end_date']));
            $pdf->setReportPeriod($period);
        }
        
        // Set margins
        $pdf->SetMargins(15, 50, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 20);
        
        // Add a page
        $pdf->AddPage();
        
        // Create table
        $pdf->createTable($headers, $rows);
        
        // Add summary
        $stats = $this->laporanModel->getStatistics();
        $summary = [];
        
        switch ($filters['jenis_laporan']) {
            case 'nasabah':
                $summary = [
                    'Total Nasabah' => number_format(count($data)),
                    'Total Pengajuan Keseluruhan' => number_format($stats['total_pengajuan'])
                ];
                break;
            case 'pengajuan':
                $summary = [
                    'Total Pengajuan' => number_format(count($data)),
                    'Total Nilai Pengajuan' => 'Rp ' . number_format(array_sum(array_column($data, 'jumlah_pinjaman')), 0, ',', '.')
                ];
                break;
            case 'petugas':
                $summary = [
                    'Total Petugas' => number_format(count($data))
                ];
                break;
            case 'status':
                $disetujui = array_filter($data, function($row) { return $row['keputusan'] == 'disetujui'; });
                $ditolak = array_filter($data, function($row) { return $row['keputusan'] == 'ditolak'; });
                $summary = [
                    'Total Data' => number_format(count($data)),
                    'Disetujui' => number_format(count($disetujui)),
                    'Ditolak' => number_format(count($ditolak))
                ];
                break;
        }
        
        if (!empty($summary)) {
            $pdf->addSummary($summary);
        }
        
        // Close and output PDF document
        $filename = strtolower(str_replace(' ', '_', $reportTitle)) . '_' . date('YmdHis') . '.pdf';
        $pdf->Output($filename, 'D');
    }
    
    public function exportExcel()
    {
        require_once __DIR__ . '/../helpers/ExcelHelper.php';
        
        // Get filter parameters
        $filters = [
            'jenis_laporan' => $_GET['jenis_laporan'] ?? 'nasabah',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? '',
            'status' => $_GET['status'] ?? '',
            'jenis_kredit' => $_GET['jenis_kredit'] ?? '',
            'keputusan' => $_GET['keputusan'] ?? '',
            'role' => $_GET['role'] ?? '',
            'pekerjaan' => $_GET['pekerjaan'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        // Get data based on report type
        $data = [];
        $reportTitle = '';
        $headers = [];
        $rows = [];
        
        switch ($filters['jenis_laporan']) {
            case 'nasabah':
                $data = $this->laporanModel->getLaporanNasabah($filters);
                $reportTitle = 'LAPORAN DATA NASABAH';
                $headers = ['No', 'Nama Lengkap', 'NIK', 'Email', 'No. Telepon', 'Pekerjaan', 'Penghasilan', 'Total Pengajuan', 'Disetujui'];
                foreach ($data as $index => $row) {
                    $rows[] = [
                        $index + 1,
                        $row['nama_lengkap'],
                        "'" . $row['nik'], // Prefix with ' to treat as text
                        $row['email'],
                        $row['no_telepon'],
                        $row['pekerjaan'],
                        $row['penghasilan_per_bulan'],
                        $row['total_pengajuan'],
                        $row['pengajuan_disetujui']
                    ];
                }
                break;
                
            case 'pengajuan':
                $data = $this->laporanModel->getLaporanPengajuan($filters);
                $reportTitle = 'LAPORAN DATA PENGAJUAN KREDIT';
                $headers = ['No', 'No. Pengajuan', 'Nasabah', 'Produk', 'Jumlah Pinjaman', 'Tenor (Bulan)', 'Status', 'Tanggal'];
                foreach ($data as $index => $row) {
                    $rows[] = [
                        $index + 1,
                        $row['no_pengajuan'],
                        $row['nama_nasabah'],
                        $row['nama_produk'],
                        $row['jumlah_pinjaman'],
                        $row['tenor'],
                        ucfirst($row['status_pengajuan']),
                        $row['tanggal_pengajuan']
                    ];
                }
                break;
                
            case 'petugas':
                $data = $this->laporanModel->getLaporanPetugas($filters);
                $reportTitle = 'LAPORAN DATA PETUGAS BANK';
                $headers = ['No', 'Nama Lengkap', 'Email', 'Role', 'No. Telepon', 'Total Tugas', 'Terdaftar'];
                foreach ($data as $index => $row) {
                    $totalTugas = $row['total_verifikasi'] + $row['total_survei'] + $row['total_analisis'] + $row['total_persetujuan'];
                    $rows[] = [
                        $index + 1,
                        $row['nama_lengkap'],
                        $row['email'],
                        ucfirst($row['role']),
                        $row['no_telepon'],
                        $totalTugas,
                        $row['created_at']
                    ];
                }
                break;
                
            case 'status':
                $data = $this->laporanModel->getLaporanStatusPengajuan($filters);
                $reportTitle = 'LAPORAN STATUS PENGAJUAN KREDIT';
                $headers = ['No', 'No. Pengajuan', 'Nasabah', 'Produk', 'Jumlah Pinjaman', 'Keputusan', 'Tanggal Keputusan', 'Pimpinan', 'Alasan Keputusan'];
                foreach ($data as $index => $row) {
                    $rows[] = [
                        $index + 1,
                        $row['no_pengajuan'],
                        $row['nama_nasabah'],
                        $row['nama_produk'],
                        $row['jumlah_pinjaman'],
                        strtoupper($row['keputusan']),
                        $row['tanggal_keputusan'],
                        $row['nama_pimpinan'],
                        $row['alasan_keputusan']
                    ];
                }
                break;
        }
        
        // Create Excel
        $excel = new ExcelHelper();
        
        // Set header
        $period = '';
        if ($filters['start_date'] && $filters['end_date']) {
            $period = 'Periode: ' . date('d/m/Y', strtotime($filters['start_date'])) . ' - ' . date('d/m/Y', strtotime($filters['end_date']));
        }
        $excel->setHeader($reportTitle, $period);
        
        // Create table
        $excel->createTable($headers, $rows);
        
        // Add summary
        $stats = $this->laporanModel->getStatistics();
        $summary = [];
        
        switch ($filters['jenis_laporan']) {
            case 'nasabah':
                $summary = [
                    'Total Nasabah' => count($data),
                    'Total Pengajuan Keseluruhan' => $stats['total_pengajuan']
                ];
                break;
            case 'pengajuan':
                $summary = [
                    'Total Pengajuan' => count($data),
                    'Total Nilai Pengajuan' => 'Rp ' . number_format(array_sum(array_column($data, 'jumlah_pinjaman')), 0, ',', '.')
                ];
                break;
            case 'petugas':
                $summary = [
                    'Total Petugas' => count($data)
                ];
                break;
            case 'status':
                $disetujui = array_filter($data, function($row) { return $row['keputusan'] == 'disetujui'; });
                $ditolak = array_filter($data, function($row) { return $row['keputusan'] == 'ditolak'; });
                $summary = [
                    'Total Data' => count($data),
                    'Disetujui' => count($disetujui),
                    'Ditolak' => count($ditolak)
                ];
                break;
        }
        
        if (!empty($summary)) {
            $excel->addSummary($summary);
        }
        
        // Download
        $filename = strtolower(str_replace(' ', '_', $reportTitle)) . '_' . date('YmdHis') . '.xlsx';
        $excel->download($filename);
    }

    // Helper methods
    private function getTotalUsers()
    {
        $stmt = $this->userModel->query("SELECT COUNT(*) as total FROM tb_users");
        return $stmt->fetch()['total'];
    }

    private function getTotalPengajuan()
    {
        $stmt = $this->pengajuanModel->query("SELECT COUNT(*) as total FROM tb_pengajuan_kredit WHERE status_pengajuan != 'draft'");
        return $stmt->fetch()['total'];
    }

    private function getActivePengajuan()
    {
        $stmt = $this->pengajuanModel->query("SELECT COUNT(*) as total FROM tb_pengajuan_kredit
                WHERE status_pengajuan NOT IN ('draft', 'disetujui', 'ditolak', 'dicairkan')");
        return $stmt->fetch()['total'];
    }

    private function getRecentPengajuan($limit = 5)
    {
        $sql = "SELECT p.*, jk.nama_kredit, n.*, u.nama_lengkap, u.email
                FROM tb_pengajuan_kredit p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                INNER JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
                INNER JOIN tb_users u ON n.id_user = u.id_user
                WHERE p.status_pengajuan != 'draft'
                ORDER BY p.created_at DESC
                LIMIT ?";
        $stmt = $this->pengajuanModel->query($sql, [$limit]);
        return $stmt->fetchAll();
    }

    private function getMonthlyStats()
    {
        $sql = "SELECT DATE_FORMAT(tanggal_pengajuan, '%Y-%m') as bulan,
                       COUNT(*) as jumlah,
                       SUM(jumlah_pinjaman) as total_nilai
                FROM tb_pengajuan_kredit
                WHERE tanggal_pengajuan IS NOT NULL
                GROUP BY DATE_FORMAT(tanggal_pengajuan, '%Y-%m')
                ORDER BY bulan DESC
                LIMIT 12";
        $stmt = $this->pengajuanModel->query($sql);
        return $stmt->fetchAll();
    }

    private function getStatusDistribution()
    {
        $sql = "SELECT status_pengajuan, COUNT(*) as jumlah
                FROM tb_pengajuan_kredit
                WHERE status_pengajuan != 'draft'
                GROUP BY status_pengajuan";
        $stmt = $this->pengajuanModel->query($sql);
        return $stmt->fetchAll();
    }

    private function getProductStats()
    {
        $sql = "SELECT jk.nama_kredit, COUNT(p.id_pengajuan) as jumlah_pengajuan,
                       SUM(p.jumlah_pinjaman) as total_nilai
                FROM tb_jenis_kredit jk
                LEFT JOIN tb_pengajuan_kredit p ON jk.id_jenis_kredit = p.id_jenis_kredit
                GROUP BY jk.id_jenis_kredit";
        $stmt = $this->jenisKreditModel->query($sql);
        return $stmt->fetchAll();
    }
}
