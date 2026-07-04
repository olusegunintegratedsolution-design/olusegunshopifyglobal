<?php
require_once __DIR__ . '/../config/db.php';
check_auth();

$message = '';
$error = '';
$is_super = ($_SESSION['admin_role'] === 'super_admin');

// 1. Process Secondary Admin Creation (Super Admin Only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die("CSRF validation failure.");
    }

    // Role-based security validation
    if (!$is_super) {
        $error = "Access denied. Only the primary Super Admin can manage administrator credentials.";
    } else {
        if ($_POST['action_type'] === 'create_sub_admin') {
            $sub_user = trim($_POST['sub_username'] ?? '');
            $sub_email = filter_var(trim($_POST['sub_email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $sub_pass = trim($_POST['sub_password'] ?? '');

            if (!empty($sub_user) && !empty($sub_pass) && $sub_email) {
                try {
                    $hashed_sub = password_hash($sub_pass, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO admins (username, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
                    $stmt->execute([$sub_user, $sub_email, $hashed_sub]);
                    $message = "Secondary Admin '{$sub_user}' created successfully.";
                } catch (Exception $e) {
                    $error = "Failed to create account: " . $e->getMessage();
                }
            } else {
                $error = "Please fill in all fields with valid information.";
            }
        }
    }
}

// 2. Handle Secondary Admin Deletion (Super Admin Only)
if (isset($_GET['delete_admin_id']) && $is_super) {
    $delete_id = (int)$_GET['delete_admin_id'];
    // Prevent deleting oneself
    if ($delete_id !== (int)$_SESSION['admin_id']) {
        $pdo->prepare("DELETE FROM admins WHERE id = ? AND role = 'admin'")->execute([$delete_id]);
        $message = "Administrator account removed successfully.";
    }
}

// Fetch all secondary administrators for supervision list
$admins_list = $pdo->query("SELECT id, username, email, role, created_at FROM admins ORDER BY id ASC")->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>

<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">System Settings & Management</h1>
            <p class="text-xs text-slate-400 mt-1">Configure global parameters and supervise admin credentials</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl font-bold">
            <?= sanitize_output($message) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-xl font-bold">
            <?= sanitize_output($error) ?>
        </div>
    <?php endif; ?>

    <!-- Section 1: User Management Panel (Super Admin Only) -->
    <?php if ($is_super): ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- Create Secondary Admin Form -->
            <div class="lg:col-span-5 bg-slate-900 border border-slate-800 p-6 rounded-2xl">
                <h3 class="text-md font-bold text-white mb-2"><i class="fa-solid fa-user-plus text-emerald-500 mr-2"></i> Add Secondary Admin</h3>
                <p class="text-xs text-slate-400 mb-6">Create restricted sub-accounts for daily content tasks.</p>

                <form action="settings.php" method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="action_type" value="create_sub_admin">

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Username</label>
                        <input type="text" name="sub_username" required placeholder="e.g. manager_john" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Email</label>
                        <input type="email" name="sub_email" required placeholder="john@yourdomain.com" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Password</label>
                        <input type="password" name="sub_password" required placeholder="Create secure password" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none">
                    </div>

                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-lg transition-all">
                        Create Sub-Admin
                    </button>
                </form>
            </div>

            <!-- Administrators List Table -->
            <div class="lg:col-span-7 bg-slate-900 border border-slate-800 p-6 rounded-2xl">
                <h3 class="text-md font-bold text-white mb-6"><i class="fa-solid fa-users-gear text-emerald-500 mr-2"></i> Admin Supervision Panel</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Username</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <?php foreach ($admins_list as $user): ?>
                                <tr class="hover:bg-slate-900/50">
                                    <td class="px-4 py-3">
                                        <span class="font-bold text-white"><?= sanitize_output($user['username']) ?></span>
                                        <span class="block text-[10px] text-slate-500"><?= sanitize_output($user['email']) ?></span>
                                    </td>
                                    <td class="px-4 py-3 capitalize">
                                        <span class="px-2 py-0.5 rounded font-bold text-[9px] <?= $user['role'] === 'super_admin' ? 'bg-purple-500/10 text-purple-400' : 'bg-blue-500/10 text-blue-400' ?>">
                                            <?= str_replace('_', ' ', $user['role']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <a href="settings.php?delete_admin_id=<?= $user['id'] ?>" onclick="return confirm('Revoke all access privileges for this administrator?');" class="text-red-400 hover:text-red-500 font-bold">Revoke</a>
                                        <?php else: ?>
                                            <span class="text-slate-600 italic">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Restricted Panel Message for Secondary Admins -->
        <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl text-center mb-12">
            <i class="fa-solid fa-user-shield text-amber-500 text-4xl mb-4"></i>
            <h3 class="text-lg font-bold text-white">System Settings Locked</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto mt-2 leading-relaxed">
                Your account is currently registered with restricted **Sub-Admin** access privileges. You can manage site content, but user management and global configuration adjustments are restricted.
            </p>
        </div>
    <?php endif; ?>
</main>
</body>
</html>