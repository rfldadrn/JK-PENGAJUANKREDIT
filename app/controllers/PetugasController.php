<?php

class PetugasController extends Controller
{
    private $pengajuanModel;
    private $dokumenModel;
    private $verifikasiModel;
    private $surveiModel;
    private $agunanModel;
    private $notifikasiModel;

    public function __construct()
    {
        $this->requireRole(['petugas']);
        $this->pengajuanModel = $this->model('PengajuanKredit');
        $this->dokumenModel = $this->model('Dokumen');
        $this->verifikasiModel = $this->model('Verifikasi');
        $this->surveiModel = $this->model('Survei');
        $this->agunanModel = $this->model('Agunan');
        $this->notifikasiModel = $this->model('Notifikasi');
    }

    public function index()
    {
        $this->redirect('petugas/dashboard');
    }

    public function dashboard()
    {
        $stats = [
            'diajukan' => $this->pengajuanModel->countByStatus('diajukan'),
            'verifikasi' => $this->pengajuanModel->countByStatus('verifikasi'),
            'survei' => $this->pengajuanModel->countByStatus('survei'),
            'analisis' => $this->pengajuanModel->countByStatus('analisis')
        ];

        $pengajuanBaru = $this->pengajuanModel->getPengajuanByStatus('diajukan');
        $pengajuanSurvei = $this->pengajuanModel->getPengajuanByStatus('survei');

        $this->view('petugas/dashboard', [
            'stats' => $stats,
            'pengajuanBaru' => $pengajuanBaru,
            'pengajuanSurvei' => $pengajuanSurvei
        ]);
    }

    public function verifikasi($idPengajuan = null)
    {
        if ($idPengajuan) {
            $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

            if (!$pengajuan) {
                $this->redirect('petugas/verifikasi');
            }

            $dokumenList = $this->dokumenModel->getDokumenByPengajuan($idPengajuan);
            $agunanList = $this->agunanModel->getAgunanByPengajuan($idPengajuan);
            $verifikasi = $this->verifikasiModel->getVerifikasiByPengajuan($idPengajuan);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'id_pengajuan' => $idPengajuan,
                    'id_petugas' => $_SESSION['user_id'],
                    'kelengkapan_dokumen' => $this->sanitize($_POST['kelengkapan_dokumen']),
                    'kesesuaian_data' => $this->sanitize($_POST['kesesuaian_data']),
                    'catatan_verifikasi' => $this->sanitize($_POST['catatan_verifikasi']),
                    'rekomendasi' => $this->sanitize($_POST['rekomendasi'])
                ];

                $errors = $this->validate($_POST, [
                    'kelengkapan_dokumen' => 'required',
                    'kesesuaian_data' => 'required',
                    'rekomendasi' => 'required'
                ]);

                if (!empty($errors)) {
                    $this->view('petugas/verifikasi_detail', [
                        'errors' => $errors,
                        'pengajuan' => $pengajuan,
                        'dokumen' => $dokumenList,
                        'agunan' => $agunanList,
                        'verifikasi' => $verifikasi ?: []
                    ]);
                    return;
                }

                if ($verifikasi) {
                    $this->verifikasiModel->updateVerifikasi($verifikasi['id_verifikasi'], $data);
                } else {
                    $this->verifikasiModel->createVerifikasi($data);
                }

                // Update status pengajuan
                $newStatus = match($data['rekomendasi']) {
                    'lanjut_survei' => 'survei',
                    'tolak' => 'ditolak',
                    'revisi' => 'revisi',
                    default => 'verifikasi'
                };

                $this->pengajuanModel->updateStatus($idPengajuan, $newStatus);

                // Send notification to nasabah
                $nasabah = $this->pengajuanModel->getPengajuanDetail($idPengajuan);
                $this->notifikasiModel->sendNotif(
                    $nasabah['id_user'],
                    'Status Verifikasi Pengajuan',
                    "Pengajuan kredit Anda telah diverifikasi. Status: " . ucfirst(str_replace('_', ' ', $newStatus)),
                    $newStatus === 'ditolak' ? 'error' : 'info',
                    $idPengajuan
                );

                $_SESSION['success'] = 'Verifikasi berhasil disimpan';
                $this->redirect('petugas/verifikasi');
            }

