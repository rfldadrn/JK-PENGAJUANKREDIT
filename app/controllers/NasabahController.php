<?php

class NasabahController extends Controller
{
    private $userModel;
    private $nasabahModel;
    private $pengajuanModel;
    private $jenisKreditModel;
    private $agunanModel;
    private $dokumenModel;
    private $notifikasiModel;

    public function __construct()
    {
        $this->requireRole(['nasabah']);
        $this->userModel = $this->model('User');
        $this->nasabahModel = $this->model('Nasabah');
        $this->pengajuanModel = $this->model('PengajuanKredit');
        $this->jenisKreditModel = $this->model('JenisKredit');
        $this->agunanModel = $this->model('Agunan');
        $this->dokumenModel = $this->model('Dokumen');
        $this->notifikasiModel = $this->model('Notifikasi');
    }

    public function index()
    {
        $this->redirect('nasabah/dashboard');
    }

    public function dashboard()
    {
        $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);

        if (!$nasabah) {
            $this->redirect('nasabah/profile');
        }

        $pengajuanList = $this->pengajuanModel->getPengajuanByNasabah($nasabah['id_nasabah']);
        $notifikasi = $this->notifikasiModel->getNotifByUser($_SESSION['user_id'], 5);

        $stats = [
            'total' => count($pengajuanList),
            'proses' => count(array_filter($pengajuanList, fn($p) =>
                !in_array($p['status_pengajuan'], ['draft', 'disetujui', 'ditolak', 'dicairkan']))),
            'disetujui' => count(array_filter($pengajuanList, fn($p) => $p['status_pengajuan'] === 'disetujui')),
            'ditolak' => count(array_filter($pengajuanList, fn($p) => $p['status_pengajuan'] === 'ditolak'))
        ];

