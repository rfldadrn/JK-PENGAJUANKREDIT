<?php

class AnalisisKredit extends Model
{
    protected $table = 'tb_analisis_kredit';

    public function createAnalisis($data)
    {
        // Hitung skor total (rata-rata tertimbang 5C)
        $skorTotal = ($data['skor_karakter'] * 0.25) +
                     ($data['skor_kapasitas'] * 0.30) +
                     ($data['skor_modal'] * 0.15) +
                     ($data['skor_agunan'] * 0.20) +
                     ($data['skor_kondisi'] * 0.10);

        $data['skor_total'] = round($skorTotal, 2);
        $data['tanggal_analisis'] = date('Y-m-d H:i:s');
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->insert($data);
    }

    public function getAnalisisByPengajuan($idPengajuan)
    {
        $sql = "SELECT a.*, u.nama_lengkap as nama_analis
                FROM {$this->table} a
                INNER JOIN tb_users u ON a.id_analis = u.id_user
                WHERE a.id_pengajuan = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan]);
        return $stmt->fetch();
    }

    public function updateAnalisis($idAnalisis, $data)
    {
        // Recalculate skor total if score fields are updated
        if (isset($data['skor_karakter']) || isset($data['skor_kapasitas'])) {
            $current = $this->find($idAnalisis, 'id_analisis');

            $skorKarakter = $data['skor_karakter'] ?? $current['skor_karakter'];
            $skorKapasitas = $data['skor_kapasitas'] ?? $current['skor_kapasitas'];
            $skorModal = $data['skor_modal'] ?? $current['skor_modal'];
            $skorAgunan = $data['skor_agunan'] ?? $current['skor_agunan'];
            $skorKondisi = $data['skor_kondisi'] ?? $current['skor_kondisi'];

            $skorTotal = ($skorKarakter * 0.25) + ($skorKapasitas * 0.30) +
                        ($skorModal * 0.15) + ($skorAgunan * 0.20) +
                        ($skorKondisi * 0.10);

            $data['skor_total'] = round($skorTotal, 2);
        }

        return $this->update($idAnalisis, $data, 'id_analisis');
    }

    public function hitungDSR($angsuranPerBulan, $penghasilanBersih)
    {
        if ($penghasilanBersih <= 0) return 0;
        return round(($angsuranPerBulan / $penghasilanBersih) * 100, 2);
    }

    public function checkExists($idPengajuan)
    {
        $result = $this->where(['id_pengajuan' => $idPengajuan], 'created_at DESC', 1);
        return !empty($result);
    }

    public function getKesimpulanByScore($skorTotal)
    {
        if ($skorTotal >= 80) return 'layak';
        if ($skorTotal >= 60) return 'layak_dengan_syarat';
        return 'tidak_layak';
    }
}
