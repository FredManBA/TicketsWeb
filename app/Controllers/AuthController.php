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
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username']
            ];
            header('Location: /');
        } else {
            return $this->view('auth/login', ['error' => 'Credenciales invケlidas']);
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
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            return $this->view('auth/register', ['error' => 'Las contraseヵas no coinciden']);
        }

        $userModel = new User();

        if ($userModel->findByUsername($username)) {
            return $this->view('auth/register', ['error' => 'El usuario ya existe']);
        }

        $userModel->create([
            'username' => $username,
            'password' => $password
        ]);

        header('Location: /login');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }
}
