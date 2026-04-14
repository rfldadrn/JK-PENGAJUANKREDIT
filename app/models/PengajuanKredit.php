<?php

class PengajuanKredit extends Model
{
    protected $table = 'tb_pengajuan_kredit';

    public function generateNoPengajuan()
    {
        $prefix = 'PKR';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $date . $random;
    }

    public function createPengajuan($data)
    {
        $data['no_pengajuan'] = $this->generateNoPengajuan();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function updateStatus($idPengajuan, $status)
    {
        return $this->update($idPengajuan, [
            'status_pengajuan' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id_pengajuan');
    }

    public function getPengajuanByNasabah($idNasabah)
    {
        $sql = "SELECT p.*, jk.nama_kredit, jk.kode_kredit
                FROM {$this->table} p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                WHERE p.id_nasabah = ?
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idNasabah]);
        return $stmt->fetchAll();
    }

    public function getPengajuanDetail($idPengajuan)
    {
        $sql = "SELECT p.*, jk.*, n.*, u.nama_lengkap, u.email, u.no_hp
                FROM {$this->table} p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                INNER JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
                INNER JOIN tb_users u ON n.id_user = u.id_user
                WHERE p.id_pengajuan = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPengajuan]);
        return $stmt->fetch();
    }

    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE status_pengajuan = ?");
        $stmt->execute([$status]);
        return $stmt->fetch()['total'];
    }

    public function countByNasabah($idNasabah)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE id_nasabah = ?");
        $stmt->execute([$idNasabah]);
        return $stmt->fetch()['total'];
    }

    public function getPengajuanByStatus($status)
    {
        $sql = "SELECT p.*, jk.nama_kredit, n.*, u.nama_lengkap, u.email
                FROM {$this->table} p
                INNER JOIN tb_jenis_kredit jk ON p.id_jenis_kredit = jk.id_jenis_kredit
                INNER JOIN tb_nasabah n ON p.id_nasabah = n.id_nasabah
                INNER JOIN tb_users u ON n.id_user = u.id_user
                WHERE p.status_pengajuan = ?
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }
}
