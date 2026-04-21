<?php
namespace App\Models;

class Client extends BaseModel
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM clients ORDER BY id DESC')->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO clients (full_name, document, phone, email, address, status) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            trim((string) ($data['full_name'] ?? '')),
            trim((string) ($data['document'] ?? '')),
            $data['phone'] ?? null,
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['status'] ?? 'activo',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE clients SET full_name = ?, document = ?, phone = ?, email = ?, address = ?, status = ? WHERE id = ?');
        return $stmt->execute([
            trim((string) ($data['full_name'] ?? '')),
            trim((string) ($data['document'] ?? '')),
            $data['phone'] ?? null,
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['status'] ?? 'activo',
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM clients WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM clients WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
