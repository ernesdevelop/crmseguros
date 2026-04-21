<?php
namespace App\Models;

class Insurer extends BaseModel
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM insurers ORDER BY id DESC')->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO insurers (name, contact_email, contact_phone) VALUES (?, ?, ?)');
        return $stmt->execute([
            trim((string) ($data['name'] ?? '')),
            $data['contact_email'] ?? null,
            $data['contact_phone'] ?? null,
        ]);
    }
}
