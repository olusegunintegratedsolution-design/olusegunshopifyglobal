<?php
// set_live_admin_pass.php - USE ONCE AND DELETE IMMEDIATELY
require_once __DIR__ . '/config/db.php';

// *** IMPORTANT: CHANGE THIS TO YOUR DESIRED NEW STRONG PASSWORD ***
// This will be the NEW password for your live admin account.
// Make sure it's strong (letters, numbers, symbols, 12+ chars)
$new_admin_password = 'MySuperSecureLivePassword2025!'; // <--- CHANGE THIS!

// Your desired admin username
$new_admin_username = 'Felix@2026'; // You can change this too if you want
$new_admin_email = 'olusegunintegratedsolution@gmail.com'; // <--- CHANGE THIS to your actual email

// --- DO NOT EDIT BELOW THIS LINE ---

$message = '';
$error = '';

try {
    // Generate the new secure hash on the live server
    $hashed_password = password_hash($new_admin_password, PASSWORD_DEFAULT);

    // Clear existing admin users and insert the new one
    $pdo->exec("TRUNCATE TABLE admins"); // Clears all existing admin records
    $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute([$new_admin_username, $new_admin_email, $hashed_password]);

    $message = "SUCCESS! Live admin credentials updated.";
    $message .= "<br><strong>Username:</strong> <code>" . htmlspecialchars($new_admin_username) . "</code>";
    $message .= "<br><strong>Password:</strong> <code>" . htmlspecialchars($new_admin_password) . "</code>";
    $message .= "<br><br><strong><span style='color: #ef4444;'>⚠️ VERY IMPORTANT: DELETE THIS FILE IMMEDIATELY AFTER LOGGING IN.</span></strong>";

} catch (Exception $e) {
    $error = "DATABASE ERROR: " . $e->getMessage();
    $error .= "<br>Please ensure your database credentials in `/config/db.php` are correct.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LIVE ADMIN PASSWORD SETTER</title>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></script>
    <style>
        body { background-color: #020617; color: #f8fafc; font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background-color: #0f172a; border: 1px solid #1e293b; padding: 40px; border-radius: 12px; text-align: center; max-width: 600px; }
        code { background-color: #1e293b; padding: 4px 8px; border-radius: 4px; color: #10b981; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Live Admin Credentials Setter</h1>
        <?php if (!empty($message)): ?>
            <p class="success text-lg mb-4"><?= $message ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p class="error text-lg mb-4"><?= $error ?></p>
        <?php endif; ?>
        <p class="text-sm text-gray-400">This script runs only once to set a new secure admin password directly on your live database.</p>
    </div>
</body>
</html>