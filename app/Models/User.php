<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    private string $table = 'users';

    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT u.id, u.fullname, u.username, u.roleId, u.isActive, u.createdAt, u.updatedAt, r.name AS roleName
             FROM {$this->table} u
             LEFT JOIN roles r ON u.roleId = r.id
             ORDER BY u.id ASC"
        );

        return $statement->fetchAll();
    }

    public function findByUsername(string $username): ?array
    {
        $statement = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1"
        );
        $statement->execute([':username' => $username]);

        $result = $statement->fetch();
        return $result ?: null;
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $statement->execute([':id' => $id]);

        $result = $statement->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        $statement = $this->db->prepare(
            "INSERT INTO {$this->table} (fullname, username, passwordHash, roleId, isActive)
             VALUES (:fullname, :username, :passwordHash, :roleId, :isActive)"
        );

        $statement->execute([
            ':fullname' => $data['fullname'],
            ':username' => $data['username'],
            ':passwordHash' => $passwordHash,
            ':roleId' => $data['roleId'],
            ':isActive' => $data['isActive'] ?? 1,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateUser(int $id, array $data): bool
    {
        $fields = [
            'fullname' => ':fullname',
            'username' => ':username',
            'roleId' => ':roleId',
            'isActive' => ':isActive',
        ];
        $params = [
            ':fullname' => $data['fullname'],
            ':username' => $data['username'],
            ':roleId' => $data['roleId'],
            ':isActive' => $data['isActive'] ?? 1,
            ':id' => $id,
        ];

        if (!empty($data['password'])) {
            $fields['passwordHash'] = ':passwordHash';
            $params[':passwordHash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $setClause = implode(', ', array_map(
            fn($column, $placeholder) => "{$column} = {$placeholder}",
            array_keys($fields),
            $fields
        ));

        $statement = $this->db->prepare(
            "UPDATE {$this->table} SET {$setClause} WHERE id = :id"
        );

        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute([':id' => $id]);
    }
}
