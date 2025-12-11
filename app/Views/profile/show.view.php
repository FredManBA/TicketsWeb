<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-8 max-w-xl mx-auto space-y-4 pb-24">
    <div class="bg-white shadow rounded-lg border border-gray-200">
        <div class="bg-slate-800 px-6 py-4 rounded-t-lg">
            <h1 class="text-white text-lg font-semibold">Mi perfil</h1>
            <p class="text-[11px] text-blue-100">Información de tu cuenta.</p>
        </div>

        <div class="px-6 py-6 space-y-3 text-sm text-gray-800">
            <?php if (!empty($flashError)): ?>
                <div class="text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded-md">
                    <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="text-sm text-green-700 bg-green-100 border border-green-200 px-3 py-2 rounded-md">
                    <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <div>
                <span class="font-semibold text-gray-600">Nombre completo:</span>
                <div class="mt-1 text-gray-900"><?= htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>

            <div>
                <span class="font-semibold text-gray-600">Usuario:</span>
                <div class="mt-1 text-gray-900"><?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <span class="font-semibold text-gray-600">Rol:</span>
                    <div
                        class="mt-1 inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
                        <?php
                        $roleId = (int) ($user['roleId'] ?? 0);
                        echo $roleId === 1 ? 'Superadministrador' : ($roleId === 2 ? 'Operador' : 'Usuario');
                        ?>
                    </div>
                </div>
                <div>
                    <span class="font-semibold text-gray-600">Estado:</span>
                    <div
                        class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                        <?= ((int) ($user['isActive'] ?? 0) === 1) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= ((int) ($user['isActive'] ?? 0) === 1) ? 'Activo' : 'Inactivo' ?>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-600">
                <div>Creado: <span
                        class="font-semibold text-gray-800"><?= htmlspecialchars($user['createdAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div>Actualizado: <span
                        class="font-semibold text-gray-800"><?= htmlspecialchars($user['updatedAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
