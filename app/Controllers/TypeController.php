<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Type;

class TypeController extends Controller
{
    private Type $types;

    public function __construct()
    {
        $this->types = new Type();
    }

    public function index(): void
    {
        $this->json($this->types->all());
    }

    public function show($id): void
    {
        $type = $this->types->find((int) $id);

        if (!$type) {
            $this->json(['error' => 'Type not found'], 404);
        }

        $this->json($type);
    }

    public function store(): void
    {
        $data = $this->input();
        if (empty($data['name'])) {
            $this->json(['error' => 'Name is required'], 422);
        }

        $this->types->create($data);
        $this->json(['message' => 'Type created']);
    }

    public function update($id): void
    {
        $data = $this->input();
        if (empty($data['name'])) {
            $this->json(['error' => 'Name is required'], 422);
        }

        $this->types->updateType((int) $id, $data);
        $this->json(['message' => 'Type updated']);
    }

    public function delete($id): void
    {
        $this->types->delete((int) $id);
        $this->json(['message' => 'Type deleted']);
    }
}
