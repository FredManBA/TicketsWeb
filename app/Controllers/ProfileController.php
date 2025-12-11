<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller {
    private User $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new User();
    }

    public function edit(): void
    {
        $this->requireLogin();

        $user = $this->users->find((int) $_SESSION['user']['id']);

        if (!$user) {
            $_SESSION['flash_error'] = 'Usuario no encontrado';
            header('Location: /login');
            exit;
        }

        unset($user['passwordHash']);

        $this->view('profile/show', [
            'user' => $user,
            'flashError' => $_SESSION['flash_error'] ?? null,
            'flashSuccess' => $_SESSION['flash_success'] ?? null,
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }
    public function update(): void {
        // Edición deshabilitada en vista de perfil; solo se muestra la información.
        $_SESSION['flash_error'] = 'La edición de perfil no está habilitada.';
        header('Location: /profile');
        exit;
    }
}
