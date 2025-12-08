<?php require __DIR__ . '/../layouts/header.php'; ?>

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
                            Registrarse
                        </h1>
                        <p class="text-[11px] text-gray-200">
                            Crea un nuevo usuario para el sistema de tickets.
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

                <form action="/register" method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Usuario
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Escribe el nombre de usuario"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Contraseña
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                            Confirmar contraseña
                        </label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Vuelve a escribir la contraseña"
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
                            <option value="" disabled selected>Seleccione un rol</option>
                            <option value="1">Superadministrador</option>
                            <option value="2">Operador</option>
                            <option value="3">Usuario</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-2 inline-flex justify-center items-center px-4 py-2.5
                               bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                               rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                        Registrar usuario
                    </button>
                </form>
            </div>

            <div class="px-6 pb-4 text-[11px] text-gray-500">
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>