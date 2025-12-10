<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Ticket extends Model
{
    private string $table = 'tickets';

    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT t.*, tp.name AS typeName, s.name AS statusName
             FROM {$this->table} t
             JOIN types tp ON t.typeId = tp.id
             JOIN status s ON t.statusId = s.id
             ORDER BY t.createdAt DESC"
        );

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare(
            "SELECT t.*, tp.name AS typeName, s.name AS statusName
             FROM {$this->table} t
             JOIN types tp ON t.typeId = tp.id
             JOIN status s ON t.statusId = s.id
             WHERE t.id = :id"
        );
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (title, summary, typeId, statusId, createdBy, assignedTo)
             VALUES (:title, :summary, :typeId, :statusId, :createdBy, :assignedTo)"
        );

        $statement->execute([
            ':title' => $data['title'],
            ':summary' => $data['summary'],
            ':typeId' => $data['typeId'],
            ':statusId' => $data['statusId'],
            ':createdBy' => $data['createdBy'],
            ':assignedTo' => $data['assignedTo'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateTicket(int $id, array $data): bool
    {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
             SET title = :title,
                 summary = :summary,
                 typeId = :typeId,
                 statusId = :statusId,
                 assignedTo = :assignedTo
             WHERE id = :id"
        );

        return $statement->execute([
            ':title' => $data['title'],
            ':summary' => $data['summary'],
            ':typeId' => $data['typeId'],
            ':statusId' => $data['statusId'],
            ':assignedTo' => $data['assignedTo'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute([':id' => $id]);
    }

    public function updateStatus(int $id, int $statusId): bool
    {
        $statement = $this->db->prepare(
            "UPDATE {$this->table} SET statusId = :statusId WHERE id = :id"
        );

        return $statement->execute([
            ':statusId' => $statusId,
            ':id' => $id,
        ]);
    }
}
