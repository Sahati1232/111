<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type']; // 'user' or 'product'
    $source = $_POST['source'];

    if ($type === 'user') {
        $stmt = $pdo->prepare("INSERT INTO users (name, role) VALUES (?, ?)");
        $stmt->execute([$_POST['name'], $_POST['role']]);
    } 
    elseif ($type === 'product') {
        $stmt = $pdo->prepare("INSERT INTO products (product_name, price, stock) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['product_name'], $_POST['price'], $_POST['stock']]);
    }

header("Location: ../admin_dashboard.php?msg=success");
}
?>