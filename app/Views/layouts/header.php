<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets web</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body class="bg-gray-100">
<header class="bg-neutral-900 text-white border-b border-neutral-800">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo  -->
            <a href="/" class="flex items-center gap-2">
                <div class="h-9 w-9 flex items-center justify-center rounded-md bg-white text-neutral-900 text-xs font-bold leading-tight">
                    TIK<br>WEB
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-semibold text-sm md:text-base">TicketsWeb</span>
                    <span class="text-[11px] text-gray-300">Centro de soporte</span>
                </div>
            </a>

            <!-- Nav de la pagina principal -->
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php $roleId = $_SESSION['user']['roleId'] ?? null; ?>
                    <?php if ($roleId === 3): ?>
                        <a href="/user/dashboard"
                           class="text-gray-300 hover:text-white hover:underline">
                            Mi panel
                        </a>
                    <?php elseif ($roleId === 2): ?>
                        <a href="/operator/dashboard"
                           class="text-gray-300 hover:text-white hover:underline">
                            Panel operador
                        </a>
                    <?php elseif ($roleId === 1): ?>
                        <a href="/admin/dashboard"
                           class="text-gray-300 hover:text-white hover:underline">
                            Panel admin
                        </a>
                        <a href="/users"
                           class="text-gray-300 hover:text-white hover:underline">
                            Usuarios
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            <!-- perfil -->
            <div class="hidden md:flex items-center gap-4 text-sm">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Nombre de usuario + dropdown -->
            <div class="relative">
                        <button id="account-toggle"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md bg-neutral-800 hover:bg-neutral-700">
                            <span class="text-xs text-gray-300">Mi cuenta</span>
                            <span class="font-semibold text-sm">
                                <?= htmlspecialchars($_SESSION['user']['fullname'] ?? $_SESSION['user']['username']) ?>
                            </span>
                            <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                       <div id="account-menu"
                            class="absolute right-0 mt-2 w-44 bg-neutral-900 border border-neutral-700 rounded-md shadow-lg py-1 text-sm hidden z-20">
                            <a href="/profile"
                               class="block px-4 py-2 text-gray-200 hover:bg-neutral-800">
                                Perfil
                            </a>
                            <div class="border-t border-neutral-700 my-1"></div>
                            <a href="/logout"
                               class="block px-4 py-2 text-red-400 hover:bg-neutral-800">
                                Cerrar sesion
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login"
                       class="text-gray-200 hover:text-white" >
                        Iniciar sesion
                    </a>
                <?php endif; ?>
            </div>

            <button id="menu-toggle"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-900 focus:ring-blue-400">
                <span class="sr-only">Abrir menu</span>
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu interactivo -->
    <div id="mobile-menu" class="md:hidden bg-neutral-900 border-t border-neutral-800 hidden">
        <div class="px-4 py-3 space-y-2 text-sm">
            <a href="/" class="block text-gray-100 hover:text-white">
                Inicio
            </a>

            <?php if (isset($_SESSION['user'])): ?>
                <?php $roleId = $_SESSION['user']['roleId'] ?? null; ?>
                <?php if ($roleId === 3): ?>
                    <a href="/user/dashboard" class="block text-gray-200 hover:text-white">
                        Mi panel
                    </a>
                <?php elseif ($roleId === 2): ?>
                    <a href="/operator/dashboard" class="block text-gray-200 hover:text-white">
                        Panel operador
                    </a>
                <?php elseif ($roleId === 1): ?>
                    <a href="/admin/dashboard" class="block text-gray-200 hover:text-white">
                        Panel admin
                    </a>
                    <a href="/users" class="block text-gray-200 hover:text-white">
                        Usuarios
                    </a>
                <?php endif; ?>
                <a href="/profile" class="block text-gray-200 hover:text-white">
                    Perfil
                </a>
                <a href="/logout" class="block text-red-400 hover:text-red-300">
                    Cerrar sesion
                </a>
            <?php else: ?>
                <a href="/login" class="block text-gray-200 hover:text-white">
                    Iniciar sesion
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Contenedor principal -->
<main class="flex flex-col min-h-screen">
    <div class="max-w-6xl mx-auto mt-4 px-4 flex-1 w-full">
