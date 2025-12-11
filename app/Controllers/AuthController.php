<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirectByRole($this->currentRoleId());
        }

        return $this->view('auth/login');
    }

    public function authenticate()
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (
            $user &&
            (int) ($user['isActive'] ?? 0) === 1 &&
            password_verify($password, $user['passwordHash'])
        ) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'roleId' => $user['roleId'],
            ];

            $this->redirectByRole((int) $user['roleId']);
        } else {
            return $this->view('auth/login', ['error' => 'Credenciales invalidas o usuario inactivo']);
        }
    }

    public function logout()
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header('Location: /login');
        exit;
    }

    private function redirectByRole(?int $roleId): void
    {
        if ($roleId === 1) {
            header('Location: /admin/dashboard');
        } elseif ($roleId === 2) {
            header('Location: /operator/dashboard');
        } elseif ($roleId === 3) {
            header('Location: /user/dashboard');
        } else {
            header('Location: /');
        }
        exit;
    }
}
