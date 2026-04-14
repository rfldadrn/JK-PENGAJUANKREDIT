<?php

class AdminController extends Controller
{
    private $userModel;
    private $jenisKreditModel;
    private $pengajuanModel;
    private $logModel;

    public function __construct()
    {
        $this->requireRole(['admin']);
        $this->userModel = $this->model('User');
        $this->jenisKreditModel = $this->model('JenisKredit');
        $this->pengajuanModel = $this->model('PengajuanKredit');
        $this->logModel = $this->model('LogAktivitas');
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
        // Monthly statistics
        $monthlyStats = $this->getMonthlyStats();

        // Status distribution
        $statusStats = $this->getStatusDistribution();

        // Product performance
        $productStats = $this->getProductStats();

        $this->view('admin/laporan', [
            'monthlyStats' => $monthlyStats,
            'statusStats' => $statusStats,
            'productStats' => $productStats
        ]);
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
