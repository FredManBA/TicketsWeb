</div>

</main> 

<footer class="w-full bg-neutral-900 text-gray-300 border-t border-neutral-800 mt-8">
    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
        <!-- Logo-->
        <div class="flex items-center gap-2">
            <div class="h-8 w-8 flex items-center justify-center rounded-md bg-white text-neutral-900 text-[10px] font-bold leading-tight">
                TIK<br>WEB
            </div>
            <div class="flex flex-col leading-tight">
                <span class="font-semibold text-sm">TicketsWeb</span>
                <span class="text-[11px] text-gray-400">Centro de soporte</span>
            </div>
        </div>
        <div class="text-[11px] text-gray-500 text-center md:text-right">
            &copy; <?php echo date('Y'); ?> Los eso brad · TicketsWeb. Todos los derechos reservados.
        </div>
    </div>
</footer>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const accountToggle = document.getElementById('account-toggle');
    const accountMenu = document.getElementById('account-menu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    if (accountToggle && accountMenu) {
        accountToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            accountMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            if (!accountMenu.classList.contains('hidden')) {
                accountMenu.classList.add('hidden');
            }
        });
    }
</script>

</body>
</html>
