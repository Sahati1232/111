<?php
require_once '../config/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $source = $_POST['source'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, role = ? WHERE id = ?");
    if ($stmt->execute([$name, $role, $id])) {
        header("Location: ../$source?msg=updated");
    } else {
        echo "Error updating record.";
    }
}
?>