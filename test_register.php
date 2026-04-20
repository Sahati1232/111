<?php
session_start();
require_once 'config/db.php';

// Force logout for testing
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 p-8">
    <div class="max-w-2xl mx-auto text-white">
        <h1 class="text-3xl font-bold mb-6">Registration Test</h1>
        
        <p class="mb-4">Session destroyed. You can now register.</p>
        
        <a href="register.php" class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg inline-block">
            Go to Registration Page
        </a>
        
        <div class="mt-8 p-4 bg-slate-800 rounded-lg">
            <h2 class="font-bold mb-2">Database Check:</h2>
            <?php
            try {
                $result = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch();
                echo "<p>Total users in database: " . $result['count'] . "</p>";
                
                $users = $pdo->query("SELECT id, username, name, role FROM users")->fetchAll();
                echo "<h3 class='font-bold mt-4'>Users:</h3>";
                foreach($users as $u) {
                    echo "<p>- " . $u['username'] . " (" . $u['role'] . ") - " . $u['name'] . "</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
