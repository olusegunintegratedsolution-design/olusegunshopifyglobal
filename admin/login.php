<?php
require_once __DIR__ . '/../config/db.php';

$error = '';
$debug_info = ''; // Diagnostics container
$csrf_token = generateCsrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $error = "CSRF Token mismatch or session expired. Please reload and try again.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            try {
                // 2. Query Database
                $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
                $stmt->execute([$username]);
                $admin = $stmt->fetch();

                // 3. Build diagnostic information
                if (!$admin) {
                    $debug_info = "🔍 [Diagnostic]: User 'admin' was NOT found in the database. Ensure reset-pass.php was run successfully.";
                    $error = "Invalid username or password.";
                } else {
                    // Test password verification
                    $is_match = password_verify($password, $admin['password_hash']);
                    if ($is_match) {
                        // Establish active session
                        session_regenerate_id(true);
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_username'] = $admin['username'];
                        $_SESSION['admin_role'] = $admin['role'];

                        // Check if session actually saved
                        if (isset($_SESSION['admin_logged_in'])) {
                            header("Location: dashboard.php");
                            exit;
                        } else {
                            $debug_info = "🚨 [Diagnostic]: Login was successful, but your local XAMPP php.ini configuration is preventing sessions from saving.";
                            $error = "Session storage error.";
                        }
                    } else {
                        $debug_info = "❌ [Diagnostic]: User 'admin' exists, but the password hash did not match. Please run reset-pass.php again.";
                        $error = "Invalid username or password.";
                    }
                }
            } catch (Exception $e) {
                $error = "Database Error: " . $e->getMessage();
            }
        } else {
            $error = "Please fill in all fields.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <title>Admin Authentication | Olusegun CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center px-4">
    <div class="max-w-md w-full space-y-8 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <div class="inline-flex w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-lg items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-white">Access Portal</h2>
            <p class="text-xs text-slate-400 mt-2">Sign in to manage Olusegun Global Solutions Ltd</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-4 rounded-lg text-center font-semibold">
                <?= sanitize_output($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($debug_info)): ?>
            <div class="bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[11px] p-3 rounded-lg leading-relaxed">
                <?= sanitize_output($debug_info) ?>
            </div>
        <?php endif; ?>

        <form class="space-y-6" action="login.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Username</label>
                <input type="text" name="username" required placeholder="admin" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg text-sm transition-all duration-300">
                Log In
            </button>
        </form>
    </div>
</body>
</html>