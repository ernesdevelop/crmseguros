<?php
namespace App\Models;

class User extends BaseModel
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, role) VALUES (?, ?, ?)');
        return $stmt->execute([
            trim((string) ($data['name'] ?? '')),
            trim((string) ($data['email'] ?? '')),
            $data['role'] ?? 'operador',
        ]);
    }
}
