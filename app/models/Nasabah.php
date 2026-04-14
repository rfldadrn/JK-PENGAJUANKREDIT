<?php

class Nasabah extends Model
{
    protected $table = 'tb_nasabah';

    public function findByUserId($userId)
    {
        return $this->find($userId, 'id_user');
    }

    public function findByNIK($nik)
    {
        return $this->find($nik, 'no_nik');
    }

    public function createProfile($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function updateProfile($idNasabah, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($idNasabah, $data, 'id_nasabah');
    }

    public function isProfileComplete($userId)
    {
        $nasabah = $this->findByUserId($userId);

        if (!$nasabah) {
            return false;
        }

        $requiredFields = ['no_nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                          'alamat_ktp', 'pekerjaan', 'penghasilan_bulanan'];

        foreach ($requiredFields as $field) {
            if (empty($nasabah[$field])) {
                return false;
            }
        }

        return true;
    }

    public function getNasabahWithUser($idNasabah)
    {
        $sql = "SELECT n.*, u.nama_lengkap, u.email, u.no_hp
                FROM {$this->table} n
                INNER JOIN tb_users u ON n.id_user = u.id_user
                WHERE n.id_nasabah = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idNasabah]);
        return $stmt->fetch();
    }
}
