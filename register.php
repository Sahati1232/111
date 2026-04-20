<?php
session_start();
require_once 'config/db.php';
require_once 'config/functions.php';

// Redirect if already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: user.php");
    exit;
}

// Initialize variables
$error = "";
$success = "";
$name = "";
$username = "";
$password = "";
$confirm_password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($name) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken. Try another.";
        } else {
            // Insert new user as "user" role only
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute([$username, $hashed_password, $name, 'user']);
                $success = "Account created successfully! You can now login.";
                // Clear form
                $name = $username = $password = $confirm_password = '';
            } catch (PDOException $e) {
                $error = "Error creating account: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | DoriSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-[#0f172a] h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md p-8 bg-slate-800/50 backdrop-blur-xl border border-slate-700 rounded-[3rem] shadow-2xl animate__animated animate__fadeInUp">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-white italic tracking-tighter">DORI<span class="text-indigo-500">SYS</span></h1>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">Create Account</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-2xl mb-6 text-xs text-center font-bold animate__animated animate__shakeX">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-500/10 border border-green-500/50 text-green-500 p-4 rounded-2xl mb-6 text-xs text-center font-bold animate__animated animate__fadeIn">
                <?= $success ?>
                <p class="mt-3"><a href="login.php" style="text-decoration:underline;">Go to Login</a></p>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="space-y-6">
            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase ml-4 tracking-widest">Full Name</label>
                <input type="text" name="name" placeholder="John Doe" value="<?= h($name) ?>"
                       class="w-full bg-slate-900/50 border border-slate-700 p-5 rounded-3xl text-white outline-none focus:ring-2 focus:ring-indigo-500 transition-all mt-1" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase ml-4 tracking-widest">Username</label>
                <input type="text" name="username" placeholder="johndoe" value="<?= h($username) ?>"
                       class="w-full bg-slate-900/50 border border-slate-700 p-5 rounded-3xl text-white outline-none focus:ring-2 focus:ring-indigo-500 transition-all mt-1" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase ml-4 tracking-widest">Password</label>
                <input type="password" name="password" placeholder="••••••••"
                       class="w-full bg-slate-900/50 border border-slate-700 p-5 rounded-3xl text-white outline-none focus:ring-2 focus:ring-indigo-500 transition-all mt-1" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase ml-4 tracking-widest">Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="••••••••"
                       class="w-full bg-slate-900/50 border border-slate-700 p-5 rounded-3xl text-white outline-none focus:ring-2 focus:ring-indigo-500 transition-all mt-1" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-3xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                CREATE ACCOUNT
            </button>
        </form>

        <p class="text-center text-slate-400 text-sm mt-6">
            Already have an account? <a href="login.php" class="text-indigo-500 hover:text-indigo-400 font-bold">Login here</a>
        </p>

        <p class="text-center text-slate-600 text-[10px] mt-8 font-bold uppercase tracking-widest">Admin Control v2.4</p>
    </div>

</body>
</html>
