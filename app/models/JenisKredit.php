<?php

class JenisKredit extends Model
{
    protected $table = 'tb_jenis_kredit';

    public function getAktif()
    {
        return $this->where(['status' => 'aktif']);
    }

    public function findByKode($kode)
    {
        return $this->find($kode, 'kode_kredit');
    }

    public function getSyaratDokumen($id)
    {
        $kredit = $this->find($id, 'id_jenis_kredit');
        if ($kredit && $kredit['syarat_dokumen']) {
            return json_decode($kredit['syarat_dokumen'], true);
        }
        return [];
    }

    public function validatePlafond($idJenisKredit, $jumlah)
    {
        $kredit = $this->find($idJenisKredit, 'id_jenis_kredit');
        if (!$kredit) return false;

        return $jumlah >= $kredit['plafond_min'] && $jumlah <= $kredit['plafond_max'];
    }

    public function validateTenor($idJenisKredit, $tenor)
    {
        $kredit = $this->find($idJenisKredit, 'id_jenis_kredit');
        if (!$kredit) return false;

        return $tenor >= $kredit['tenor_min'] && $tenor <= $kredit['tenor_max'];
    }

    public function hitungAngsuran($jumlahPinjaman, $bungaTahunan, $tenor)
    {
        $bungaBulanan = $bungaTahunan / 12 / 100;
        $angsuran = ($jumlahPinjaman * $bungaBulanan) / (1 - pow(1 + $bungaBulanan, -$tenor));
        return round($angsuran, 2);
    }
}
