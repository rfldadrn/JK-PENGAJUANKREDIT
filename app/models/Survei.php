<?php

class Survei extends Model
{
    protected $table = 'tb_survei';

    public function createSurvei($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getSurveiByPengajuan($idPengajuan)
    {
        $sql = "SELECT s.*, u.nama_lengkap as nama_petugas
                FROM {$this->table} s
                INNER JOIN tb_users u ON s.id_petugas = u.id_user
                WHERE s.id_pengajuan = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan]);
        return $stmt->fetch();
    }

    public function updateSurvei($idSurvei, $data)
    {
        return $this->update($idSurvei, $data, 'id_survei');
    }

    public function checkExists($idPengajuan)
    {
        $result = $this->where(['id_pengajuan' => $idPengajuan], 'created_at DESC', 1);
        return !empty($result);
    }

    public function getFotoSurvei($idSurvei)
    {
        $survei = $this->find($idSurvei, 'id_survei');
        if ($survei && $survei['foto_survei']) {
            return json_decode($survei['foto_survei'], true);
        }
        return [];
    }
}
