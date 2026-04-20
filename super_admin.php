<?php 
require_once 'config/db.php';
require_once 'config/functions.php';

session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch Data
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();

// 📊 ANALYTICS LOGIC
$total_users = count($users);
$total_products = count($products);
$total_stock = 0;
$total_value = 0;

foreach($products as $p) {
    $total_stock += $p['stock'];
    $total_value += ($p['price'] * $p['stock']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>System Control | AdminHub</title>
    <?php include 'includes/scripts.php'; ?>
</head>
<body class="bg-[#0f172a] text-slate-200 flex min-h-screen">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 p-6 lg:p-10">
        <?php include 'includes/navbar.php'; ?>

        <section id="analytics" class="mb-12 animate__animated animate__fadeIn">
            <h2 class="text-sm font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Real-time Analytics</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-3xl">
                    <p class="text-slate-400 text-xs font-bold uppercase">Total Value</p>
                    <h3 class="text-2xl font-black text-emerald-500 mt-1">$<?= number_format($total_value, 2) ?></h3>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-3xl">
                    <p class="text-slate-400 text-xs font-bold uppercase">Stock Items</p>
                    <h3 class="text-2xl font-black text-white mt-1"><?= $total_stock ?></h3>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-3xl">
                    <p class="text-slate-400 text-xs font-bold uppercase">Products</p>
                    <h3 class="text-2xl font-black text-indigo-500 mt-1"><?= $total_products ?></h3>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 p-6 rounded-3xl">
                    <p class="text-slate-400 text-xs font-bold uppercase">Team Size</p>
                    <h3 class="text-2xl font-black text-white mt-1"><?= $total_users ?></h3>
                </div>
            </div>
        </section>

        <section id="inventory" class="grid lg:grid-cols-2 gap-8 animate__animated animate__fadeInUp">
            
            <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-[2rem]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-white">Product Inventory</h3>
                    <span class="bg-emerald-500/10 text-emerald-500 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Active</span>
                </div>

                <form action="actions/insert.php" method="POST" class="flex gap-2 mb-8 bg-slate-900/50 p-2 rounded-2xl">
                    <input type="hidden" name="type" value="product">
                    <input type="text" name="product_name" placeholder="Name" class="flex-1 bg-transparent p-3 text-sm outline-none" required>
                    <input type="number" name="stock" placeholder="Qty" class="w-20 bg-transparent p-3 text-sm outline-none" required>
                    <button class="bg-indigo-600 hover:bg-indigo-500 px-6 py-3 rounded-xl font-bold text-xs transition-all">ADD</button>
                </form>

                <div class="space-y-3">
                    <?php foreach($products as $p): ?>
                    <div class="bg-slate-900/40 p-4 rounded-2xl border border-transparent hover:border-slate-700 flex justify-between items-center transition-all group">
                        <div>
                            <p class="font-bold text-white"><?= h($p['product_name']) ?></p>
                            <p class="text-[10px] font-mono text-slate-500 uppercase">Price: $<?= h($p['price']) ?> | Stock: <?= h($p['stock']) ?></p>
                        </div>
                        <div class="flex gap-4 opacity-0 group-hover:opacity-100 transition-all">
                            <a href="actions/delete.php?id=<?= $p['id'] ?>&type=product" class="text-red-500 text-[10px] font-black uppercase">Delete</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-[2rem]">
                <h3 class="text-xl font-black text-white mb-6">Team Members</h3>
                <div class="space-y-3">
                    <?php foreach($users as $u): ?>
                    <div class="bg-slate-900/40 p-4 rounded-2xl border border-transparent hover:border-indigo-500/30 flex justify-between items-center transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs font-bold"><?= substr($u['name'], 0, 1) ?></div>
                            <p class="font-bold text-white text-sm"><?= h($u['name']) ?></p>
                        </div>
                        <a href="actions/delete.php?id=<?= $u['id'] ?>&type=user" class="text-red-500 text-[10px] font-black opacity-0 group-hover:opacity-100 transition-all uppercase">Remove</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </section>

        <?php include 'includes/footer.php'; ?>
    </main>
</body>
</html>