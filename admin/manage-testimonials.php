<?php
require_once __DIR__ . '/../config/db.php';
check_auth();

$message = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$tId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['add', 'edit'])) {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die("CSRF protection failure.");
    }

    $name = trim($_POST['client_name'] ?? '');
    $role = trim($_POST['client_role'] ?? '');
    $rating = (int)($_POST['star_rating'] ?? 5);
    $type = trim($_POST['project_type'] ?? '');
    $feedback = trim($_POST['feedback'] ?? '');

    if (!empty($name) && !empty($feedback)) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, client_role, star_rating, project_type, feedback) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $rating, $type, $feedback]);
                $message = "Testimonial published successfully.";
            } else {
                $stmt = $pdo->prepare("UPDATE testimonials SET client_name = ?, client_role = ?, star_rating = ?, project_type = ?, feedback = ? WHERE id = ?");
                $stmt->execute([$name, $role, $rating, $type, $feedback, $tId]);
                $message = "Testimonial updated successfully.";
            }
            $action = 'list';
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

if ($action === 'delete' && $tId > 0) {
    $pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$tId]);
    $message = "Testimonial removed.";
    $action = 'list';
}

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>
<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Manage Testimonials</h1>
            <p class="text-xs text-slate-400 mt-1">Configure client review cards and star ratings</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="manage-testimonials.php?action=add" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg"><i class="fa-solid fa-plus mr-1"></i> Add Review</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl font-bold"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): 
        $list = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
    ?>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Client Name</th>
                        <th class="px-6 py-4">Role/Brand</th>
                        <th class="px-6 py-4">Stars</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if (empty($list)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 font-bold">No reviews created yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($list as $t): ?>
                            <tr class="hover:bg-slate-900/50">
                                <td class="px-6 py-4 font-bold text-white"><?= sanitize_output($t['client_name']) ?></td>
                                <td class="px-6 py-4 text-xs text-slate-400"><?= sanitize_output($t['client_role']) ?></td>
                                <td class="px-6 py-4 text-xs text-yellow-500"><?= str_repeat('★', $t['star_rating']) ?></td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="manage-testimonials.php?action=edit&id=<?= $t['id'] ?>" class="text-emerald-500 font-bold">Edit</a>
                                    <a href="manage-testimonials.php?action=delete&id=<?= $t['id'] ?>" onclick="return confirm('Delete testimonial?');" class="text-red-400">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php elseif (in_array($action, ['add', 'edit'])): 
        $t = ['client_name' => '', 'client_role' => '', 'star_rating' => 5, 'project_type' => 'Store Setup', 'feedback' => ''];
        if ($action === 'edit' && $tId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
            $stmt->execute([$tId]);
            $t = $stmt->fetch() ?: $t;
        }
    ?>
        <form action="manage-testimonials.php?action=<?= $action ?>&id=<?= $tId ?>" method="POST" class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Client Name</label>
                    <input type="text" name="client_name" required value="<?= sanitize_output($t['client_name']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Company / Role</label>
                    <input type="text" name="client_role" required value="<?= sanitize_output($t['client_role']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Project Classification</label>
                    <input type="text" name="project_type" required value="<?= sanitize_output($t['project_type']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Star Rating (1 to 5)</label>
                <select name="star_rating" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                    <option value="5" <?= $t['star_rating'] == 5 ? 'selected' : '' ?>>5 Stars</option>
                    <option value="4" <?= $t['star_rating'] == 4 ? 'selected' : '' ?>>4 Stars</option>
                    <option value="3" <?= $t['star_rating'] == 3 ? 'selected' : '' ?>>3 Stars</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Feedback Content</label>
                <textarea name="feedback" required rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none"><?= sanitize_output($t['feedback']) ?></textarea>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="manage-testimonials.php" class="px-5 py-2.5 bg-slate-800 text-white text-xs font-bold rounded-lg">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-lg">Publish Review</button>
            </div>
        </form>
    <?php endif; ?>
</main>
</body>
</html>