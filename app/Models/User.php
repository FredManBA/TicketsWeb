<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function findByUsername($username)
    {
        $statement = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $statement->execute([':username' => $username]);

        return $statement->fetch();
    }

    public function create($data)
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $statement = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $statement->execute([
            ':username' => $data['username'],
            ':password' => $password
        ]);
    }

    public function find($id)
    {
        $statement = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $statement->execute([':id' => $id]);

        return $statement->fetch();
    }

    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $statement = $this->db->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
            $params = [
                ':username' => $data['username'],
                ':password' => $password,
                ':id' => $id
            ];
        } else {
            $statement = $this->db->prepare("UPDATE users SET username = :username WHERE id = :id");
            $params = [
                ':username' => $data['username'],
                ':id' => $id
            ];
        }

        return $statement->execute($params);
    }
}
