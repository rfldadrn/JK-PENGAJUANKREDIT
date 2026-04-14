<?php

class HomeController extends Controller
{
    public function index()
    {
        if ($this->isLoggedIn()) {
            $dashboards = [
                'nasabah' => 'nasabah/dashboard',
                'petugas' => 'petugas/dashboard',
                'analis' => 'analis/dashboard',
                'pimpinan' => 'pimpinan/dashboard',
                'admin' => 'admin/dashboard'
            ];
            $this->redirect($dashboards[$_SESSION['role']] ?? 'auth/login');
        }

        $this->view('home/index');
    }
}
