<?php

namespace App\Models;

use App\Core\Model;

class Transition extends Model
{
    private string $table = 'transitions';

    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT t.*, fs.name AS fromStatusName, ts.name AS toStatusName
             FROM {$this->table} t
             JOIN status fs ON t.fromStatusId = fs.id
             JOIN status ts ON t.toStatusId = ts.id
             ORDER BY t.id ASC"
        );

        return $statement->fetchAll();
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (fromStatusId, toStatusId)
             VALUES (:fromStatusId, :toStatusId)"
        );

        return $statement->execute([
            ':fromStatusId' => $data['fromStatusId'],
            ':toStatusId' => $data['toStatusId'],
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute([':id' => $id]);
    }
}
