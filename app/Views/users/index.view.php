<?php require __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-10">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Listado de usuarios</h1>
            <p class="text-sm text-gray-500">
                Gestión de cuentas y roles del sistema de tickets.
            </p>
        </div>

        <div class="flex items-center">
            <a href="/users/create"
               class="inline-flex items-center rounded-md border border-blue-600 px-3 py-2 text-sm font-semibold
                      text-blue-50 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2
                      focus:ring-blue-500 focus:ring-offset-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                </svg>
                Agregar nuevo
            </a>
        </div>
    </div>

    <!-- tabla de datos -->
    <div class="overflow-x-auto bg-white/80 rounded-xl shadow border border-gray-200">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-900">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">ID</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">Usuario</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">Nombre completo</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">Rol</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">Estado</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">Creado</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-100">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                <?php foreach ($users as $user): ?>
                    <?php
                        $roleLabel = 'Sin definir';
                        switch ((int)$user->roleId) {
                            case 1:
                                $roleLabel = 'Superadministrador';
                                break;
                            case 2:
                                $roleLabel = 'Operador';
                                break;
                            case 3:
                                $roleLabel = 'Usuario';
                                break;
                        }
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700">
                            <?= (int)$user->id ?>
                        </td>

                        <td class="px-4 py-3 font-mono text-gray-900">
                            <?= htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td class="px-4 py-3 text-gray-700">
                            <?= htmlspecialchars($user->fullname ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                <?php if ((int)$user->roleId === 1): ?>
                                    bg-purple-100 text-purple-800
                                <?php elseif ((int)$user->roleId === 2): ?>
                                    bg-blue-100 text-blue-800
                                <?php else: ?>
                                    bg-gray-100 text-gray-800
                                <?php endif; ?>">
                                <?= $roleLabel ?>
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <?php if ((int)$user->isActive === 1): ?>
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    Activo
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                    Inactivo
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-3 text-gray-500 text-xs">
                            <?= htmlspecialchars($user->createdAt ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="/users/edit/<?= (int)$user->id ?>"
                                   class="inline-flex items-center rounded-md border border-amber-500 px-2.5 py-1
                                          text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100">
                                    Editar
                                </a>
                                <a href="/users/delete/<?= (int)$user->id ?>"
                                   class="inline-flex items-center rounded-md border border-red-600 px-2.5 py-1
                                          text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100"
                                   onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                            No hay usuarios registrados todavía.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

