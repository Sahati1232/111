<?php
require_once 'config/db.php';

try {
    // 1. Create the table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'manager') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Prepare the passwords
    $managerPass = password_hash("2009", PASSWORD_DEFAULT);

    // 3. Insert users (Using REPLACE to avoid duplicate errors if you run it twice)
    $stmt = $pdo->prepare("REPLACE INTO staff (id, username, password, role) VALUES 
        (2, 'dorii', ?, 'manager')");
    
    $stmt->execute([$managerPass]);

    echo "<h1 style='color:green;'>Success!</h1>";
    echo "<p>Table 'staff' created with:</p>";
    echo "<p><b>Manager:</b> dorii / 2009</p>";
    echo "<p><a href='login.php' style='color:blue;'>Go to Login</a></p>";

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>