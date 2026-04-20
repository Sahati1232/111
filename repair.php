<?php
require_once 'config/db.php';

try {
    // 1. Ensure the password column can hold a full hash (at least 255 chars)
    $pdo->exec("ALTER TABLE staff MODIFY COLUMN password VARCHAR(255) NOT NULL");

    // 2. Clear the table
    $pdo->exec("TRUNCATE TABLE staff");

    // 3. Generate a fresh hash for '12345'
    $freshHash = password_hash("12345", PASSWORD_DEFAULT);

    // 4. Insert the clean record
    $stmt = $pdo->prepare("INSERT INTO staff (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $freshHash, 'admin']);

    echo "<div style='background: #ecfdf5; color: #065f46; padding: 20px; border-radius: 10px; font-family: sans-serif;'>";
    echo "✅ <b>Success!</b> The 'admin' account has been reset.<br>";
    echo "Password is now exactly: <b>12345</b><br><br>";
    echo "<a href='index.php'>Back to Login</a>";
    echo "</div>";
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}