<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit; }
require_once 'config/db.php';
require_once 'config/functions.php';

// Only admins can manage products
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory | DoriSys</title>
    <?php include 'includes/scripts.php'; ?>
</head>
<body class="bg-[#0f172a] text-white flex">
    <?php include 'includes/sidebar.php'; ?>
    <main class="flex-1 p-10">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-black">Stock Management</h2>
            <?php if($is_admin): ?>
            <button onclick="document.getElementById('add-form').scrollIntoView()" class="bg-indigo-600 px-6 py-3 rounded-2xl font-bold text-sm">Add New Item</button>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <?php foreach($products as $p): ?>
            <div class="bg-slate-800/40 border border-slate-700 p-6 rounded-3xl flex justify-between items-center <?php echo $is_admin ? 'group' : ''; ?>">
                <div>
                    <h4 class="text-lg font-bold"><?= h($p['product_name']) ?></h4>
                    <p class="text-slate-500 text-sm font-mono">Price: $<?= h($p['price']) ?> | Available: <?= h($p['stock']) ?></p>
                </div>
                <?php if($is_admin): ?>
                <div class="flex gap-4 opacity-0 group-hover:opacity-100 transition-all">
                    <a href="actions/delete.php?id=<?= $p['id'] ?>&type=product" class="text-red-500 font-black text-xs uppercase tracking-tighter">Remove Item</a>
                </div>
                <?php else: ?>
                <button class="bg-indigo-600 hover:bg-indigo-500 px-6 py-2 rounded-xl font-bold text-sm transition-all">Order</button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if($is_admin): ?>
        <form id="add-form" action="actions/insert.php" method="POST" class="mt-12 bg-indigo-600 p-8 rounded-[2.5rem] flex flex-wrap gap-4 items-center">
            <input type="hidden" name="type" value="product">
            <input type="text" name="product_name" placeholder="Item Name" class="flex-1 bg-white/10 p-4 rounded-2xl outline-none placeholder:text-indigo-200" required>
            <input type="number" name="price" placeholder="Price" class="w-32 bg-white/10 p-4 rounded-2xl outline-none placeholder:text-indigo-200" required>
            <input type="number" name="stock" placeholder="Stock" class="w-32 bg-white/10 p-4 rounded-2xl outline-none placeholder:text-indigo-200" required>
            <button class="bg-white text-indigo-600 px-10 py-4 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition-all">Submit</button>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>