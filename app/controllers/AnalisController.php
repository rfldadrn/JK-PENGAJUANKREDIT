<?php

class AnalisController extends Controller
{
    private $pengajuanModel;
    private $analisisModel;
    private $verifikasiModel;
    private $surveiModel;
    private $agunanModel;
    private $notifikasiModel;
    private $nasabahModel;

    public function __construct()
    {
        $this->requireRole(['analis']);
        $this->pengajuanModel = $this->model('PengajuanKredit');
        $this->analisisModel = $this->model('AnalisisKredit');
        $this->verifikasiModel = $this->model('Verifikasi');
        $this->surveiModel = $this->model('Survei');
        $this->agunanModel = $this->model('Agunan');
        $this->notifikasiModel = $this->model('Notifikasi');
        $this->nasabahModel = $this->model('Nasabah');
    }

    public function index()
    {
        $this->redirect('analis/dashboard');
    }

    public function dashboard()
    {
        $stats = [
            'menunggu_analisis' => $this->pengajuanModel->countByStatus('analisis'),
            'analisis_selesai' => $this->pengajuanModel->countByStatus('menunggu_keputusan'),
            'total_layak' => $this->countByKesimpulan('layak'),
            'total_tidak_layak' => $this->countByKesimpulan('tidak_layak')
        ];

        $pengajuanBaru = $this->pengajuanModel->getPengajuanByStatus('analisis');

        // Get pengajuan selesai with analisis data
        $pengajuanSelesai = $this->pengajuanModel->query(
            "SELECT p.*, u.nama_lengkap, jk.nama_kredit,
                    a.skor_total, a.kesimpulan
             FROM tb_pengajuan_kredit p
             JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
             JOIN tb_users u ON n.id_user = u.id_user
             JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
             LEFT JOIN tb_analisis_kredit a ON p.id_pengajuan = a.id_pengajuan
             WHERE p.status_pengajuan = 'menunggu_keputusan'
             ORDER BY p.tanggal_pengajuan DESC
             LIMIT 5"
        )->fetchAll();

        $this->view('analis/dashboard', [
            'stats' => $stats,
            'pengajuanBaru' => array_slice($pengajuanBaru, 0, 5),
            'pengajuanSelesai' => $pengajuanSelesai
        ]);
    }

    public function analisis($idPengajuan = null)
    {
        if ($idPengajuan) {
            $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

            if (!$pengajuan || !in_array($pengajuan['status_pengajuan'], ['analisis', 'menunggu_keputusan'])) {
                $this->redirect('analis/analisis');
            }

            $verifikasi = $this->verifikasiModel->getVerifikasiByPengajuan($idPengajuan);
            $survei = $this->surveiModel->getSurveiByPengajuan($idPengajuan);
            $agunan = $this->agunanModel->getAgunanByPengajuan($idPengajuan);
            $analisis = $this->analisisModel->getAnalisisByPengajuan($idPengajuan);
            $nasabah = $this->nasabahModel->find($pengajuan['id_nasabah'], 'id_nasabah');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'id_pengajuan' => $idPengajuan,
                    'id_analis' => $_SESSION['user_id'],
                    'skor_karakter' => (int) $this->sanitize($_POST['skor_karakter']),
                    'skor_kapasitas' => (int) $this->sanitize($_POST['skor_kapasitas']),
                    'skor_modal' => (int) $this->sanitize($_POST['skor_modal']),
                    'skor_agunan' => (int) $this->sanitize($_POST['skor_agunan']),
                    'skor_kondisi' => (int) $this->sanitize($_POST['skor_kondisi']),
                    'rasio_dsr' => (float) $this->sanitize($_POST['rasio_dsr']),
                    'plafond_rekomendasi' => (float) $this->sanitize($_POST['plafond_rekomendasi']),
                    'tenor_rekomendasi' => (int) $this->sanitize($_POST['tenor_rekomendasi']),
                    'catatan_analisis' => $this->sanitize($_POST['catatan_analisis']),
                    'kesimpulan' => $this->sanitize($_POST['kesimpulan'])
                ];

                $errors = $this->validate($_POST, [
                    'skor_karakter' => 'required|numeric',
                    'skor_kapasitas' => 'required|numeric',
                    'skor_modal' => 'required|numeric',
                    'skor_agunan' => 'required|numeric',
                    'skor_kondisi' => 'required|numeric',
                    'rasio_dsr' => 'required|numeric',
                    'plafond_rekomendasi' => 'required|numeric',
                    'tenor_rekomendasi' => 'required|numeric',
                    'kesimpulan' => 'required'
                ]);

                // Validate score range (0-100)
                foreach (['skor_karakter', 'skor_kapasitas', 'skor_modal', 'skor_agunan', 'skor_kondisi'] as $field) {
                    if ($data[$field] < 0 || $data[$field] > 100) {
                        $errors[$field] = 'Skor harus antara 0-100';
                    }
                }

                // Validate DSR (should not exceed 40%)
                if ($data['rasio_dsr'] > 40) {
                    $errors['rasio_dsr'] = 'DSR melebihi batas maksimal 40%';
                }

                if (!empty($errors)) {
                    $this->view('analis/analisis_form', [
                        'errors' => $errors,
                        'pengajuan' => $pengajuan,
                        'verifikasi' => $verifikasi,
                        'survei' => $survei,
                        'agunan' => $agunan,
                        'analisis' => $analisis,
                        'nasabah' => $nasabah,
                        'old' => $data
                    ]);
                    return;
                }

                if ($analisis) {
                    $this->analisisModel->updateAnalisis($analisis['id_analisis'], $data);
                } else {
                    $this->analisisModel->createAnalisis($data);
                }

                // Update status ke menunggu keputusan
                $this->pengajuanModel->updateStatus($idPengajuan, 'menunggu_keputusan');

                // Send notification
                $this->notifikasiModel->sendNotif(
                    $pengajuan['id_user'],
                    'Analisis Kredit Selesai',
                    'Analisis kredit untuk pengajuan Anda telah selesai dan sedang menunggu persetujuan pimpinan.',
                    'info',
                    $idPengajuan
                );

                $_SESSION['success'] = 'Analisis kredit berhasil disimpan';
                $this->redirect('analis/analisis');
            }

