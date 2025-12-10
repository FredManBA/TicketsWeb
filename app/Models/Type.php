<?php

namespace App\Models;

use App\Core\Model;

class Type extends Model
{
    private string $table = 'types';

    public function all(): array
    {
        $statement = $this->db->query("SELECT * FROM {$this->table} ORDER BY id ASC");
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $statement->execute([':id' => $id]);

        $result = $statement->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)"
        );

        return $statement->execute([
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
        ]);
    }

    public function updateType(int $id, array $data): bool
    {
        $statement = $this->db->prepare(
            "UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id"
        );

        return $statement->execute([
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute([':id' => $id]);
    }
}
