<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-6 space-y-4">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Panel de tickets (Superadmin)</h1>
            <p class="text-sm text-gray-600">Vista global con filtros por estado, tipo, operador y búsqueda.</p>
        </div>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-2">
            <div>
                <label class="text-xs text-gray-600" for="status">Estado</label>
                <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
                    <option value="">Todos</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= htmlspecialchars($status['id']) ?>" <?= ($statusFilter === (int) $status['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-600" for="type">Tipo</label>
                <select id="type" name="type" class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
                    <option value="">Todos</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= htmlspecialchars($type['id']) ?>" <?= ($typeFilter === (int) $type['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-600" for="operator">Operador</label>
                <select id="operator" name="operator" class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm">
                    <option value="">Todos</option>
                    <option value="null" <?= ($operatorFilter === 'null') ? 'selected' : '' ?>>Sin asignar</option>
                    <?php foreach ($operators as $op): ?>
                        <option value="<?= htmlspecialchars($op['id']) ?>" <?= ($operatorFilter === (int) $op['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($op['fullname']) ?> (<?= htmlspecialchars($op['username']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-600" for="q">Buscar</label>
                <input
                    id="q"
                    name="q"
                    type="text"
                    value="<?= htmlspecialchars($query) ?>"
                    placeholder="ID o título"
                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm"
                >
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded-md text-sm">Filtrar</button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Título</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Creador</th>
                    <th class="px-4 py-2 text-left">Operador</th>
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
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['creatorName'] ?? '') ?></td>
                            <td class="px-4 py-2">
                                <?= $ticket['operatorName'] ? htmlspecialchars($ticket['operatorName']) : 'Sin asignar' ?>
                            </td>
                            <td class="px-4 py-2"><?= htmlspecialchars($ticket['createdAt']) ?></td>
                            <td class="px-4 py-2">
                                <a class="text-blue-600 hover:underline" href="/admin/tickets/<?= htmlspecialchars($ticket['id']) ?>">Ver detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">No hay tickets</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
