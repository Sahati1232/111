<?php
require_once 'config/db.php';

try {
    // Drop existing users table
    $pdo->exec("DROP TABLE IF EXISTS users");
    echo "<p style='color:green;'>✓ Dropped old users table</p>";
    
    // Create fresh users table
    $pdo->exec("CREATE TABLE users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(100) DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<p style='color:green;'>✓ Created new users table</p>";
    
    // Add default user
    $hashed_pass = password_hash('2009', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)")
        ->execute(['dorii', $hashed_pass, 'Manager User', 'manager']);
    echo "<p style='color:green;'>✓ Added default user: dorii / 2009</p>";
    
    echo "<h1 style='color:green;'>Success!</h1>";
    echo "<p><b>Login with:</b> dorii / 2009</p>";
    echo "<p><a href='login.php' style='color:blue;'>Go to Login</a></p>";
    
} catch (PDOException $e) {
    die("<p style='color:red;'>Error: " . $e->getMessage() . "</p>");
}
?>
