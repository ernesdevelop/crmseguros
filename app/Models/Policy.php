<?php
namespace App\Models;

class Policy extends BaseModel
{
    public function all(): array
    {
        $sql = 'SELECT p.*, c.full_name AS client_name, i.name AS insurer_name
                FROM policies p
                INNER JOIN clients c ON c.id = p.client_id
                INNER JOIN insurers i ON i.id = p.insurer_id
                ORDER BY p.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO policies (policy_number, client_id, insurer_id, coverage_type, start_date, end_date, premium, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        return $stmt->execute([
            trim((string) ($data['policy_number'] ?? '')),
            (int) ($data['client_id'] ?? 0),
            (int) ($data['insurer_id'] ?? 0),
            trim((string) ($data['coverage_type'] ?? '')),
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['premium'] ?? 0,
            $data['status'] ?? 'vigente',
        ]);
    }

    public function upcomingRenewals(int $days = 30): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.policy_number, p.end_date, c.full_name AS client_name, i.name AS insurer_name
             FROM policies p
             INNER JOIN clients c ON c.id = p.client_id
             INNER JOIN insurers i ON i.id = p.insurer_id
             WHERE p.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
             ORDER BY p.end_date ASC'
        );
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}
