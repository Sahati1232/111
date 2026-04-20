<nav class="bg-slate-800/40 border border-slate-700/50 backdrop-blur-md p-4 rounded-3xl flex justify-between items-center mb-8">
    <div class="flex items-center gap-3">
        <div class="h-3 w-3 bg-green-500 rounded-full animate-pulse"></div>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">System Unlocked</span>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-slate-300">Active User: <b class="text-white"><?= $_SESSION['username'] ?></b> <span class="text-xs text-slate-500">(<?= ucfirst($_SESSION['role']) ?>)</span></span>
        <div class="relative">
            <button onclick="toggleDropdown()" class="h-10 w-10 bg-indigo-600 hover:bg-indigo-500 rounded-xl flex items-center justify-center font-bold text-white shadow-lg transition-all cursor-pointer">A</button>
            
            <div id="dropdown" class="hidden absolute right-0 mt-3 w-48 bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-700">
                    <p class="text-xs text-slate-400 uppercase font-bold">User Account</p>
                    <p class="text-sm text-white font-bold mt-1"><?= $_SESSION['username'] ?></p>
                </div>
                
                <a href="<?= $_SESSION['role'] === 'user' ? 'user.php' : 'admin_dashboard.php' ?>" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-sm">
                    <span>📊</span> Dashboard
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-sm">
                    <span>👤</span> Profile
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-sm">
                    <span>⚙️</span> Settings
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-sm">
                    <span>🔔</span> Notifications
                </a>
                
                <div class="border-t border-slate-700"></div>
                
                <a href="actions/logout.php" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 transition-all text-sm font-bold">
                    <span>🚪</span> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const button = event.target.closest('button');
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>