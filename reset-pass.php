<?php
// reset-pass.php - Self-Locking Setup Wizard
require_once __DIR__ . '/config/db.php';

$message = '';
$error = '';
$is_locked = false;

try {
    // Check if any administrator already exists in the system
    $adminCount = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($adminCount > 0) {
        $is_locked = true;
    }
} catch (Exception $e) {
    $error = "System Database Check Failed: " . $e->getMessage();
}

// Process Form Submission (Only if system is not locked)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_locked) {
    $username = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password) && $email) {
        try {
            // Hash the password securely using standard bcrypt
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert as the supreme Super Admin
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, role) VALUES (?, ?, ?, 'super_admin')");
            $stmt->execute([$username, $email, $hashedPassword]);

            $message = "Super Admin account initialized successfully.";
            $is_locked = true; // Lock down immediately
        } catch (Exception $e) {
            $error = "Initialization Failed: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields with valid information.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <title>System Initialization Wizard | Olusegun CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full flex items-center justify-center px-4 text-slate-100 font-sans">
    <div class="max-w-md w-full space-y-6 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <div class="inline-flex w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-lg items-center justify-center mb-4">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-white">System Setup Wizard</h2>
            <p class="text-xs text-slate-400 mt-2">Initialize your primary administrator credentials</p>
        </div>

        <?php if ($is_locked): ?>
            <!-- Lockdown State Display -->
            <div class="p-5 bg-red-500/10 border border-red-500/30 rounded-xl text-center space-y-3">
                <i class="fa-solid fa-lock text-red-400 text-3xl"></i>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">System Locked Down</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Primary Super Admin already initialized. This setup script is permanently disabled to prevent unauthorized access.
                </p>
                <p class="text-[10px] text-red-400 font-semibold pt-2 border-t border-red-950">
                    For complete security, delete the "reset-pass.php" file from your directory structure.
                </p>
            </div>
            <div class="text-center">
                <a href="admin/login.php" class="inline-flex items-center text-xs font-bold text-emerald-500 hover:text-emerald-400">
                    Go to Login Portal <i class="fa-solid fa-arrow-right-long ml-1.5"></i>
                </a>
            </div>
        <?php else: ?>
            <!-- Setup Form Display -->
            <?php if (!empty($message)): ?>
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl text-center font-bold">
                    <?= sanitize_output($message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-xl text-center font-bold">
                    <?= sanitize_output($error) ?>
                </div>
            <?php endif; ?>

            <form class="space-y-4" action="reset-pass.php" method="POST">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Create Username</label>
                    <input type="text" name="username" required placeholder="e.g. olusegun_owner" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Admin Email</label>
                    <input type="email" name="email" required placeholder="e.g. owner@yourdomain.com" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Set Strong Password</label>
                    <input type="password" name="password" required placeholder="Minimum 12 characters recommended" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg text-sm transition-all duration-300">
                    Initialize Super Admin Account
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>