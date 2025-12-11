<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Entry;

class EntryController extends Controller
{
    private Entry $entries;

    public function __construct()
    {
        $this->entries = new Entry();
    }

    public function index($ticketId): void
    {
        $this->json($this->entries->allByTicket((int) $ticketId));
    }

    public function store($ticketId): void
    {
        $data = $this->input();

        $required = ['authorId', 'body'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json(['error' => "{$field} is required"], 422);
            }
        }

        $payload = array_merge($data, ['ticketId' => (int) $ticketId]);
        $id = $this->entries->create($payload);

        $this->json(['message' => 'Entry created', 'id' => $id]);
    }
}
