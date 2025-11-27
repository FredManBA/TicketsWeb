<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public static function findByUsername($username)
    {
        $statement = self::connection()->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(':username', $username);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public static function create($data)
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $statement = self::connection()->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $statement->bindValue(':username', $data['username']);
        $statement->bindValue(':password', $password);
        return $statement->execute();
    }

    public static function find($id)
    {
        $statement = self::connection()->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(':id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public static function update($id, $data)
    {
        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $statement = self::connection()->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
            $statement->bindValue(':password', $password);
        } else {
            $statement = self::connection()->prepare("UPDATE users SET username = :username WHERE id = :id");
        }

        $statement->bindValue(':id', $id);
        $statement->bindValue(':username', $data['username']);
        return $statement->execute();
    }
}
