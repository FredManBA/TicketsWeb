<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $roleId = $_SESSION['user']['roleId'] ?? null;
        if ($roleId === 1) {
            header('Location: /admin/dashboard');
        } elseif ($roleId === 2) {
            header('Location: /operator/dashboard');
        } elseif ($roleId === 3) {
            header('Location: /user/dashboard');
        } else {
            header('Location: /login');
        }
        exit;
    }
}
