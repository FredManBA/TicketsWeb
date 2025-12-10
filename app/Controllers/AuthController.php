<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
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
            header('Location: /');
        } else {
            return $this->view('auth/login', ['error' => 'Credenciales invalidas o usuario inactivo']);
        }
    }

    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        return $this->view('auth/register');
    }

    public function store()
    {
        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm_password) {
            return $this->view('auth/register', ['error' => 'Las contrasenas no coinciden']);
        }

        if ($fullname === '' || $username === '') {
            return $this->view('auth/register', ['error' => 'Nombre completo y usuario son obligatorios']);
        }

        $userModel = new User();

        if ($userModel->findByUsername($username)) {
            return $this->view('auth/register', ['error' => 'El usuario ya existe']);
        }

        $userModel->create([
            'fullname' => $fullname,
            'username' => $username,
            'password' => $password,
            'roleId' => 1,
            'isActive' => 1,
        ]);

        header('Location: /login');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }
}
