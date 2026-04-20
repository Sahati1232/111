<?php
require_once 'config/db.php';

echo "<h1>Database Debug</h1>";

try {
    // Check users table exists
    $result = $pdo->query("SELECT * FROM users")->fetchAll();
    echo "<p style='color:green;'>✓ Users table exists</p>";
    echo "<p>Total users: " . count($result) . "</p>";
    
    echo "<table border='1' style='margin-top:20px;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Name</th><th>Role</th><th>Password Hash</th><th>Created</th></tr>";
    foreach($result as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['name'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td><small>" . substr($user['password'], 0, 20) . "...</small></td>";
        echo "<td>" . $user['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test password
    if(count($result) > 0) {
        $user = $result[0];
        $test_pass = '2009';
        $verify = password_verify($test_pass, $user['password']);
        echo "<p style='margin-top:20px;'><b>Password Test:</b></p>";
        echo "<p>Username: " . $user['username'] . "</p>";
        echo "<p>Test password: " . $test_pass . "</p>";
        echo "<p>Hash: " . $user['password'] . "</p>";
        echo "<p>Result: " . ($verify ? "<span style='color:green;'>✓ MATCH</span>" : "<span style='color:red;'>✗ NO MATCH</span>") . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
