<?php

class AuthController extends Controller
{
    private $userModel;
    private $nasabahModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->nasabahModel = $this->model('Nasabah');
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect($this->getDashboardByRole($_SESSION['role']));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            $password = $_POST['password'];

            $errors = $this->validate($_POST, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!empty($errors)) {
                $this->view('auth/login', ['errors' => $errors, 'email' => $email]);
                return;
            }

            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                $this->view('auth/login', [
                    'errors' => ['email' => 'Email tidak terdaftar'],
                    'email' => $email
                ]);
                return;
            }

            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                $this->view('auth/login', [
                    'errors' => ['password' => 'Password salah'],
                    'email' => $email
                ]);
                return;
            }

            if ($user['status_akun'] !== 'aktif') {
                $this->view('auth/login', [
                    'errors' => ['email' => 'Akun Anda belum aktif atau dinonaktifkan'],
                    'email' => $email
                ]);
                return;
            }

            // Set session
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            // Update last login
            $this->userModel->updateLastLogin($user['id_user']);

            // Redirect to dashboard
            $this->redirect($this->getDashboardByRole($user['role']));
        }

        $this->view('auth/login');
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect($this->getDashboardByRole($_SESSION['role']));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_lengkap' => $this->sanitize($_POST['nama_lengkap']),
                'email' => $this->sanitize($_POST['email']),
                'no_hp' => $this->sanitize($_POST['no_hp']),
                'password' => $_POST['password'],
                'role' => 'nasabah'
            ];

            $errors = $this->validate($_POST, [
                'nama_lengkap' => 'required|min:3',
                'email' => 'required|email',
                'no_hp' => 'required|min:10',
                'password' => 'required|min:6',
                'password_confirm' => 'required'
            ]);

            // Check password confirmation
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $errors['password_confirm'] = 'Konfirmasi password tidak cocok';
            }

            // Check email exists
            if ($this->userModel->findByEmail($data['email'])) {
                $errors['email'] = 'Email sudah terdaftar';
            }

            if (!empty($errors)) {
                $this->view('auth/register', ['errors' => $errors, 'old' => $data]);
                return;
            }

            // Auto-activate for nasabah
            $data['status_akun'] = 'aktif';

            $userId = $this->userModel->register($data);

            if ($userId) {
                // Create empty nasabah profile
                $this->nasabahModel->createProfile(['id_user' => $userId]);

                $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
                $this->redirect('auth/login');
            } else {
                $this->view('auth/register', [
                    'errors' => ['system' => 'Terjadi kesalahan sistem'],
                    'old' => $data
                ]);
            }
        }

        $this->view('auth/register');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('auth/login');
    }

    public function unauthorized()
    {
        $this->view('auth/unauthorized');
    }

    private function getDashboardByRole($role)
    {
        $dashboards = [
            'nasabah' => 'nasabah/dashboard',
            'petugas' => 'petugas/dashboard',
            'analis' => 'analis/dashboard',
            'pimpinan' => 'pimpinan/dashboard',
            'admin' => 'admin/dashboard'
        ];

        return $dashboards[$role] ?? 'auth/login';
    }
}
