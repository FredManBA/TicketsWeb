<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-10 flex items-center justify-center">
    <div class="w-full max-w-lg">
        <div class="bg-white/90 rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-slate-800 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-white text-neutral-900 text-xs font-bold leading-tight shadow">
                        TIK<br>WEB
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-white text-lg font-semibold">
                            Crear ticket
                        </h1>
                        <p class="text-[11px] text-blue-100">
                            Reporta un incidente o petición.
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded-md">
                        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <form action="/user/tickets" method="POST" class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Título
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            required
                            maxlength="200"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Describe brevemente tu solicitud"
                        >
                    </div>

                    <div>
                        <label for="typeId" class="block text-sm font-medium text-gray-700">
                            Tipo
                        </label>
                        <select
                            id="typeId"
                            name="typeId"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="" disabled selected>Seleccione</option>
                            <option value="1">Petición</option>
                            <option value="2">Incidente</option>
                        </select>
                    </div>

                    <div>
                        <label for="summary" class="block text-sm font-medium text-gray-700">
                            Descripción
                        </label>
                        <textarea
                            id="summary"
                            name="summary"
                            rows="4"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Explica el detalle del problema o solicitud"
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-2 inline-flex justify-center items-center px-4 py-2.5
                               bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                               rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                        Crear ticket
                    </button>
                </form>
            </div>

            <div class="px-6 pb-4 text-[11px] text-gray-500">
                El ticket se creará con estado "No Asignado".
            </div>
        </div>
    </div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
