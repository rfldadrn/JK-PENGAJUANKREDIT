<?php

class User extends Model
{
    protected $table = 'tb_users';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function register($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->insert($data);
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['updated_at' => date('Y-m-d H:i:s')], 'id_user');
    }

    public function activateAccount($userId)
    {
        return $this->update($userId, ['status_akun' => 'aktif'], 'id_user');
    }

    public function countByRole($role)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetch()['total'];
    }

    public function getUserWithNasabah($userId)
    {
        $sql = "SELECT u.*, n.*
                FROM {$this->table} u
                LEFT JOIN tb_nasabah n ON u.id_user = n.id_user
                WHERE u.id_user = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