            // Calculate auto-fill values
            $autoCalc = $this->calculateAutoValues($nasabah, $pengajuan, $survei, $agunan);

            $this->view('analis/analisis_form', [
                'pengajuan' => $pengajuan,
                'verifikasi' => $verifikasi,
                'survei' => $survei,
                'agunan' => $agunan,
                'analisis' => $analisis,
                'nasabah' => $nasabah,
                'autoCalc' => $autoCalc
            ]);
        } else {
            $pengajuanList = $this->pengajuanModel->getPengajuanByStatus('analisis');
            $pengajuanSelesai = $this->pengajuanModel->getPengajuanByStatus('menunggu_keputusan');

            $this->view('analis/analisis', [
                'pengajuanBaru' => $pengajuanList,
                'pengajuanSelesai' => $pengajuanSelesai
            ]);
        }
    }

    private function calculateAutoValues($nasabah, $pengajuan, $survei, $agunan)
    {
        $jenisKreditModel = $this->model('JenisKredit');
        $kredit = $jenisKreditModel->find($pengajuan['id_jenis_kredit'], 'id_jenis_kredit');

        // Calculate DSR
        $angsuran = $jenisKreditModel->hitungAngsuran(
            $pengajuan['jumlah_pinjaman'],
            $kredit['bunga_per_tahun'],
            $pengajuan['tenor']
        );

        $dsr = 0;
        if ($nasabah['penghasilan_bulanan'] > 0) {
            $dsr = ($angsuran / $nasabah['penghasilan_bulanan']) * 100;
        }

        // Get total nilai agunan
        $totalAgunan = $this->agunanModel->getTotalNilaiAgunan($pengajuan['id_pengajuan']);

        // Auto score agunan (based on coverage ratio)
        $skorAgunan = 0;
        if ($totalAgunan > 0 && $pengajuan['jumlah_pinjaman'] > 0) {
            $coverageRatio = ($totalAgunan / $pengajuan['jumlah_pinjaman']) * 100;
            if ($coverageRatio >= 150) $skorAgunan = 100;
            elseif ($coverageRatio >= 125) $skorAgunan = 85;
            elseif ($coverageRatio >= 100) $skorAgunan = 70;
            elseif ($coverageRatio >= 75) $skorAgunan = 50;
            else $skorAgunan = 30;
        }

        // Auto score kapasitas (based on DSR)
        $skorKapasitas = 0;
        if ($dsr <= 30) $skorKapasitas = 100;
        elseif ($dsr <= 35) $skorKapasitas = 85;
        elseif ($dsr <= 40) $skorKapasitas = 70;
        else $skorKapasitas = 40;

        return [
            'angsuran' => number_format($angsuran, 0, ',', '.'),
            'dsr' => round($dsr, 2),
            'total_agunan' => number_format($totalAgunan, 0, ',', '.'),
            'skor_agunan' => $skorAgunan,
            'skor_kapasitas' => $skorKapasitas
        ];
    }

    public function riwayat()
    {
        $sql = "SELECT p.*, jk.nama_kredit, n.*, u.nama_lengkap, u.email,
                       a.skor_total, a.kesimpulan, a.tanggal_analisis
                FROM tb_pengajuan_kredit p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                INNER JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
                INNER JOIN tb_users u ON n.id_user = u.id_user
                LEFT JOIN tb_analisis_kredit a ON p.id_pengajuan = a.id_pengajuan
                WHERE a.id_analis = ?
                ORDER BY a.created_at DESC
                LIMIT 50";

        $stmt = $this->pengajuanModel->query($sql, [$_SESSION['user_id']]);
        $riwayat = $stmt->fetchAll();

        $this->view('analis/riwayat', ['riwayat' => $riwayat]);
    }

    private function countByKesimpulan($kesimpulan)
    {
        $sql = "SELECT COUNT(*) as total FROM tb_analisis_kredit WHERE kesimpulan = ?";
        $stmt = $this->analisisModel->query($sql, [$kesimpulan]);
        return $stmt->fetch()['total'];
    }
}
