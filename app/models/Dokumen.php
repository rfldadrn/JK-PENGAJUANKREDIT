<?php

class Dokumen extends Model
{
    protected $table = 'tb_dokumen';

    public function uploadDokumen($data)
    {
        $data['tanggal_upload'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getDokumenByPengajuan($idPengajuan)
    {
        return $this->where(['id_pengajuan' => $idPengajuan], 'tanggal_upload ASC');
    }

    public function verifikasiDokumen($idDokumen, $status, $catatan, $idPetugas)
    {
        return $this->update($idDokumen, [
            'status_dokumen' => $status,
            'catatan_verifikasi' => $catatan,
            'diverifikasi_oleh' => $idPetugas,
            'tanggal_verifikasi' => date('Y-m-d H:i:s')
        ], 'id_dokumen');
    }

    public function checkKelengkapan($idPengajuan, $requiredDocs)
    {
        $uploaded = $this->getDokumenByPengajuan($idPengajuan);
        $uploadedTypes = array_column($uploaded, 'jenis_dokumen');

        foreach ($requiredDocs as $doc) {
            if (!in_array($doc, $uploadedTypes)) {
                return false;
            }
        }
        return true;
    }

    public function countByStatus($idPengajuan, $status)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}
                WHERE id_pengajuan = ? AND status_dokumen = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan, $status]);
        return $stmt->fetch()['total'];
    }
}
