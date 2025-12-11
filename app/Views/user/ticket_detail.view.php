<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-6 space-y-4">
    <?php if (!empty($flashError)): ?>
        <div class="mb-2 text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded-md">
            <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($flashSuccess)): ?>
        <div class="mb-2 text-sm text-green-700 bg-green-100 border border-green-200 px-3 py-2 rounded-md">
            <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">
            Ticket #<?= htmlspecialchars($ticket['id']) ?> - <?= htmlspecialchars($ticket['title']) ?>
        </h1>
        <span class="text-sm px-3 py-1 rounded-full bg-gray-200 text-gray-800">
            <?= htmlspecialchars($ticket['statusName']) ?>
        </span>
    </div>

    <div class="bg-white shadow rounded-lg p-4 space-y-2 text-sm">
        <div><strong>Tipo:</strong> <?= htmlspecialchars($ticket['typeName']) ?></div>
        <div><strong>Estado:</strong> <?= htmlspecialchars($ticket['statusName']) ?></div>
        <div><strong>Creado:</strong> <?= htmlspecialchars($ticket['createdAt']) ?></div>
        <div><strong>Resumen:</strong> <?= nl2br(htmlspecialchars($ticket['summary'])) ?></div>
    </div>

    <?php if ((int)$ticket['statusId'] === 5): ?>
        <div class="flex gap-3">
            <form method="POST" action="/user/tickets/<?= htmlspecialchars($ticket['id']) ?>/accept">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm">Aceptar solución</button>
            </form>
            <form method="POST" action="/user/tickets/<?= htmlspecialchars($ticket['id']) ?>/reject">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm">Rechazar solución</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-lg">
        <div class="border-b px-4 py-2 font-semibold text-gray-800">Historial</div>
        <div class="divide-y">
            <?php if (!empty($entries)): ?>
                <?php foreach ($entries as $entry): ?>
                    <div class="px-4 py-3 text-sm">
                        <div class="flex justify-between text-gray-700">
                            <span><strong><?= htmlspecialchars($entry['authorUsername']) ?></strong></span>
                            <span class="text-gray-500"><?= htmlspecialchars($entry['createdAt']) ?></span>
                        </div>
                        <?php if ($entry['fromStatusName'] || $entry['toStatusName']): ?>
                            <div class="text-gray-600 text-xs mt-1">
                                Estado: <?= htmlspecialchars($entry['fromStatusName'] ?? '-') ?> →
                                <?= htmlspecialchars($entry['toStatusName'] ?? '-') ?>
                            </div>
                        <?php endif; ?>
                        <div class="mt-2 text-gray-800"><?= nl2br(htmlspecialchars($entry['body'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="px-4 py-3 text-gray-500 text-sm">No hay historial</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Agregar comentario</h2>
        <form method="POST" action="/user/tickets/<?= htmlspecialchars($ticket['id']) ?>/comment" class="space-y-3">
            <div>
                <label class="block text-sm text-gray-700" for="body">Comentario</label>
                <textarea id="body" name="body" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm">Enviar</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
