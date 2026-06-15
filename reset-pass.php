<?php
// reset-pass.php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Clear any existing admin records to avoid conflicts
    $pdo->exec("TRUNCATE TABLE admins");

    // 2. Set password & generate secure local hash using your computer's PHP compiler
    $password = 'AdminPassword2025!';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // 3. Insert fresh admin credentials
    $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        'admin', 
        'olusegunintegratedsolution@gmail.com', 
        $hash, 
        'admin'
    ]);

    echo "<div style='font-family:sans-serif; padding:20px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; max-width:500px; margin:40px auto;'>";
    echo "<h3 style='color:#15803d; margin-top:0;'>Success! Password Reset Completed</h3>";
    echo "<p>Your local PHP compiler has successfully generated the password hash and updated the database.</p>";
    echo "<p><strong>Username:</strong> <code style='background:#e2e8f0; padding:2px 6px; border-radius:4px;'>admin</code></p>";
    echo "<p><strong>Password:</strong> <code style='background:#e2e8f0; padding:2px 6px; border-radius:4px;'>AdminPassword2025!</code></p>";
    echo "<p style='color:#b91c1c; font-size:13px; font-weight:bold; margin-bottom:0;'>⚠️ FOR SECURITY: Delete the 'reset-pass.php' file from your folder immediately after logging in.</p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div style='color:red; font-family:sans-serif; padding:20px;'>Error: " . $e->getMessage() . "</div>";
}