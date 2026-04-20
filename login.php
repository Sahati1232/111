<?php
session_start();
require_once 'config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (!empty($user) && !empty($pass)) {
        // Query database for user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $user_data = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user_data && password_verify($pass, $user_data['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['user_id'] = $user_data['id'];
            
            // Redirect to dashboard
            header("Location: super_admin.php"); 
            exit;
        } else {
            $error = "Incorrect username or password.";
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dori | System Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-[#0f172a] h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md p-8 bg-slate-800/50 backdrop-blur-xl border border-slate-700 rounded-[3rem] shadow-2xl animate__animated animate__fadeInUp">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-white italic tracking-tighter">DORI<span class="text-indigo-500">SYS</span></h1>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">Private Access Only</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-2xl mb-6 text-xs text-center font-bold animate__animated animate__shakeX">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase ml-4 tracking-widest">Identify User</label>
                <input type="text" name="username" placeholder="Username" 
                       class="w-full bg-slate-900/50 border border-slate-700 p-5 rounded-3xl text-white outline-none focus:ring-2 focus:ring-indigo-500 transition-all mt-1" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase ml-4 tracking-widest">Secret Key</label>
                <input type="password" name="password" placeholder="Password" 
                       class="w-full bg-slate-900/50 border border-slate-700 p-5 rounded-3xl text-white outline-none focus:ring-2 focus:ring-indigo-500 transition-all mt-1" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-3xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                AUTHORIZE ACCESS
            </button>
        </form>

        <p class="text-center text-slate-600 text-[10px] mt-8 font-bold uppercase tracking-widest">Admin Control v2.4</p>
    </div>

</body>
</html>