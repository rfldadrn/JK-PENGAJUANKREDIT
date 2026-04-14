<?php

class LogAktivitas extends Model
{
    protected $table = 'tb_log_aktivitas';

    public function log($idUser, $aktivitas, $modul, $dataLama = null, $dataBaru = null)
    {
        $data = [
            'id_user' => $idUser,
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'data_lama' => $dataLama ? json_encode($dataLama) : null,
            'data_baru' => $dataBaru ? json_encode($dataBaru) : null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    public function getLogByUser($idUser, $limit = 50)
    {
        return $this->where(['id_user' => $idUser], 'created_at DESC', $limit);
    }

    public function getLogByModul($modul, $limit = 50)
    {
        return $this->where(['modul' => $modul], 'created_at DESC', $limit);
    }

    public function getAllLog($limit = 100)
    {
        $sql = "SELECT l.*, u.nama_lengkap, u.email, u.role
                FROM {$this->table} l
                INNER JOIN tb_users u ON l.id_user = u.id_user
                ORDER BY l.created_at DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function deleteOldLog($days = 90)
    {
        $sql = "DELETE FROM {$this->table}
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$days]);
    }

    public function countByModul($modul)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE modul = ?");
        $stmt->execute([$modul]);
        return $stmt->fetch()['total'];
    }
}
