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
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        return $stmt->execute([
            trim((string) ($data['name'] ?? '')),
            trim((string) ($data['email'] ?? '')),
            (string) ($data['password_hash'] ?? ''),
            $data['role'] ?? 'operador',
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([trim($email)]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public function verifyCredentials(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if ($user === null) {
            return null;
        }

        $hash = (string) ($user['password_hash'] ?? '');
        if ($hash === '' || !password_verify($password, $hash)) {
            return null;
        }

        return [
            'id' => (int) $user['id'],
            'name' => (string) $user['name'],
            'email' => (string) $user['email'],
            'role' => (string) $user['role'],
        ];
    }
}
