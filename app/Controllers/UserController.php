<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private User $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new User();
    }

    public function index(): void
    {
        $this->requireRole([1]);
        $users = $this->users->all();
        $this->view('users/index', ['users' => $users]);
    }

    public function create(): void
    {
        $this->requireRole([1]);
        $this->view('users/create');
    }

    public function edit($id): void
    {
        $this->requireRole([1]);
        $user = $this->users->find((int) $id);
        if (!$user) {
            $_SESSION['flash_error'] = 'Usuario no encontrado';
            header('Location: /users');
            exit;
        }
        $this->view('users/edit', ['user' => $user]);
    }

    public function show($id): void
    {
        $this->requireRole([1]);
        $user = $this->users->find((int) $id);

        if (!$user) {
            $this->json(['error' => 'User not found'], 404);
        }

        unset($user['passwordHash']);
        $this->json($user);
    }

    public function store(): void
    {
        $this->requireRole([1]);
        $data = $this->input();
        $required = ['fullname', 'username', 'password', 'roleId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $_SESSION['flash_error'] = "{$field} is required";
                header('Location: /users/create');
                exit;
            }
        }

        $this->users->create($data);
        $_SESSION['flash_success'] = 'User created';
        header('Location: /users');
        exit;
    }

    public function update($id): void
    {
        $this->requireRole([1]);
        $data = $this->input();
        $required = ['fullname', 'username', 'roleId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $_SESSION['flash_error'] = "{$field} is required";
                header('Location: /users/edit/' . (int) $id);
                exit;
            }
        }

        $this->users->updateUser((int) $id, $data);
        $_SESSION['flash_success'] = 'User updated';
        header('Location: /users');
        exit;
    }

    public function deactivate($id): void
    {
        $this->requireRole([1]);
        $this->users->deactivate((int) $id);
        $_SESSION['flash_success'] = 'User deactivated';
        header('Location: /users');
        exit;
    }

    public function activate($id): void
    {
        $this->requireRole([1]);
        $this->users->activate((int) $id);
        $_SESSION['flash_success'] = 'User activated';
        header('Location: /users');
        exit;
    }
}
