<?php

namespace App\Models;

use App\Core\Model;

class Entry extends Model
{
    private string $table = 'entries';

    public function allByTicket(int $ticketId): array
    {
        $statement = $this->db->prepare(
            "SELECT e.*, u.username AS authorUsername,
                    fs.name AS fromStatusName, ts.name AS toStatusName
             FROM {$this->table} e
             JOIN users u ON e.authorId = u.id
             LEFT JOIN status fs ON e.fromStatusId = fs.id
             LEFT JOIN status ts ON e.toStatusId = ts.id
             WHERE e.ticketId = :ticketId
             ORDER BY e.createdAt ASC"
        );
        $statement->execute([':ticketId' => $ticketId]);

        return $statement->fetchAll();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (ticketId, authorId, body, fromStatusId, toStatusId)
             VALUES (:ticketId, :authorId, :body, :fromStatusId, :toStatusId)"
        );

        $statement->execute([
            ':ticketId' => $data['ticketId'],
            ':authorId' => $data['authorId'],
            ':body' => $data['body'],
            ':fromStatusId' => $data['fromStatusId'] ?? null,
            ':toStatusId' => $data['toStatusId'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
