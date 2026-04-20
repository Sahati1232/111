<?php
session_start();
require_once '../config/db.php';

// Check if user is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'manager';

    // Validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM staff WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO staff (username, password, role) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$username, $hashed_password, $role]);
                $success = "User '$username' created successfully as $role!";
            } catch (PDOException $e) {
                $error = "Error creating user: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User | DoriSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-slate-200 min-h-screen p-6">
    <div class="max-w-md mx-auto mt-10">
        <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-3xl">
            <h1 class="text-2xl font-black text-white mb-6">Add New User</h1>

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

            <form action="add_user.php" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase">Username</label>
                    <input type="text" name="username" placeholder="Enter username" 
                           class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase">Password</label>
                    <input type="password" name="password" placeholder="Enter password" 
                           class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase">Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm password" 
                           class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1" required>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase">Role</label>
                    <select name="role" class="w-full bg-slate-900/50 border border-slate-700 p-3 rounded-2xl text-white outline-none focus:ring-2 focus:ring-indigo-500 mt-1">
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-2xl transition-all mt-6">
                    Create User
                </button>
            </form>

            <a href="../admin_dashboard.php" class="block text-center text-slate-400 hover:text-white text-sm mt-4 transition-all">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
