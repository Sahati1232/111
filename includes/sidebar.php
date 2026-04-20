<aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col p-6 gap-8 h-screen sticky top-0">
    <a href="super_admin.php" class="px-2 hover:opacity-80 transition-all">
        <h1 class="text-2xl font-black text-white italic tracking-tighter">DORI<span class="text-indigo-500">SYS</span></h1>
    </a>
    <nav class="flex-1 space-y-2 text-sm font-bold">
        <a href="analytics.php" class="flex items-center gap-3 p-4 <?= basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800' ?> rounded-2xl transition-all">
            📊 Analytics
        </a>
        <a href="inventory.php" class="flex items-center gap-3 p-4 <?= basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800' ?> rounded-2xl transition-all">
            📦 Inventory
        </a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin_users.php" class="flex items-center gap-3 p-4 <?= basename($_SERVER['PHP_SELF']) == 'admin_users.php' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800' ?> rounded-2xl transition-all">
            👥 Users
        </a>
        <?php endif; ?>
    </nav>
    <a href="actions/logout.php" class="p-4 bg-red-500/10 text-red-500 rounded-2xl text-center text-xs font-black hover:bg-red-500 hover:text-white transition-all">LOGOUT</a>
</aside>