<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Entry;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Transition;
use App\Models\Type;
use App\Models\User;

class TicketController extends Controller
{
    private Ticket $tickets;
    private Entry $entries;

    public function __construct()
    {
        parent::__construct();
        $this->tickets = new Ticket();
        $this->entries = new Entry();
    }

    public function index(): void
    {
        $this->requireRole([1]);
        $this->json($this->tickets->all());
    }

    public function show($id): void
    {
        $this->requireRole([1, 2, 3]);
        $ticket = $this->tickets->find((int) $id);

        if (!$ticket) {
            $this->json(['error' => 'Ticket not found'], 404);
        }

        $roleId = $this->currentRoleId();
        $currentUser = $this->currentUser();
        $currentUserId = $currentUser['id'] ?? null;

        if ($roleId === 2 && $ticket['assignedTo'] !== (int) $currentUserId) {
            $this->json(['error' => 'Acceso denegado'], 403);
        }

        if ($roleId === 3 && $ticket['createdBy'] !== (int) $currentUserId) {
            $this->json(['error' => 'Acceso denegado'], 403);
        }

        $entries = $this->entries->allByTicket((int) $id);
        $this->json([
            'ticket' => $ticket,
            'entries' => $entries,
        ]);
    }

    public function store(): void
    {
        $this->requireRole([3]);
        $data = $this->input();
        $required = ['title', 'summary', 'typeId'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $_SESSION['flash_error'] = "{$field} is required";
                header('Location: /user/tickets/create');
                exit;
            }
        }

        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);

        $ticketPayload = [
            'title' => $data['title'],
            'summary' => $data['summary'],
            'typeId' => (int) $data['typeId'],
            'statusId' => 1,
            'createdBy' => $userId,
            'assignedTo' => null,
        ];

        $id = $this->tickets->create($ticketPayload);

        // entrada inicial
        $this->entries->create([
            'ticketId' => $id,
            'authorId' => $userId,
            'body' => $data['summary'],
            'fromStatusId' => null,
            'toStatusId' => 1,
        ]);

        $_SESSION['flash_success'] = 'Ticket creado';
        header('Location: /user/tickets/' . $id);
        exit;
    }

    public function update($id): void
    {
        $this->requireRole([2]);
        $ticket = $this->tickets->find((int) $id);

        if (!$ticket) {
            $_SESSION['flash_error'] = 'Ticket not found';
            header('Location: /operator/dashboard');
            exit;
        }

        $currentUser = $this->currentUser();
        $currentUserId = $currentUser['id'] ?? null;

        if ((int) $ticket['assignedTo'] !== (int) $currentUserId) {
            $_SESSION['flash_error'] = 'Acceso denegado';
            header('Location: /operator/dashboard');
            exit;
        }

        $data = $this->input();
        $body = trim($data['body'] ?? '');
        if ($body === '') {
            $_SESSION['flash_error'] = 'body is required';
            header('Location: /operator/tickets/' . (int) $id);
            exit;
        }

        $currentStatusId = (int) $ticket['statusId'];
        $newStatusId = isset($data['new_status_id']) && $data['new_status_id'] !== ''
            ? (int) $data['new_status_id']
            : $currentStatusId;

        $statusChanged = $newStatusId !== $currentStatusId;

        if ($statusChanged) {
            $transitionModel = new Transition();
            $allowed = array_filter(
                $transitionModel->all(),
                fn($t) => (int) $t['fromStatusId'] === $currentStatusId && (int) $t['toStatusId'] === $newStatusId
            );

            if (count($allowed) === 0) {
                $_SESSION['flash_error'] = 'Transicion no permitida';
                header('Location: /operator/tickets/' . (int) $id);
                exit;
            }
        }

        $this->entries->create([
            'ticketId' => (int) $id,
            'authorId' => (int) $currentUserId,
            'body' => $body,
            'fromStatusId' => $currentStatusId,
            'toStatusId' => $statusChanged ? $newStatusId : null,
        ]);

        if ($statusChanged) {
            $this->tickets->updateStatus((int) $id, $newStatusId);
        }

        header('Location: /operator/tickets/' . (int) $id);
        exit;
    }

    public function delete($id): void
    {
        $this->requireRole([1]);
        $this->tickets->delete((int) $id);
        $this->json(['message' => 'Ticket deleted']);
    }

    public function operatorDashboard(): void
    {
        $this->requireRole([2]);

        $user = $this->currentUser();
        $operatorId = (int) ($user['id'] ?? 0);
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? (int) $_GET['status'] : null;

        $queueTickets = $this->tickets->findQueue();
        $myTickets = $this->tickets->findAssignedForOperator($operatorId, $statusFilter);

        $this->view('operator/dashboard', [
            'queueTickets' => $queueTickets,
            'myTickets' => $myTickets,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function assignTicket($id): void
    {
        $this->requireRole([2]);

        $ticket = $this->tickets->find((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        if ((int) $ticket['statusId'] !== 1) {
            http_response_code(422);
            echo 'El ticket no esta en estado No Asignado';
            exit;
        }

        $user = $this->currentUser();
        $operatorId = (int) ($user['id'] ?? 0);

        $this->tickets->assignToOperator((int) $id, $operatorId);

        $this->entries->create([
            'ticketId' => (int) $id,
            'authorId' => $operatorId,
            'body' => 'Ticket autoasignado por operador',
            'fromStatusId' => 1,
            'toStatusId' => 2,
        ]);

        header('Location: /operator/dashboard');
        exit;
    }

    public function showOperatorTicket($id): void
    {
        $this->requireRole([2]);

        $ticket = $this->tickets->find((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        $user = $this->currentUser();
        $operatorId = (int) ($user['id'] ?? 0);

        if ((int) $ticket['assignedTo'] !== $operatorId) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }

        $entries = $this->entries->allByTicket((int) $id);

        $statusOptions = [
            2 => 'Asignado',
            3 => 'En Proceso',
            4 => 'En Espera de Terceros',
            5 => 'Solucionado',
        ];

        $this->view('operator/ticket_detail', [
            'ticket' => $ticket,
            'entries' => $entries,
            'statusOptions' => $statusOptions,
            'flashError' => $_SESSION['flash_error'] ?? null,
        ]);

        if (isset($_SESSION['flash_error'])) {
            unset($_SESSION['flash_error']);
        }
    }

    public function adminDashboard(): void
    {
        $this->requireRole([1]);

        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? (int) $_GET['status'] : null;
        $typeFilter = isset($_GET['type']) && $_GET['type'] !== '' ? (int) $_GET['type'] : null;

        $operatorParam = $_GET['operator'] ?? '';
        $operatorFilter = null;
        if ($operatorParam !== '') {
            $operatorFilter = $operatorParam === 'null' ? 'null' : (int) $operatorParam;
        }

        $query = trim($_GET['q'] ?? '');

        $tickets = $this->tickets->searchAdmin($statusFilter, $typeFilter, $operatorFilter, $query);

        $statusModel = new Status();
        $typeModel = new Type();
        $userModel = new User();

        $this->view('admin/dashboard', [
            'tickets' => $tickets,
            'statuses' => $statusModel->all(),
            'types' => $typeModel->all(),
            'operators' => $userModel->findByRole(2, true),
            'statusFilter' => $statusFilter,
            'typeFilter' => $typeFilter,
            'operatorFilter' => $operatorFilter,
            'query' => $query,
        ]);
    }

    public function showAdminTicket($id): void
    {
        $this->requireRole([1]);

        $ticket = $this->tickets->findAdminTicket((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        $entries = $this->entries->allByTicket((int) $id);

        $this->view('admin/ticket_detail', [
            'ticket' => $ticket,
            'entries' => $entries,
        ]);
    }

    public function userDashboard(): void
    {
        $this->requireRole([3]);

        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? (int) $_GET['status'] : null;

        $tickets = $this->tickets->findByCreator($userId, $statusFilter);

        $this->view('user/dashboard', [
            'tickets' => $tickets,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function showUserTicket($id): void
    {
        $this->requireRole([3]);

        $ticket = $this->tickets->find((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);

        if ((int) $ticket['createdBy'] !== $userId) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }

        $entries = $this->entries->allByTicket((int) $id);

        $this->view('user/ticket_detail', [
            'ticket' => $ticket,
            'entries' => $entries,
            'flashError' => $_SESSION['flash_error'] ?? null,
            'flashSuccess' => $_SESSION['flash_success'] ?? null,
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function acceptSolution($id): void
    {
        $this->requireRole([3]);

        $ticket = $this->tickets->find((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);

        if ((int) $ticket['createdBy'] !== $userId) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }

        $currentStatusId = (int) $ticket['statusId'];
        if ($currentStatusId !== 5) {
            http_response_code(422);
            echo 'El ticket no se encuentra en estado Solucionado';
            exit;
        }

        $transitionModel = new Transition();
        $allowed = array_filter(
            $transitionModel->all(),
            fn($t) => (int) $t['fromStatusId'] === 5 && (int) $t['toStatusId'] === 6
        );

        if (count($allowed) === 0) {
            http_response_code(422);
            echo 'Transicion no permitida';
            exit;
        }

        $this->entries->create([
            'ticketId' => (int) $id,
            'authorId' => $userId,
            'body' => 'Usuario acepto la solucion',
            'fromStatusId' => 5,
            'toStatusId' => 6,
        ]);

        $this->tickets->updateStatus((int) $id, 6);

        header('Location: /user/tickets/' . (int) $id);
        exit;
    }

    public function rejectSolution($id): void
    {
        $this->requireRole([3]);

        $ticket = $this->tickets->find((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);

        if ((int) $ticket['createdBy'] !== $userId) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }

        $currentStatusId = (int) $ticket['statusId'];
        if ($currentStatusId !== 5) {
            http_response_code(422);
            echo 'El ticket no se encuentra en estado Solucionado';
            exit;
        }

        $transitionModel = new Transition();
        $allowed = array_filter(
            $transitionModel->all(),
            fn($t) => (int) $t['fromStatusId'] === 5 && (int) $t['toStatusId'] === 2
        );

        if (count($allowed) === 0) {
            http_response_code(422);
            echo 'Transicion no permitida';
            exit;
        }

        $this->entries->create([
            'ticketId' => (int) $id,
            'authorId' => $userId,
            'body' => 'Usuario rechazo la solucion',
            'fromStatusId' => 5,
            'toStatusId' => 2,
        ]);

        $this->tickets->updateStatus((int) $id, 2);

        header('Location: /user/tickets/' . (int) $id);
        exit;
    }

    public function create(): void
    {
        $this->requireRole([3]);
        $this->view('user/create');
    }

    public function addUserComment($id): void
    {
        $this->requireRole([3]);

        $ticket = $this->tickets->find((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket no encontrado';
            exit;
        }

        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);

        if ((int) $ticket['createdBy'] !== $userId) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }

        $data = $this->input();
        $body = trim($data['body'] ?? '');
        if ($body === '') {
            $_SESSION['flash_error'] = 'El comentario no puede ir vacío';
            header('Location: /user/tickets/' . (int) $id);
            exit;
        }

        $this->entries->create([
            'ticketId' => (int) $id,
            'authorId' => $userId,
            'body' => $body,
            'fromStatusId' => $ticket['statusId'] ?? null,
            'toStatusId' => null,
        ]);

        $_SESSION['flash_success'] = 'Comentario agregado';
        header('Location: /user/tickets/' . (int) $id);
        exit;
    }
}
