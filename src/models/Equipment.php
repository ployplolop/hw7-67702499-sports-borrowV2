<?php
require_once __DIR__ . '/../config/database.php';

class Equipment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* ---------- READ ---------- */

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM equipment ORDER BY id');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM equipment WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM equipment WHERE name LIKE :kw OR category LIKE :kw ORDER BY name'
        );
        $stmt->execute([':kw' => "%$keyword%"]);
        return $stmt->fetchAll();
    }

    /* ---------- CREATE ---------- */

    public function create(array $data): int
    {
        $sql = 'INSERT INTO equipment (name, description, category, total_quantity, available_qty, image_url)
                VALUES (:name, :description, :category, :total_quantity, :available_qty, :image_url)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'           => $data['name'],
            ':description'    => $data['description'] ?? null,
            ':category'       => $data['category'] ?? null,
            ':total_quantity' => $data['total_quantity'] ?? 1,
            ':available_qty'  => $data['available_qty'] ?? $data['total_quantity'] ?? 1,
            ':image_url'      => $data['image_url'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /* ---------- UPDATE ---------- */

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE equipment SET name = :name, description = :description, category = :category,
                total_quantity = :total_quantity, available_qty = :available_qty, image_url = :image_url
                WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':name'           => $data['name'],
            ':description'    => $data['description'] ?? null,
            ':category'       => $data['category'] ?? null,
            ':total_quantity' => $data['total_quantity'],
            ':available_qty'  => $data['available_qty'],
            ':image_url'      => $data['image_url'] ?? null,
        ]);
    }

    /* ---------- STOCK HELPERS ---------- */

    public function decreaseStock(int $id, int $qty): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE equipment SET available_qty = available_qty - :qty WHERE id = :id AND available_qty >= :qty'
        );
        $stmt->execute([':id' => $id, ':qty' => $qty]);
        return $stmt->rowCount() > 0;
    }

    public function increaseStock(int $id, int $qty): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE equipment SET available_qty = available_qty + :qty WHERE id = :id'
        );
        return $stmt->execute([':id' => $id, ':qty' => $qty]);
    }

    /* ---------- DELETE ---------- */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM equipment WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
