<?php

class Agunan extends Model
{
    protected $table = 'tb_agunan';

    public function tambahAgunan($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getAgunanByPengajuan($idPengajuan)
    {
        return $this->where(['id_pengajuan' => $idPengajuan], 'created_at ASC');
    }

    public function getTotalNilaiAgunan($idPengajuan)
    {
        $sql = "SELECT SUM(nilai_taksasi) as total FROM {$this->table}
                WHERE id_pengajuan = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function updateAgunan($idAgunan, $data)
    {
        return $this->update($idAgunan, $data, 'id_agunan');
    }

    public function deleteAgunan($idAgunan)
    {
        return $this->delete($idAgunan, 'id_agunan');
    }
}
