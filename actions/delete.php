<?php
session_start();
require_once '../config/db.php';

// Check if user is admin (for staff deletion)
if ($_GET['type'] === 'staff' && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'product'; // user, product, or staff

if($id) {
    if ($type === 'user') {
        $table = 'users';
    } elseif ($type === 'staff') {
        $table = 'staff';
    } else {
        $table = 'products';
    }
    
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$id]);
}

// Redirect back to the main dashboard
header("Location: ../admin_dashboard.php?msg=deleted");
exit;