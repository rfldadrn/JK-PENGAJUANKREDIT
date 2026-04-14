<?php

class Notifikasi extends Model
{
    protected $table = 'tb_notifikasi';

    public function createNotif($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getNotifByUser($idUser, $limit = 10)
    {
        return $this->where(['id_user' => $idUser], 'created_at DESC', $limit);
    }

    public function getUnreadCount($idUser)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}
                WHERE id_user = ? AND status_baca = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUser]);
        return $stmt->fetch()['total'];
    }

    public function markAsRead($idNotif)
    {
        return $this->update($idNotif, ['status_baca' => 1], 'id_notif');
    }

    public function markAllAsRead($idUser)
    {
        $sql = "UPDATE {$this->table} SET status_baca = 1 WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idUser]);
    }

    public function sendNotif($idUser, $judul, $pesan, $jenis = 'info', $idPengajuan = null)
    {
        return $this->createNotif([
            'id_user' => $idUser,
            'id_pengajuan' => $idPengajuan,
            'judul' => $judul,
            'pesan' => $pesan,
            'jenis_notif' => $jenis,
            'status_baca' => 0
        ]);
    }

    public function deleteOldNotif($days = 30)
    {
        $sql = "DELETE FROM {$this->table}
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$days]);
    }
}
