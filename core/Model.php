<?php

class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all($orderBy = 'created_at DESC')
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function find($id, $column = 'id')
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function where($conditions = [], $orderBy = 'created_at DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($conditions)) {
            $where = [];
            $values = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY {$orderBy}";

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($values ?? []);
        return $stmt->fetchAll();
    }

    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute(array_values($data))) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data, $column = 'id')
    {
        $set = [];
        $values = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE {$column} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id, $column = 'id')
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$column} = ?");
        return $stmt->execute([$id]);
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
