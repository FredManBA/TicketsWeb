<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(): void
    {
        $this->json($this->users->all());
    }

    public function show($id): void
    {
        $user = $this->users->find((int) $id);

        if (!$user) {
            $this->json(['error' => 'User not found'], 404);
        }

        unset($user['passwordHash']);
        $this->json($user);
    }

    public function store(): void
    {
        $data = $this->input();
        $required = ['fullname', 'username', 'password', 'roleId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json(['error' => "{$field} is required"], 422);
            }
        }

        $id = $this->users->create($data);
        $this->json(['message' => 'User created', 'id' => $id]);
    }

    public function update($id): void
    {
        $data = $this->input();
        $required = ['fullname', 'username', 'roleId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json(['error' => "{$field} is required"], 422);
            }
        }

        $this->users->updateUser((int) $id, $data);
        $this->json(['message' => 'User updated']);
    }

    public function delete($id): void
    {
        $this->users->delete((int) $id);
        $this->json(['message' => 'User deleted']);
    }
}
