<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-6 space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">
            Ticket #<?= htmlspecialchars($ticket['id']) ?> - <?= htmlspecialchars($ticket['title']) ?>
        </h1>
        <span class="text-sm px-3 py-1 rounded-full bg-gray-200 text-gray-800">
            <?= htmlspecialchars($ticket['statusName']) ?>
        </span>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white shadow rounded-lg p-4 space-y-2 text-sm">
            <div><strong>Tipo:</strong> <?= htmlspecialchars($ticket['typeName']) ?></div>
            <div><strong>Estado:</strong> <?= htmlspecialchars($ticket['statusName']) ?></div>
            <div><strong>Creador:</strong> <?= htmlspecialchars($ticket['creatorName'] ?? '') ?> (<?= htmlspecialchars($ticket['creatorUsername'] ?? '') ?>)</div>
            <div><strong>Operador asignado:</strong>
                <?= $ticket['operatorName'] ? htmlspecialchars($ticket['operatorName']) . ' (' . htmlspecialchars($ticket['operatorUsername']) . ')' : 'Sin asignar' ?>
            </div>
            <div><strong>Creado:</strong> <?= htmlspecialchars($ticket['createdAt']) ?></div>
            <div><strong>Actualizado:</strong> <?= htmlspecialchars($ticket['updatedAt'] ?? '') ?></div>
            <div><strong>Resumen:</strong> <?= nl2br(htmlspecialchars($ticket['summary'])) ?></div>
        </div>
    </div>

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
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
