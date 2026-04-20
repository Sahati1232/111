<?php 
require_once 'config/db.php';
require_once 'config/functions.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// Restrict access to users only
if ($_SESSION['role'] !== 'user') {
    header("Location: admin_dashboard.php");
    exit;
}

// Get current user info
$stmt = $pdo->prepare("SELECT id, username, name, role, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$current_user = $stmt->fetch();

// Get user statistics
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | DoriSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include 'includes/scripts.php'; ?>
</head>
<body class="bg-[#0f172a] text-slate-200 flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="flex-1 p-6 lg:p-10">
        <?php include 'includes/navbar.php'; ?>
        
        <!-- Page Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-black text-white mb-2">Welcome, <?= h($current_user['name']) ?></h1>
            <p class="text-slate-400">Your personal dashboard</p>
        </div>

        <!-- Profile Card & Quick Stats Grid -->
        <div class="grid lg:grid-cols-3 gap-8 mb-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-indigo-600/20 to-indigo-900/20 border border-indigo-500/30 p-8 rounded-3xl">
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-indigo-500/30 border-2 border-indigo-500 flex items-center justify-center">
                            <span class="text-4xl font-black text-indigo-400"><?= substr($current_user['name'], 0, 1) ?></span>
                        </div>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-black text-white mb-1"><?= h($current_user['name']) ?></h2>
                        <p class="text-slate-400 text-sm">@<?= h($current_user['username']) ?></p>
                    </div>

                    <div class="space-y-4 border-y border-indigo-500/20 py-6">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Role</p>
                            <p class="text-white font-bold">👤 <?= ucfirst(h($current_user['role'])) ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Member Since</p>
                            <p class="text-white font-bold"><?= date('M d, Y', strtotime($current_user['created_at'])) ?></p>
                        </div>
                    </div>

                    <button onclick="document.getElementById('editModal').classList.remove('hidden')" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Stat Cards -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-2xl">
                        <p class="text-slate-400 text-sm font-bold uppercase mb-2">Total Products</p>
                        <p class="text-3xl font-black text-white"><?= $total_products ?></p>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-2xl">
                        <p class="text-slate-400 text-sm font-bold uppercase mb-2">System Users</p>
                        <p class="text-3xl font-black text-white"><?= $total_users ?></p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-2xl">
                    <h3 class="text-lg font-black text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="inventory.php" class="block bg-slate-900/40 hover:bg-slate-900/60 p-4 rounded-xl transition-all border border-slate-700/30 hover:border-indigo-500/50">
                            <p class="text-white font-bold">📦 Browse Products</p>
                            <p class="text-xs text-slate-400">View and order available products</p>
                        </a>
                        <a href="admin_dashboard.php" class="block bg-slate-900/40 hover:bg-slate-900/60 p-4 rounded-xl transition-all border border-slate-700/30 hover:border-indigo-500/50">
                            <p class="text-white font-bold">📊 Admin Dashboard</p>
                            <p class="text-xs text-slate-400">View system overview and analytics</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Account Details -->
            <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-3xl">
                <h3 class="text-xl font-black text-white mb-6">Account Information</h3>
                
                <div class="space-y-5">
                    <div class="pb-4 border-b border-slate-700/50">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-2">Full Name</p>
                        <p class="text-white"><?= h($current_user['name']) ?></p>
                    </div>
                    <div class="pb-4 border-b border-slate-700/50">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-2">Username</p>
                        <p class="text-white"><?= h($current_user['username']) ?></p>
                    </div>
                    <div class="pb-4 border-b border-slate-700/50">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-2">Account ID</p>
                        <p class="text-white">#<?= $current_user['id'] ?></p>
                    </div>
                    <div class="pb-4">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-2">Member Status</p>
                        <div class="inline-block bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-bold">
                            ✓ Active
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-3xl">
                <h3 class="text-xl font-black text-white mb-6">Security</h3>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/40 p-4 rounded-xl border border-slate-700/30">
                        <p class="font-bold text-white mb-2">🔐 Password</p>
                        <p class="text-slate-400 text-sm mb-3">Last changed: Never</p>
                        <button onclick="document.getElementById('changePasswordModal').classList.remove('hidden')" class="text-indigo-400 hover:text-indigo-300 text-sm font-bold transition-all">
                            Change Password →
                        </button>
                    </div>

                    <div class="bg-slate-900/40 p-4 rounded-xl border border-slate-700/30">
                        <p class="font-bold text-white mb-2">🛡️ Sessions</p>
                        <p class="text-slate-400 text-sm mb-3">1 active session</p>
                        <button class="text-indigo-400 hover:text-indigo-300 text-sm font-bold transition-all">
                            Manage Sessions →
                        </button>
                    </div>

                    <div class="bg-red-500/10 p-4 rounded-xl border border-red-500/30">
                        <p class="font-bold text-red-400 mb-2">⚠️ Logout</p>
                        <a href="actions/logout.php" class="text-red-400 hover:text-red-300 text-sm font-bold transition-all">
                            Sign out from all devices →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-slate-800 border border-slate-700/50 p-8 rounded-3xl max-w-md w-full">
            <h3 class="text-2xl font-black text-white mb-6">Edit Profile</h3>
            
            <p class="text-slate-400 mb-6">Profile editing coming soon. Contact an administrator to update your information.</p>
            
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all">
                Close
            </button>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-slate-800 border border-slate-700/50 p-8 rounded-3xl max-w-md w-full">
            <h3 class="text-2xl font-black text-white mb-6">Change Password</h3>
            
            <p class="text-slate-400 mb-6">Password change coming soon. Please contact an administrator for assistance.</p>
            
            <button onclick="document.getElementById('changePasswordModal').classList.add('hidden')" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all">
                Close
            </button>
        </div>
    </div>
</body>
</html>
