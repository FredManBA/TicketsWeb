<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Transition;

class TransitionController extends Controller
{
    private Transition $transitions;

    public function __construct()
    {
        $this->transitions = new Transition();
    }

    public function index(): void
    {
        $this->json($this->transitions->all());
    }

    public function store(): void
    {
        $data = $this->input();

        if (empty($data['fromStatusId']) || empty($data['toStatusId'])) {
            $this->json(['error' => 'fromStatusId and toStatusId are required'], 422);
        }

        $this->transitions->create($data);
        $this->json(['message' => 'Transition created']);
    }

    public function delete($id): void
    {
        $this->transitions->delete((int) $id);
        $this->json(['message' => 'Transition deleted']);
    }
}
