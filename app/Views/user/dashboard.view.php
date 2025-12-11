<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-6 space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Mis tickets</h1>
        <div class="flex items-center gap-3">
            <a href="/user/tickets/create" class="inline-flex items-center rounded-md border border-blue-600 px-3 py-1.5 text-sm font-semibold
                      text-blue-50 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2
                      focus:ring-blue-500 focus:ring-offset-1">
                Crear ticket
            </a>
            <form method="GET" class="flex items-center gap-2">
                <label for="status" class="text-sm text-gray-600">Estado:</label>
                <select id="status" name="status" class="border border-gray-300 rounded-md px-2 py-1 text-sm">
                    <option value="">Todos</option>
                    <option value="1" <?= $statusFilter === 1 ? 'selected' : '' ?>>No asignado</option>
                    <option value="2" <?= $statusFilter === 2 ? 'selected' : '' ?>>Asignado</option>
                    <option value="3" <?= $statusFilter === 3 ? 'selected' : '' ?>>En proceso</option>
                    <option value="4" <?= $statusFilter === 4 ? 'selected' : '' ?>>En espera</option>
                    <option value="5" <?= $statusFilter === 5 ? 'selected' : '' ?>>Solucionado</option>
                    <option value="6" <?= $statusFilter === 6 ? 'selected' : '' ?>>Cerrado</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Título</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Creado</th>
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tickets)): ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['title']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['typeName']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['statusName']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['createdAt']) ?></td>
                            <td class="px-4 py-2">
                                <a class="text-blue-600 hover:underline"
                                    href="/user/tickets/<?= htmlspecialchars($ticket['id']) ?>">Ver detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No hay tickets</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>