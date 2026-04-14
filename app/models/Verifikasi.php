<?php

class Verifikasi extends Model
{
    protected $table = 'tb_verifikasi';

    public function createVerifikasi($data)
    {
        $data['tanggal_verifikasi'] = date('Y-m-d H:i:s');
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getVerifikasiByPengajuan($idPengajuan)
    {
        $sql = "SELECT v.*, u.nama_lengkap as nama_petugas
                FROM {$this->table} v
                INNER JOIN tb_users u ON v.id_petugas = u.id_user
                WHERE v.id_pengajuan = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan]);
        return $stmt->fetch();
    }

    public function updateVerifikasi($idVerifikasi, $data)
    {
        return $this->update($idVerifikasi, $data, 'id_verifikasi');
    }

    public function checkExists($idPengajuan)
    {
        $result = $this->where(['id_pengajuan' => $idPengajuan], 'created_at DESC', 1);
        return !empty($result);
    }
}
