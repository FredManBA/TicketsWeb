<?php

namespace App\Core;

class Controller
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function view(string $path, array $data = [])
    {
        return View::render($path, $data);
    }

    protected function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function currentRoleId(): ?int
    {
        return isset($_SESSION['user']['roleId']) ? (int) $_SESSION['user']['roleId'] : null;
    }

    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    protected function requireRole(array $allowedRoleIds): void
    {
        $this->requireLogin();

        $roleId = $this->currentRoleId();
        if ($roleId === null || !in_array($roleId, $allowedRoleIds, true)) {
            http_response_code(403);
            echo 'Acceso denegado.';
            exit;
        }
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function input(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }

        return $_POST;
    }
}
