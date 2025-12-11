<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="mt-8">
    <div class="max-w-6xl mx-auto bg-gray-100 rounded-2xl px-6 py-10 shadow-sm">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Bienvenido al Soporte Técnico de Los Patitos S.A
        </h1>
        <p class="text-lg text-gray-700 max-w-2xl">
            Ponte en contacto con nuestros técnicos y le solucionaremos su problema.
        </p>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="/tickets"
               class="inline-block mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm md:text-base rounded-md shadow-md transition">
                Ver Tickets
            </a>
        <?php else: ?>
            <a href="/login"
               class="inline-block mt-6 px-6 py-3 bg-slate-700 hover:bg-slate-800 text-white font-semibold text-sm md:text-base rounded-md shadow-md transition">
                Iniciar sesion
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
