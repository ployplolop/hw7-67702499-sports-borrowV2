<?php
require_once __DIR__ . '/../config/database.php';

class BorrowRecord
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* ---------- READ ---------- */

    public function getAll(): array
    {
        $sql = 'SELECT br.*, u.full_name AS user_name, e.name AS equipment_name
                FROM borrow_records br
                JOIN users u      ON u.id = br.user_id
                JOIN equipment e  ON e.id = br.equipment_id
                ORDER BY br.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT br.*, u.full_name AS user_name, e.name AS equipment_name
                FROM borrow_records br
                JOIN users u      ON u.id = br.user_id
                JOIN equipment e  ON e.id = br.equipment_id
                WHERE br.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByUserId(int $userId): array
    {
        $sql = 'SELECT br.*, e.name AS equipment_name
                FROM borrow_records br
                JOIN equipment e ON e.id = br.equipment_id
                WHERE br.user_id = :uid
                ORDER BY br.borrow_date DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    /* ---------- CREATE (BORROW) ---------- */

    public function create(array $data): int
    {
        $sql = 'INSERT INTO borrow_records (user_id, equipment_id, quantity, borrow_date, due_date, note)
                VALUES (:user_id, :equipment_id, :quantity, NOW(), :due_date, :note)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id'      => $data['user_id'],
            ':equipment_id' => $data['equipment_id'],
            ':quantity'     => $data['quantity'] ?? 1,
            ':due_date'     => $data['due_date'],
            ':note'         => $data['note'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /* ---------- RETURN ---------- */

    public function returnItem(int $id): bool
    {
        $sql = "UPDATE borrow_records
                SET return_date = NOW(), status = 'returned'
                WHERE id = :id AND status = 'borrowed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /* ---------- DELETE ---------- */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM borrow_records WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