            $this->view('petugas/verifikasi_detail', [
                'pengajuan' => $pengajuan,
                'dokumen' => $dokumenList,
                'agunan' => $agunanList,
                'verifikasi' => $verifikasi ?: []
            ]);
        } else {
            $pengajuanList = $this->pengajuanModel->getPengajuanByStatus('diajukan');
            $pengajuanVerifikasi = $this->pengajuanModel->getPengajuanByStatus('verifikasi');

            $this->view('petugas/verifikasi', [
                'pengajuanList' => array_merge($pengajuanList, $pengajuanVerifikasi)
            ]);
        }
    }

    public function verifikasiDokumen($idDokumen)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $this->sanitize($_POST['status_dokumen']);
            $catatan = $this->sanitize($_POST['catatan_verifikasi'] ?? '');

            $this->dokumenModel->verifikasiDokumen($idDokumen, $status, $catatan, $_SESSION['user_id']);

            $this->json(['success' => true, 'message' => 'Dokumen berhasil diverifikasi']);
        }
    }

    public function survei($idPengajuan = null)
    {
        if ($idPengajuan) {
            $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

            if (!$pengajuan || $pengajuan['status_pengajuan'] !== 'survei') {
                $this->redirect('petugas/survei');
            }

            $agunanList = $this->agunanModel->getAgunanByPengajuan($idPengajuan);
            $survei = $this->surveiModel->getSurveiByPengajuan($idPengajuan);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'id_pengajuan' => $idPengajuan,
                    'id_petugas' => $_SESSION['user_id'],
                    'tanggal_survei' => $this->sanitize($_POST['tanggal_survei']),
                    'alamat_survei' => $this->sanitize($_POST['alamat_survei']),
                    'kondisi_usaha' => $this->sanitize($_POST['kondisi_usaha']),
                    'omzet_usaha' => $this->sanitize($_POST['omzet_usaha']),
                    'kondisi_agunan' => $this->sanitize($_POST['kondisi_agunan']),
                    'nilai_agunan_estimasi' => $this->sanitize($_POST['nilai_agunan_estimasi']),
                    'lingkungan_sekitar' => $this->sanitize($_POST['lingkungan_sekitar']),
                    'catatan_survei' => $this->sanitize($_POST['catatan_survei']),
                    'rekomendasi_survei' => $this->sanitize($_POST['rekomendasi_survei'])
                ];

                $errors = $this->validate($_POST, [
                    'tanggal_survei' => 'required',
                    'alamat_survei' => 'required',
                    'kondisi_usaha' => 'required',
                    'omzet_usaha' => 'required|numeric',
                    'kondisi_agunan' => 'required',
                    'nilai_agunan_estimasi' => 'required|numeric',
                    'rekomendasi_survei' => 'required'
                ]);

                if (!empty($errors)) {
                    $this->view('petugas/survei_form', [
                        'errors' => $errors,
                        'pengajuan' => $pengajuan,
                        'agunan' => $agunanList,
                        'survei' => $survei,
                        'old' => $data
                    ]);
                    return;
                }

                // Handle foto survei upload
                $fotoSurvei = [];
                if (isset($_FILES['foto_survei']) && is_array($_FILES['foto_survei']['name'])) {
                    foreach ($_FILES['foto_survei']['name'] as $key => $name) {
                        if ($_FILES['foto_survei']['error'][$key] === 0) {
                            $file = [
                                'name' => $_FILES['foto_survei']['name'][$key],
                                'type' => $_FILES['foto_survei']['type'][$key],
                                'tmp_name' => $_FILES['foto_survei']['tmp_name'][$key],
                                'error' => $_FILES['foto_survei']['error'][$key],
                                'size' => $_FILES['foto_survei']['size'][$key]
                            ];

                            $upload = $this->uploadFile($file, 'survei');
                            if ($upload['success']) {
                                $fotoSurvei[] = $upload['path'];
                            }
                        }
                    }
                }

                $data['foto_survei'] = !empty($fotoSurvei) ? json_encode($fotoSurvei) : null;

                if ($survei) {
                    $this->surveiModel->updateSurvei($survei['id_survei'], $data);
                } else {
                    $this->surveiModel->createSurvei($data);
                }

                // Update status pengajuan ke analisis
                $this->pengajuanModel->updateStatus($idPengajuan, 'analisis');

                // Send notification
                $this->notifikasiModel->sendNotif(
                    $pengajuan['id_user'],
                    'Survei Lapangan Telah Dilakukan',
                    'Survei lapangan untuk pengajuan kredit Anda telah selesai dan sedang dalam tahap analisis kredit.',
                    'info',
                    $idPengajuan
                );

                $_SESSION['success'] = 'Laporan survei berhasil disimpan';
                $this->redirect('petugas/survei');
            }

            $this->view('petugas/survei_form', [
                'pengajuan' => $pengajuan,
                'agunan' => $agunanList,
                'survei' => $survei
            ]);
        } else {
            $pengajuanList = $this->pengajuanModel->getPengajuanByStatus('survei');

            $this->view('petugas/survei', [
                'pengajuanList' => $pengajuanList
            ]);
        }
    }

    public function riwayat()
    {
        $sql = "SELECT p.*, jk.nama_kredit, n.*, u.nama_lengkap, u.email,
                       v.rekomendasi as verifikasi_rekomendasi,
                       s.rekomendasi_survei
                FROM tb_pengajuan_kredit p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                INNER JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
                INNER JOIN tb_users u ON n.id_user = u.id_user
                LEFT JOIN tb_verifikasi v ON p.id_pengajuan = v.id_pengajuan
                LEFT JOIN tb_survei s ON p.id_pengajuan = s.id_pengajuan
                WHERE (v.id_petugas = ? OR s.id_petugas = ?)
                ORDER BY p.created_at DESC
                LIMIT 50";

        $stmt = $this->pengajuanModel->query($sql, [$_SESSION['user_id'], $_SESSION['user_id']]);
        $riwayat = $stmt->fetchAll();

        $this->view('petugas/riwayat', ['riwayat' => $riwayat]);
    }
}
