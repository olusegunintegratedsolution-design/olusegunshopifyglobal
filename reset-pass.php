<?php
// reset-pass.php
require_once __DIR__ . '/config/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $pass = trim($_POST['password'] ?? '');

    if (!empty($user) && !empty($pass) && $email) {
        try {
            // 1. Clear any existing admin records to avoid duplicates
            $pdo->exec("TRUNCATE TABLE admins");

            // 2. Generate secure bcrypt hash locally on the server
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            // 3. Insert fresh custom admin credentials
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
            $stmt->execute([$user, $email, $hash]);

            $message = "Success! Your live database has been updated with your custom credentials.";
        } catch (Exception $e) {
            $error = "Error updating database: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields with valid details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <title>Secure Credentials Configurator | Olusegun CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center px-4 text-slate-100 font-sans">
    <div class="max-w-md w-full space-y-6 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <h2 class="text-2xl font-extrabold text-white">Credentials Configurator</h2>
            <p class="text-xs text-slate-400 mt-2">Set up custom credentials for your live database</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl text-center font-bold">
                <?= $message ?>
                <p class="text-red-400 text-[11px] font-semibold mt-3">⚠️ IMPORTANT: Delete the reset-pass.php file from your code immediately for security.</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-xl text-center font-bold">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form class="space-y-4" action="reset-pass.php" method="POST">
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">New Custom Username</label>
                <input type="text" name="username" required placeholder="e.g. olusegun_admin" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Admin Email Address</label>
                <input type="email" name="email" required placeholder="e.g. admin@yourdomain.com" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">New Strong Password</label>
                <input type="password" name="password" required placeholder="Minimum 12 characters recommended" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg text-sm transition-all duration-300">
                Update Database Credentials
            </button>
        </form>
    </div>
</body>
</html>