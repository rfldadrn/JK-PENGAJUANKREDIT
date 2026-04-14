<?php

class Persetujuan extends Model
{
    protected $table = 'tb_persetujuan';

    public function createPersetujuan($data)
    {
        $data['tanggal_keputusan'] = date('Y-m-d H:i:s');
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getPersetujuanByPengajuan($idPengajuan)
    {
        $sql = "SELECT p.*, u.nama_lengkap as nama_pimpinan
                FROM {$this->table} p
                INNER JOIN tb_users u ON p.id_pimpinan = u.id_user
                WHERE p.id_pengajuan = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan]);
        return $stmt->fetch();
    }

    public function updatePersetujuan($idPersetujuan, $data)
    {
        return $this->update($idPersetujuan, $data, 'id_persetujuan');
    }

    public function checkExists($idPengajuan)
    {
        $result = $this->where(['id_pengajuan' => $idPengajuan], 'created_at DESC', 1);
        return !empty($result);
    }

    public function hitungAngsuran($plafond, $bunga, $tenor)
    {
        $bungaBulanan = $bunga / 12 / 100;
        if ($bungaBulanan == 0) {
            return $plafond / $tenor;
        }
        $angsuran = ($plafond * $bungaBulanan) / (1 - pow(1 + $bungaBulanan, -$tenor));
        return round($angsuran, 2);
    }

    public function countByKeputusan($keputusan)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE keputusan = ?");
        $stmt->execute([$keputusan]);
        return $stmt->fetch()['total'];
    }
}
