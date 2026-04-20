<?php
session_start();
require_once 'config/db.php';
require_once 'config/functions.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

// Handle form submission for adding users
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $name = $_POST['name'] ?? '';
        $role = $_POST['role'] ?? 'user';

        // Validation
        if (empty($username) || empty($password) || empty($name)) {
            $error = "All fields are required.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($password) < 4) {
            $error = "Password must be at least 4 characters.";
        } else {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username already exists.";
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
                try {
                    $stmt->execute([$username, $hashed_password, $name, $role]);
                    $success = "User '$username' created successfully!";
                } catch (PDOException $e) {
                    $error = "Error creating user: " . $e->getMessage();
                }
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['user_id'] ?? null;
        if ($id && $id != $_SESSION['user_id']) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $success = "User deleted successfully!";
        } elseif ($id == $_SESSION['user_id']) {
            $error = "You cannot delete your own account!";
        }
    }
}

// Fetch all users
$users = $pdo->query("SELECT id, username, name, role, created_at FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management | DoriSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-slate-200 min-h-screen p-6">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-black text-white mb-2">User Management</h1>
            <p class="text-slate-400">Manage system users and permissions</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Add User Form -->
            <div class="lg:col-span-1">
                <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-3xl sticky top-6">
                    <h2 class="text-xl font-black text-white mb-6">Add New User</h2>

                    <?php if($error): ?>
                        <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-bold">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <?php if($success): ?>
                        <div class="bg-green-500/10 border border-green-500/50 text-green-500 p-4 rounded-2xl mb-6 text-sm font-bold">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form action="admin_users.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add">
                        
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Full Name</label>
                            <input type="text" name="name" placeholder="John Doe" 
                                   class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Username</label>
                            <input type="text" name="username" placeholder="johndoe" 
                                   class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Password</label>
                            <input type="password" name="password" placeholder="••••••" 
                                   class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="••••••" 
                                   class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Role</label>
                            <select name="role" class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1">
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-2xl transition-all mt-6">
                            Create User
                        </button>
                    </form>

                    <a href="super_admin.php" class="block text-center text-slate-400 hover:text-white text-sm mt-4 transition-all">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Users List -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-3xl">
                    <h2 class="text-xl font-black text-white mb-6">System Users (<?= count($users) ?>)</h2>
                    
                    <div class="space-y-3">
                        <?php if(count($users) > 0): ?>
                            <?php foreach($users as $u): ?>
                            <div class="bg-slate-900/40 p-4 rounded-2xl border border-transparent hover:border-indigo-500/30 flex justify-between items-center transition-all group">
                                <div>
                                    <p class="font-bold text-white"><?= h($u['name']) ?></p>
                                    <p class="text-xs text-slate-400 mt-1">@<?= h($u['username']) ?></p>
                                    <div class="flex gap-2 mt-2">
                                        <span class="text-[10px] text-slate-500">Added: <?= date('M d, Y', strtotime($u['created_at'])) ?></span>
                                        <span class="text-[10px] px-2 py-1 rounded-full <?= $u['role'] === 'admin' ? 'bg-red-500/20 text-red-400' : ($u['role'] === 'manager' ? 'bg-blue-500/20 text-blue-400' : 'bg-slate-700/20 text-slate-400') ?> font-bold uppercase"><?= $u['role'] ?></span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <?php if($u['id'] != $_SESSION['user_id']): ?>
                                    <form action="admin_users.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="text-red-500 text-xs font-black opacity-0 group-hover:opacity-100 transition-all uppercase hover:text-red-400">Delete</button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-xs text-green-500 font-bold uppercase">(You)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-slate-400 text-center py-8">No users found</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
