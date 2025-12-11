<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php
$currentRoleId = $user['roleId'] ?? '';
?>

<div class="mt-10 flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white/90 rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- inicio de la pagina -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-700 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-white text-neutral-900 text-xs font-bold leading-tight shadow">
                        TIK<br>WEB
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-white text-lg font-semibold">
                            Editar usuario
                        </h1>
                        <p class="text-[11px] text-gray-200">
                            Actualiza la información del usuario del sistema de tickets.
                        </p>
                    </div>
                </div>
            </div>

            <!-- cuerpo de la pagina -->
            <div class="px-6 py-6">
                <?php if (isset($error)): ?>
                    <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded-md">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 px-3 py-2 rounded-md">
                        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded-md">
                        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 px-3 py-2 rounded-md">
                        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <form action="/users/<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>/update" method="POST" class="space-y-4">
                    <!-- oculta el id -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">

                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-700">
                            Nombre completo
                        </label>
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            required
                            value="<?= htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nombre y apellidos"
                        >
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Usuario
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                            value="<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Escribe el nombre de usuario"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Nueva contraseña
                            </label>
                            <span class="text-[11px] text-gray-500">
                                (Deja en blanco si no deseas cambiarla)
                            </span>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                            Confirmar nueva contraseña
                        </label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Vuelve a escribir la nueva contraseña"
                        >
                    </div>

                    <!-- combobox -->
                    <div>
                        <label for="roleId" class="block text-sm font-medium text-gray-700">
                            Rol del sistema
                        </label>
                        <select
                            id="roleId"
                            name="roleId"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="" disabled <?= $currentRoleId === '' ? 'selected' : '' ?>>Seleccione un rol</option>
                            <option value="1" <?= (int)$currentRoleId === 1 ? 'selected' : '' ?>>Superadministrador</option>
                            <option value="2" <?= (int)$currentRoleId === 2 ? 'selected' : '' ?>>Operador</option>
                            <option value="3" <?= (int)$currentRoleId === 3 ? 'selected' : '' ?>>Usuario</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button
                            type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2.5
                                   bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                                   rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            Guardar cambios
                        </button>

                        <a
                            href="/users"
                            class="inline-flex justify-center items-center px-4 py-2.5
                                   border border-gray-300 text-gray-700 text-sm font-semibold
                                   rounded-md bg-white hover:bg-gray-50 shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <div class="px-6 pb-4 text-[11px] text-gray-500">
                Última actualización del usuario:
                <span class="font-medium">
                    <?= htmlspecialchars($user['updated_at'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
