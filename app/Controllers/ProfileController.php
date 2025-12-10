<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function edit(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $this->users->find((int) $_SESSION['user']['id']);
        if ($user) {
            unset($user['passwordHash']);
        }

        $this->json($user ?? ['error' => 'User not found'], $user ? 200 : 404);
    }

    public function update(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $data = $this->input();
        $payload = [
            'fullname' => $data['fullname'] ?? '',
            'username' => $data['username'] ?? '',
            'roleId' => $data['roleId'] ?? $_SESSION['user']['roleId'] ?? 1,
            'isActive' => 1,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        if (empty($payload['fullname']) || empty($payload['username'])) {
            $this->json(['error' => 'fullname and username are required'], 422);
        }

        $this->users->updateUser((int) $_SESSION['user']['id'], $payload);
        $this->json(['message' => 'Profile updated']);
    }
}
