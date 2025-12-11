<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Panel del Operador</h1>
            <p class="text-sm text-gray-600">Cola global y tus tickets asignados.</p>
        </div>
        <form method="GET" class="flex items-center gap-2 text-sm">
            <label for="status" class="text-gray-600">Filtrar mis tickets por estado:</label>
            <select id="status" name="status" class="border border-gray-300 rounded-md px-2 py-1">
                <option value="">Todos</option>
                <option value="2" <?= $statusFilter === 2 ? 'selected' : '' ?>>Asignado</option>
                <option value="3" <?= $statusFilter === 3 ? 'selected' : '' ?>>En Proceso</option>
                <option value="4" <?= $statusFilter === 4 ? 'selected' : '' ?>>En Espera</option>
                <option value="5" <?= $statusFilter === 5 ? 'selected' : '' ?>>Solucionado</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md">Aplicar</button>
        </form>
    </div>

    <!-- Cola global -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="border-b px-4 py-2 font-semibold text-gray-800">Cola global (No Asignados)</div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Título</th>
                    <th class="px-4 py-2 text-left">Creador</th>
                    <th class="px-4 py-2 text-left">Fecha</th>
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($queueTickets)): ?>
                    <?php foreach ($queueTickets as $ticket): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['title']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['creatorUsername']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['createdAt']) ?></td>
                            <td class="px-4 py-2">
                                <form method="POST" action="/operator/tickets/<?= htmlspecialchars($ticket['id']) ?>/assign">
                                    <button type="submit" class="bg-slate-700 text-white px-3 py-1 rounded-md text-xs">Autoasignar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No hay tickets en cola</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mis tickets asignados -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="border-b px-4 py-2 font-semibold text-gray-800">Mis tickets asignados</div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Título</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Fecha</th>
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($myTickets)): ?>
                    <?php foreach ($myTickets as $ticket): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['title']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['statusName']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['typeName']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['createdAt']) ?></td>
                            <td class="px-4 py-2">
                                <a class="text-blue-600 hover:underline" href="/operator/tickets/<?= htmlspecialchars($ticket['id']) ?>">Ver detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No tienes tickets asignados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