        $this->view('nasabah/dashboard', [
            'nasabah' => $nasabah,
            'pengajuan' => $pengajuanList,
            'notifikasi' => $notifikasi,
            'stats' => $stats
        ]);
    }

    public function profile()
    {
        $user = $this->userModel->getUserWithNasabah($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'no_nik' => $this->sanitize($_POST['no_nik']),
                'tempat_lahir' => $this->sanitize($_POST['tempat_lahir']),
                'tanggal_lahir' => $this->sanitize($_POST['tanggal_lahir']),
                'jenis_kelamin' => $this->sanitize($_POST['jenis_kelamin']),
                'status_perkawinan' => $this->sanitize($_POST['status_perkawinan']),
                'alamat_ktp' => $this->sanitize($_POST['alamat_ktp']),
                'alamat_domisili' => $this->sanitize($_POST['alamat_domisili']),
                'kelurahan' => $this->sanitize($_POST['kelurahan']),
                'kecamatan' => $this->sanitize($_POST['kecamatan']),
                'kota_kabupaten' => $this->sanitize($_POST['kota_kabupaten']),
                'provinsi' => $this->sanitize($_POST['provinsi']),
                'pekerjaan' => $this->sanitize($_POST['pekerjaan']),
                'nama_perusahaan' => $this->sanitize($_POST['nama_perusahaan']),
                'penghasilan_bulanan' => $this->sanitize($_POST['penghasilan_bulanan']),
                'no_npwp' => $this->sanitize($_POST['no_npwp'])
            ];

            $errors = $this->validate($_POST, [
                'no_nik' => 'required|min:16|max:16',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'alamat_ktp' => 'required',
                'pekerjaan' => 'required',
                'penghasilan_bulanan' => 'required|numeric'
            ]);

            if (!empty($errors)) {
                $this->view('nasabah/profile', ['errors' => $errors, 'old' => $data, 'nasabah' => $user]);
                return;
            }

            $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);

            if ($nasabah) {
                $this->nasabahModel->updateProfile($nasabah['id_nasabah'], $data);
            } else {
                $data['id_user'] = $_SESSION['user_id'];
                $this->nasabahModel->createProfile($data);
            }

            $_SESSION['success'] = 'Profil berhasil diperbarui';
            $this->redirect('nasabah/profile');
        }

        $this->view('nasabah/profile', ['nasabah' => $user]);
    }

    public function pengajuan()
    {
        // Show list of pengajuan (same as tracking list)
        $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);

        if (!$nasabah) {
            $this->redirect('nasabah/profile');
        }

        $pengajuanList = $this->pengajuanModel->getPengajuanByNasabah($nasabah['id_nasabah']);

        $this->view('nasabah/tracking', [
            'pengajuan' => $pengajuanList
        ]);
    }

    public function ajukanBaru()
    {
        $this->redirect('nasabah/step1');
    }

    public function notifikasi()
    {
        $notifikasi = $this->notifikasiModel->getNotifByUser($_SESSION['user_id']);

        // Mark as read when viewing
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
            $idNotif = $_POST['id_notifikasi'];
            $this->notifikasiModel->markAsRead($idNotif);
            $this->redirect('nasabah/notifikasi');
        }

        $this->view('nasabah/notifikasi', [
            'notifikasi' => $notifikasi
        ]);
    }

    public function step1($idPengajuan = null)
    {
        $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);

        if (!$nasabah || !$this->nasabahModel->isProfileComplete($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Lengkapi profil Anda terlebih dahulu';
            $this->redirect('nasabah/profile');
        }

        $jenisKredit = $this->jenisKreditModel->getAktif();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_nasabah' => $nasabah['id_nasabah'],
                'id_jenis_kredit' => $this->sanitize($_POST['id_jenis_kredit']),
                'jumlah_pinjaman' => $this->sanitize($_POST['jumlah_pinjaman']),
                'tenor' => $this->sanitize($_POST['tenor']),
                'tujuan_kredit' => $this->sanitize($_POST['tujuan_kredit']),
                'sumber_pengembalian' => $this->sanitize($_POST['sumber_pengembalian']),
                'catatan_nasabah' => $this->sanitize($_POST['catatan_nasabah'] ?? ''),
                'status_pengajuan' => 'draft'
            ];

            $errors = $this->validate($_POST, [
                'id_jenis_kredit' => 'required|numeric',
                'jumlah_pinjaman' => 'required|numeric',
                'tenor' => 'required|numeric',
                'tujuan_kredit' => 'required',
                'sumber_pengembalian' => 'required'
            ]);

            if (!$this->jenisKreditModel->validatePlafond($data['id_jenis_kredit'], $data['jumlah_pinjaman'])) {
                $errors['jumlah_pinjaman'] = 'Jumlah pinjaman tidak sesuai range produk';
            }

            if (!$this->jenisKreditModel->validateTenor($data['id_jenis_kredit'], $data['tenor'])) {
                $errors['tenor'] = 'Tenor tidak sesuai range produk';
            }

            if (!empty($errors)) {
                $this->view('nasabah/pengajuan/step1', [
                    'errors' => $errors,
                    'old' => $data,
                    'jenisKredit' => $jenisKredit
                ]);
                return;
            }

            if ($idPengajuan) {
                $this->pengajuanModel->update($idPengajuan, $data, 'id_pengajuan');
                $_SESSION['id_pengajuan'] = $idPengajuan;
            } else {
                $idPengajuan = $this->pengajuanModel->createPengajuan($data);
                $_SESSION['id_pengajuan'] = $idPengajuan;
            }

            $this->redirect('nasabah/step2/' . $idPengajuan);
        }

        $pengajuan = $idPengajuan ? $this->pengajuanModel->find($idPengajuan, 'id_pengajuan') : null;

        $this->view('nasabah/pengajuan/step1', [
            'jenisKredit' => $jenisKredit,
            'pengajuan' => $pengajuan
        ]);
    }

    public function step2($idPengajuan)
    {
        $pengajuan = $this->pengajuanModel->find($idPengajuan, 'id_pengajuan');

        // Allow access for draft and revisi status
        if (!$pengajuan || !in_array($pengajuan['status_pengajuan'], ['draft', 'revisi'])) {
            $this->redirect('nasabah/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'add') {
                $agunanData = [
                    'id_pengajuan' => $idPengajuan,
                    'jenis_agunan' => $this->sanitize($_POST['jenis_agunan']),
                    'nama_agunan' => $this->sanitize($_POST['nama_agunan']),
                    'no_sertifikat' => $this->sanitize($_POST['no_sertifikat']),
                    'atas_nama' => $this->sanitize($_POST['atas_nama']),
                    'nilai_pasar' => $this->sanitize($_POST['nilai_pasar']),
                    'nilai_taksasi' => $this->sanitize($_POST['nilai_taksasi']),
                    'lokasi_agunan' => $this->sanitize($_POST['lokasi_agunan']),
                    'luas' => $this->sanitize($_POST['luas'])
                ];

                $this->agunanModel->tambahAgunan($agunanData);
                $_SESSION['success'] = 'Agunan berhasil ditambahkan';
                $this->redirect('nasabah/step2/' . $idPengajuan);
            } elseif ($_POST['action'] === 'next') {
                $this->redirect('nasabah/step3/' . $idPengajuan);
            }
        }

        $agunanList = $this->agunanModel->getAgunanByPengajuan($idPengajuan);

        $this->view('nasabah/pengajuan/step2', [
            'pengajuan' => $pengajuan,
            'agunan' => $agunanList
        ]);
    }

    public function step3($idPengajuan)
    {
        $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

        // Allow access for draft and revisi status
        if (!$pengajuan || !in_array($pengajuan['status_pengajuan'], ['draft', 'revisi'])) {
            $this->redirect('nasabah/dashboard');
        }

        $syaratDokumen = $this->jenisKreditModel->getSyaratDokumen($pengajuan['id_jenis_kredit']);
        $dokumenUploaded = $this->dokumenModel->getDokumenByPengajuan($idPengajuan);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === 0) {
                $jenisDokumen = $this->sanitize($_POST['jenis_dokumen']);
                $file = $_FILES['dokumen'];

                $upload = $this->uploadFile($file, 'dokumen');

                if ($upload['success']) {
                    $dokumenData = [
                        'id_pengajuan' => $idPengajuan,
                        'jenis_dokumen' => $jenisDokumen,
                        'nama_file' => $file['name'],
                        'path_file' => $upload['path'],
                        'ukuran_file' => round($file['size'] / 1024),
                        'tipe_file' => $file['type'],
                        'status_dokumen' => 'belum_diverifikasi'
                    ];

                    $this->dokumenModel->uploadDokumen($dokumenData);
                    $_SESSION['success'] = 'Dokumen berhasil diunggah';
                } else {
                    $_SESSION['error'] = $upload['message'];
                }

                $this->redirect('nasabah/step3/' . $idPengajuan);
            } elseif (isset($_POST['action']) && $_POST['action'] === 'next') {
                $this->redirect('nasabah/step4/' . $idPengajuan);
            }
        }

        $this->view('nasabah/pengajuan/step3', [
            'pengajuan' => $pengajuan,
            'syaratDokumen' => $syaratDokumen,
            'dokumenUploaded' => $dokumenUploaded
        ]);
    }

    public function step4($idPengajuan)
    {
        $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

        if (!$pengajuan || $pengajuan['status_pengajuan'] !== 'draft') {
            $this->redirect('nasabah/dashboard');
        }

        $agunanList = $this->agunanModel->getAgunanByPengajuan($idPengajuan);
        $dokumenList = $this->dokumenModel->getDokumenByPengajuan($idPengajuan);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->pengajuanModel->update($idPengajuan, [
                'status_pengajuan' => 'diajukan',
                'tanggal_pengajuan' => date('Y-m-d H:i:s')
            ], 'id_pengajuan');

            $this->notifikasiModel->sendNotif(
                $_SESSION['user_id'],
                'Pengajuan Kredit Berhasil Dikirim',
                'Pengajuan kredit Anda dengan nomor ' . $pengajuan['no_pengajuan'] . ' telah berhasil dikirim.',
                'sukses',
                $idPengajuan
            );

            unset($_SESSION['id_pengajuan']);
            $_SESSION['success'] = 'Pengajuan kredit berhasil dikirim!';
            $this->redirect('nasabah/tracking/' . $idPengajuan);
        }

        $this->view('nasabah/pengajuan/step4', [
            'pengajuan' => $pengajuan,
            'agunan' => $agunanList,
            'dokumen' => $dokumenList
        ]);
    }

    public function tracking($idPengajuan = null)
    {
        $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);

        if ($idPengajuan) {
            $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

            if (!$pengajuan || $pengajuan['id_nasabah'] != $nasabah['id_nasabah']) {
                $this->redirect('nasabah/tracking');
            }

            $this->view('nasabah/tracking_detail', ['pengajuan' => $pengajuan]);
        } else {
            $pengajuanList = $this->pengajuanModel->getPengajuanByNasabah($nasabah['id_nasabah']);
            $this->view('nasabah/tracking', ['pengajuan' => $pengajuanList]);
        }
    }

    public function deleteAgunan($idAgunan)
    {
        $this->agunanModel->deleteAgunan($idAgunan);
        $_SESSION['success'] = 'Agunan berhasil dihapus';

        // Get referer and extract relative path
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer && strpos($referer, BASE_URL) === 0) {
            $relativePath = str_replace(BASE_URL, '', $referer);
            $this->redirect($relativePath);
        } else {
            $this->redirect('nasabah/dashboard');
        }
    }

    public function deleteDokumen($idDokumen)
    {
        $dokumen = $this->dokumenModel->find($idDokumen, 'id_dokumen');
        if ($dokumen) {
            $filePath = UPLOAD_PATH . $dokumen['path_file'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->dokumenModel->delete($idDokumen, 'id_dokumen');
            $_SESSION['success'] = 'Dokumen berhasil dihapus';
        }

        // Get referer and extract relative path
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer && strpos($referer, BASE_URL) === 0) {
            $relativePath = str_replace(BASE_URL, '', $referer);
            $this->redirect($relativePath);
        } else {
            $this->redirect('nasabah/dashboard');
        }
    }

    public function revisi($idPengajuan)
    {
        $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);
        $pengajuan = $this->pengajuanModel->getPengajuanDetail($idPengajuan);

        if (!$pengajuan || $pengajuan['id_nasabah'] != $nasabah['id_nasabah']) {
            $this->redirect('nasabah/tracking');
        }

        if ($pengajuan['status_pengajuan'] !== 'revisi') {
            $_SESSION['error'] = 'Pengajuan ini tidak memerlukan revisi';
            $this->redirect('nasabah/tracking/' . $idPengajuan);
        }

        // Get verifikasi data to show revision notes
        $verifikasi = $this->model('Verifikasi')->getVerifikasiByPengajuan($idPengajuan);
        $dokumenList = $this->dokumenModel->getDokumenByPengajuan($idPengajuan);
        $agunanList = $this->agunanModel->getAgunanByPengajuan($idPengajuan);

        $this->view('nasabah/revisi', [
            'pengajuan' => $pengajuan,
            'verifikasi' => $verifikasi ?: [],
            'dokumen' => $dokumenList,
            'agunan' => $agunanList
        ]);
    }

    public function submitRevisi($idPengajuan)
    {
        $nasabah = $this->nasabahModel->findByUserId($_SESSION['user_id']);
        $pengajuan = $this->pengajuanModel->find($idPengajuan, 'id_pengajuan');

        if (!$pengajuan || $pengajuan['id_nasabah'] != $nasabah['id_nasabah']) {
            $this->redirect('nasabah/tracking');
        }

        if ($pengajuan['status_pengajuan'] !== 'revisi') {
            $_SESSION['error'] = 'Pengajuan ini tidak dalam status revisi';
            $this->redirect('nasabah/tracking/' . $idPengajuan);
        }

        // Update status back to 'diajukan' for re-verification
        $this->pengajuanModel->updateStatus($idPengajuan, 'diajukan');

        // Send notification to petugas
        $petugas = $this->model('User')->where(['role' => 'petugas'], 'created_at ASC', 1);
        if (!empty($petugas)) {
            $this->notifikasiModel->sendNotif(
                $petugas[0]['id_user'],
                'Pengajuan Revisi Disubmit Ulang',
                "Pengajuan {$pengajuan['no_pengajuan']} telah diperbaiki dan siap diverifikasi ulang",
                'info',
                $idPengajuan
            );
        }

        $_SESSION['success'] = 'Pengajuan berhasil disubmit ulang untuk verifikasi';
        $this->redirect('nasabah/tracking/' . $idPengajuan);
    }
}
