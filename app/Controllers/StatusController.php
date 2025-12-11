<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Status;

class StatusController extends Controller
{
    private Status $status;

    public function __construct()
    {
        $this->status = new Status();
    }

    public function index(): void
    {
        $this->json($this->status->all());
    }

    public function show($id): void
    {
        $status = $this->status->find((int) $id);

        if (!$status) {
            $this->json(['error' => 'Status not found'], 404);
        }

        $this->json($status);
    }

    public function store(): void
    {
        $data = $this->input();
        if (empty($data['name'])) {
            $this->json(['error' => 'Name is required'], 422);
        }

        $this->status->create($data);
        $this->json(['message' => 'Status created']);
    }

    public function update($id): void
    {
        $data = $this->input();
        if (empty($data['name'])) {
            $this->json(['error' => 'Name is required'], 422);
        }

        $this->status->updateStatus((int) $id, $data);
        $this->json(['message' => 'Status updated']);
    }

    public function delete($id): void
    {
        $this->status->delete((int) $id);
        $this->json(['message' => 'Status deleted']);
    }
}
