<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit; }

// Restrict analytics to admins only
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager') {
    header("Location: user.php");
    exit;
}

require_once 'config/db.php';

$products = $pdo->query("SELECT product_name, stock, price FROM products")->fetchAll();
$p_names = []; $p_stocks = []; $total_value = 0;

foreach($products as $p) {
    $p_names[] = $p['product_name'];
    $p_stocks[] = $p['stock'];
    $total_value += ($p['price'] * $p['stock']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Analytics | DoriSys</title>
    <?php include 'includes/scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0f172a] text-white flex">
    <?php include 'includes/sidebar.php'; ?>
    <main class="flex-1 p-10">
        <h2 class="text-3xl font-black mb-8">Data Visuals</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Net Inventory Value</p>
                <h3 class="text-4xl font-black text-emerald-500 mt-2">$<?= number_format($total_value, 2) ?></h3>
            </div>

            <div class="lg:col-span-2 bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700">
                <canvas id="stockChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($p_names) ?>,
                datasets: [{
                    label: 'Current Stock Level',
                    data: <?= json_encode($p_stocks) ?>,
                    backgroundColor: '#6366f1',
                    borderRadius: 10
                }]
            },
            options: { 
                scales: { y: { beginAtZero: true, grid: { color: '#1e293b' } } },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>