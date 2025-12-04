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
    <header>
        <nav class="bg-gray-900 border-b border-gray-700">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-white font-semibold text-lg">
                            TicketsWeb
                        </a>
                    </div>

                    <!-- Menu -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                            Inicio
                        </a>

                        <!-- Si esta iniciado -->
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="/vehicles"
                                class="text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                                Tickets
                            </a>
                            <a href="/brands"
                                class="text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                                Crear usuario
                            </a>
                            <a href="/owners"
                                class="text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                                Configuración
                            </a>

                            <!-- Perfil -->
                            <div class="relative group">
                                <button
                                    class="flex items-center text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                                    <span class="mr-1">
                                        <?= $_SESSION['user']['username'] ?>
                                    </span>
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-1 text-sm hidden group-hover:block z-20">
                                    <a href="/profile"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Perfil
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <a href="/logout"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Cerrar sesión
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/login"
                                class="text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                                Iniciar sesión
                            </a>
                            <a href="/register"
                                class="text-gray-300 hover:text-white text-sm px-3 py-2 rounded-md">
                                Registrarse
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="max-w-6xl mx-auto mt-4 px-4">
        <!-- Aquí va tu contenido -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
