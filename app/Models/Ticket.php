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

    public function findByCreator(int $creatorId, ?int $statusId = null): array
    {
        $params = [':createdBy' => $creatorId];
        $statusFilter = '';

        if ($statusId !== null) {
            $statusFilter = ' AND t.statusId = :statusId';
            $params[':statusId'] = $statusId;
        }

        $sql = "SELECT t.*, tp.name AS typeName, s.name AS statusName
                FROM {$this->table} t
                JOIN types tp ON t.typeId = tp.id
                JOIN status s ON t.statusId = s.id
                WHERE t.createdBy = :createdBy {$statusFilter}
                ORDER BY t.createdAt DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
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

    public function findQueue(): array
    {
        $statement = $this->db->prepare(
            "SELECT t.*, tp.name AS typeName, s.name AS statusName, u.username AS creatorUsername
             FROM {$this->table} t
             JOIN types tp ON t.typeId = tp.id
             JOIN status s ON t.statusId = s.id
             JOIN users u ON t.createdBy = u.id
             WHERE t.statusId = 1
             ORDER BY t.createdAt ASC"
        );

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAssignedForOperator(int $operatorId, ?int $statusId = null): array
    {
        $params = [':assignedTo' => $operatorId];
        $statusFilter = '';

        if ($statusId !== null) {
            $statusFilter = ' AND t.statusId = :statusId';
            $params[':statusId'] = $statusId;
        }

        $sql = "SELECT t.*, tp.name AS typeName, s.name AS statusName
                FROM {$this->table} t
                JOIN types tp ON t.typeId = tp.id
                JOIN status s ON t.statusId = s.id
                WHERE t.assignedTo = :assignedTo {$statusFilter}
                  AND t.statusId IN (2, 3, 4, 5)
                ORDER BY t.createdAt DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignToOperator(int $id, int $operatorId): bool
    {
        $statement = $this->db->prepare(
            "UPDATE {$this->table}
             SET assignedTo = :assignedTo, statusId = 2
             WHERE id = :id"
        );

        return $statement->execute([
            ':assignedTo' => $operatorId,
            ':id' => $id,
        ]);
    }

    public function searchAdmin(?int $statusId, ?int $typeId, $operatorFilter, ?string $query): array
    {
        $conditions = [];
        $params = [];

        if ($statusId !== null) {
            $conditions[] = 't.statusId = :statusId';
            $params[':statusId'] = $statusId;
        }

        if ($typeId !== null) {
            $conditions[] = 't.typeId = :typeId';
            $params[':typeId'] = $typeId;
        }

        if ($operatorFilter !== null) {
            if ($operatorFilter === 'null') {
                $conditions[] = 't.assignedTo IS NULL';
            } else {
                $conditions[] = 't.assignedTo = :assignedTo';
                $params[':assignedTo'] = (int) $operatorFilter;
            }
        }

        if ($query !== null && $query !== '') {
            if (ctype_digit($query)) {
                $conditions[] = 't.id = :ticketId';
                $params[':ticketId'] = (int) $query;
            } else {
                $conditions[] = 't.title LIKE :query';
                $params[':query'] = '%' . $query . '%';
            }
        }

        $where = '';
        if (count($conditions) > 0) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $sql = "SELECT t.*,
                       tp.name AS typeName,
                       s.name AS statusName,
                       uc.fullname AS creatorName,
                       ua.fullname AS operatorName
                FROM {$this->table} t
                JOIN types tp ON t.typeId = tp.id
                JOIN status s ON t.statusId = s.id
                JOIN users uc ON t.createdBy = uc.id
                LEFT JOIN users ua ON t.assignedTo = ua.id
                {$where}
                ORDER BY t.createdAt DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAdminTicket(int $id): ?array
    {
        $statement = $this->db->prepare(
            "SELECT t.*,
                    tp.name AS typeName,
                    s.name AS statusName,
                    uc.fullname AS creatorName,
                    uc.username AS creatorUsername,
                    ua.fullname AS operatorName,
                    ua.username AS operatorUsername
             FROM {$this->table} t
             JOIN types tp ON t.typeId = tp.id
             JOIN status s ON t.statusId = s.id
             JOIN users uc ON t.createdBy = uc.id
             LEFT JOIN users ua ON t.assignedTo = ua.id
             WHERE t.id = :id"
        );
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
