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
        // Get statistics for report
        $totalPengajuan = $this->pengajuanModel->query(
            "SELECT COUNT(*) as total FROM tb_pengajuan_kredit WHERE status_pengajuan != 'draft'"
        )->fetch()['total'];

        $stats = [
            'total_pengajuan' => $totalPengajuan,
            'disetujui' => $this->persetujuanModel->countByKeputusan('disetujui'),
            'ditolak' => $this->persetujuanModel->countByKeputusan('ditolak'),
            'proses' => $this->pengajuanModel->countByStatus('menunggu_keputusan')
        ];

        // Get monthly stats
        $monthlyStats = $this->pengajuanModel->query(
            "SELECT DATE_FORMAT(tanggal_pengajuan, '%Y-%m') as bulan,
                    COUNT(*) as jumlah
             FROM tb_pengajuan_kredit
             WHERE tanggal_pengajuan IS NOT NULL
             GROUP BY DATE_FORMAT(tanggal_pengajuan, '%Y-%m')
             ORDER BY bulan DESC
             LIMIT 12"
        )->fetchAll();

        $this->view('pimpinan/laporan', [
            'stats' => $stats,
            'monthlyStats' => $monthlyStats
        ]);
    }
}
