<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    private Role $roles;

    public function __construct()
    {
        $this->roles = new Role();
    }

    public function index(): void
    {
        $this->json($this->roles->all());
    }

    public function show($id): void
    {
        $role = $this->roles->find((int) $id);

        if (!$role) {
            $this->json(['error' => 'Role not found'], 404);
        }

        $this->json($role);
    }

    public function store(): void
    {
        $data = $this->input();
        if (empty($data['name'])) {
            $this->json(['error' => 'Name is required'], 422);
        }

        $this->roles->create($data);
        $this->json(['message' => 'Role created']);
    }

    public function update($id): void
    {
        $data = $this->input();
        if (empty($data['name'])) {
            $this->json(['error' => 'Name is required'], 422);
        }

        $this->roles->updateRole((int) $id, $data);
        $this->json(['message' => 'Role updated']);
    }

    public function delete($id): void
    {
        $this->roles->delete((int) $id);
        $this->json(['message' => 'Role deleted']);
    }
}
