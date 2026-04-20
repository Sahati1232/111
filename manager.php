<?php 
session_start();
require_once 'config/db.php';
require_once 'config/functions.php';

if(!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manager' && $_SESSION['role'] !== 'admin')) { 
    header("Location: login.php"); 
    exit; 
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard | AdminHub</title>
    <?php include 'includes/scripts.php'; ?>
</head>
<body class="bg-[#0f172a] text-slate-200 flex min-h-screen">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 p-8">
        <?php include 'includes/navbar.php'; ?>

        <div class="animate__animated animate__fadeInUp">
             </div>

        <?php include 'includes/footer.php'; ?>
    </main>
</body>
</html>