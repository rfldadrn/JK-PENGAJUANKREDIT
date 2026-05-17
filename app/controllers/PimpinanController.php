<?php

class PimpinanController extends Controller
{
    private $pengajuanModel;
    private $analisisModel;
    private $persetujuanModel;
    private $verifikasiModel;
    private $surveiModel;
    private $notifikasiModel;
    private $jenisKreditModel;
    private $laporanModel;

    public function __construct()
    {
        $this->requireRole(['pimpinan']);
        $this->pengajuanModel = $this->model('PengajuanKredit');
        $this->analisisModel = $this->model('AnalisisKredit');
        $this->persetujuanModel = $this->model('Persetujuan');
        $this->verifikasiModel = $this->model('Verifikasi');
        $this->surveiModel = $this->model('Survei');
        $this->notifikasiModel = $this->model('Notifikasi');
        $this->jenisKreditModel = $this->model('JenisKredit');
        $this->laporanModel = $this->model('Laporan');
    }

    public function index()
    {
        $this->redirect('pimpinan/dashboard');
    }

    public function dashboard()
    {
        $stats = [
            'menunggu' => $this->pengajuanModel->countByStatus('menunggu_keputusan'),
            'disetujui' => $this->persetujuanModel->countByKeputusan('disetujui'),
            'ditolak' => $this->persetujuanModel->countByKeputusan('ditolak'),
            'revisi' => $this->persetujuanModel->countByKeputusan('revisi')
        ];

        $pengajuanBaru = $this->pengajuanModel->getPengajuanByStatus('menunggu_keputusan');
        $pengajuanDisetujui = $this->pengajuanModel->getPengajuanByStatus('disetujui');

        $this->view('pimpinan/dashboard', [
            'stats' => $stats,
            'pengajuanBaru' => array_slice($pengajuanBaru, 0, 5),
            'pengajuanDisetujui' => array_slice($pengajuanDisetujui, 0, 5)
        ]);
    }

    public function persetujuan($idPengajuan = null)
    {
        if ($idPengajuan) {
            $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

            if (!$pengajuan || !in_array($pengajuan['status_pengajuan'], ['menunggu_keputusan', 'disetujui', 'ditolak'])) {
                $this->redirect('pimpinan/persetujuan');
            }

            $verifikasi = $this->verifikasiModel->getVerifikasiByPengajuan($idPengajuan);
            $survei = $this->surveiModel->getSurveiByPengajuan($idPengajuan);
            $analisis = $this->analisisModel->getAnalisisByPengajuan($idPengajuan);
            $persetujuan = $this->persetujuanModel->getPersetujuanByPengajuan($idPengajuan);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $keputusan = $this->sanitize($_POST['keputusan']);

                $data = [
                    'id_pengajuan' => $idPengajuan,
                    'id_pimpinan' => $_SESSION['user_id'],
                    'keputusan' => $keputusan,
                    'alasan_keputusan' => $this->sanitize($_POST['alasan_keputusan'])
                ];

                if ($keputusan === 'disetujui') {
                    $plafond = (float) $this->sanitize($_POST['plafond_disetujui']);
                    $tenor = (int) $this->sanitize($_POST['tenor_disetujui']);
                    $bunga = (float) $this->sanitize($_POST['bunga_disetujui']);

                    $data['plafond_disetujui'] = $plafond;
                    $data['tenor_disetujui'] = $tenor;
                    $data['bunga_disetujui'] = $bunga;
                    $data['angsuran_per_bulan'] = $this->persetujuanModel->hitungAngsuran($plafond, $bunga, $tenor);
                    $data['syarat_pencairan'] = $this->sanitize($_POST['syarat_pencairan'] ?? '');
                }

                $errors = $this->validate($_POST, [
                    'keputusan' => 'required',
                    'alasan_keputusan' => 'required'
                ]);

                if ($keputusan === 'disetujui') {
                    $additionalRules = [
                        'plafond_disetujui' => 'required|numeric',
                        'tenor_disetujui' => 'required|numeric',
                        'bunga_disetujui' => 'required|numeric'
                    ];
                    $errors = array_merge($errors, $this->validate($_POST, $additionalRules));
                }

                if (!empty($errors)) {
                    $this->view('pimpinan/persetujuan_form', [
                        'errors' => $errors,
                        'pengajuan' => $pengajuan,
                        'verifikasi' => $verifikasi,
                        'survei' => $survei,
                        'analisis' => $analisis,
                        'persetujuan' => $persetujuan,
                        'old' => $data
                    ]);
                    return;
                }

                if ($persetujuan) {
                    $this->persetujuanModel->updatePersetujuan($persetujuan['id_persetujuan'], $data);
                } else {
                    $this->persetujuanModel->createPersetujuan($data);
                }

                // Update status pengajuan
                $newStatus = $keputusan;
                $this->pengajuanModel->update($idPengajuan, [
                    'status_pengajuan' => $newStatus,
                    'tanggal_keputusan' => date('Y-m-d H:i:s')
                ], 'id_pengajuan');

                // Send notification
                $notifMessage = match($keputusan) {
                    'disetujui' => "Selamat! Pengajuan kredit Anda telah disetujui dengan plafond Rp " .
                                   number_format($plafond, 0, ',', '.') . " untuk tenor {$tenor} bulan.",
                    'ditolak' => "Maaf, pengajuan kredit Anda tidak dapat disetujui. Alasan: {$data['alasan_keputusan']}",
                    'revisi' => "Pengajuan kredit Anda memerlukan revisi. Silakan periksa catatan dan lakukan perbaikan.",
                    default => "Status pengajuan kredit Anda telah diperbarui."
                };

                $this->notifikasiModel->sendNotif(
                    $pengajuan['id_user'],
                    'Keputusan Pengajuan Kredit',
                    $notifMessage,
                    $keputusan === 'disetujui' ? 'sukses' : ($keputusan === 'ditolak' ? 'error' : 'peringatan'),
                    $idPengajuan
                );

                $_SESSION['success'] = 'Keputusan berhasil disimpan';
                $this->redirect('pimpinan/persetujuan');
            }

            $this->view('pimpinan/persetujuan_form', [
                'pengajuan' => $pengajuan,
                'verifikasi' => $verifikasi,
                'survei' => $survei,
                'analisis' => $analisis,
                'persetujuan' => $persetujuan
            ]);
        } else {
            $pengajuanList = $this->pengajuanModel->getPengajuanByStatus('menunggu_keputusan');

            $this->view('pimpinan/persetujuan', [
                'pengajuanList' => $pengajuanList
            ]);
        }
    }

    public function riwayat()
    {
        $sql = "SELECT p.*, jk.nama_kredit, n.*, u.nama_lengkap, u.email,
                       ps.keputusan, ps.plafond_disetujui, ps.tanggal_keputusan
                FROM tb_pengajuan_kredit p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                INNER JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
                INNER JOIN tb_users u ON n.id_user = u.id_user
                LEFT JOIN tb_persetujuan ps ON p.id_pengajuan = ps.id_pengajuan
                WHERE ps.id_pimpinan = ?
                ORDER BY ps.created_at DESC
                LIMIT 50";

        $stmt = $this->pengajuanModel->query($sql, [$_SESSION['user_id']]);
        $riwayat = $stmt->fetchAll();

        $this->view('pimpinan/riwayat', ['riwayat' => $riwayat]);
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
        
        $this->view('pimpinan/laporan', [
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
        $pdf->SetAuthor('Pimpinan');
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
}

