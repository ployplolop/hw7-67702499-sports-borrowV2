<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* ---------- READ ---------- */

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT id, username, full_name, email, phone, role, created_at FROM users ORDER BY id');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /* ---------- CREATE ---------- */

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (username, password, full_name, email, phone, role)
                VALUES (:username, :password, :full_name, :email, :phone, :role)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username'  => $data['username'],
            ':password'  => password_hash($data['password'], PASSWORD_DEFAULT),
            ':full_name' => $data['full_name'],
            ':email'     => $data['email'],
            ':phone'     => $data['phone'] ?? null,
            ':role'      => $data['role'] ?? 'user',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /* ---------- UPDATE ---------- */

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE users SET username = :username, full_name = :full_name,
                email = :email, phone = :phone, role = :role WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'        => $id,
            ':username'  => $data['username'],
            ':full_name' => $data['full_name'],
            ':email'     => $data['email'],
            ':phone'     => $data['phone'] ?? null,
            ':role'      => $data['role'] ?? 'user',
        ]);
    }

    /* ---------- DELETE ---------- */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
