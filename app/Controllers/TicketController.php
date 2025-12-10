<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Entry;
use App\Models\Ticket;

class TicketController extends Controller
{
    private Ticket $tickets;
    private Entry $entries;

    public function __construct()
    {
        $this->tickets = new Ticket();
        $this->entries = new Entry();
    }

    public function index(): void
    {
        $this->json($this->tickets->all());
    }

    public function show($id): void
    {
        $ticket = $this->tickets->find((int) $id);

        if (!$ticket) {
            $this->json(['error' => 'Ticket not found'], 404);
        }

        $entries = $this->entries->allByTicket((int) $id);
        $this->json([
            'ticket' => $ticket,
            'entries' => $entries,
        ]);
    }

    public function store(): void
    {
        $data = $this->input();
        $required = ['title', 'summary', 'typeId', 'statusId', 'createdBy'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json(['error' => "{$field} is required"], 422);
            }
        }

        $id = $this->tickets->create($data);
        $this->json(['message' => 'Ticket created', 'id' => $id]);
    }

    public function update($id): void
    {
        $data = $this->input();
        $required = ['title', 'summary', 'typeId', 'statusId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json(['error' => "{$field} is required"], 422);
            }
        }

        $this->tickets->updateTicket((int) $id, $data);
        $this->json(['message' => 'Ticket updated']);
    }

    public function delete($id): void
    {
        $this->tickets->delete((int) $id);
        $this->json(['message' => 'Ticket deleted']);
    }
}
