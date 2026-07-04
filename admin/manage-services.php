<?php
require_once __DIR__ . '/../config/db.php';
check_auth();

$message = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle CRUD submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['add', 'edit'])) {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die("CSRF protection failure.");
    }

    $title = trim($_POST['title'] ?? '');
    $service_key = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $icon = trim($_POST['icon'] ?? 'fa-solid fa-cube');
    $description = trim($_POST['description'] ?? '');
    $expected_results = trim($_POST['expected_results'] ?? '');
    $why_needed = trim($_POST['why_needed'] ?? '');
    $problems_solved = trim($_POST['problems_solved'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    // Parse bullet benefits
    $benefits = array_filter(array_map('trim', explode("\n", $_POST['benefits'] ?? '')));
    $benefits_json = json_encode(array_values($benefits));

    if (!empty($title) && !empty($description)) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO services (service_key, title, icon, description, benefits_json, expected_results, why_needed, problems_solved, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$service_key, $title, $icon, $description, $benefits_json, $expected_results, $why_needed, $problems_solved, $sort_order]);
                $message = "Service added successfully.";
            } else {
                $stmt = $pdo->prepare("UPDATE services SET service_key = ?, title = ?, icon = ?, description = ?, benefits_json = ?, expected_results = ?, why_needed = ?, problems_solved = ?, sort_order = ? WHERE id = ?");
                $stmt->execute([$service_key, $title, $icon, $description, $benefits_json, $expected_results, $why_needed, $problems_solved, $sort_order, $serviceId]);
                $message = "Service updated successfully.";
            }
            $action = 'list';
        } catch (Exception $e) {
            $error = "Error saving service: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

if ($action === 'delete' && $serviceId > 0) {
    $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$serviceId]);
    $message = "Service deleted successfully.";
    $action = 'list';
}

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>
<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Manage Services</h1>
            <p class="text-xs text-slate-400 mt-1">Configure your e-commerce capability cards</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="manage-services.php?action=add" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg"><i class="fa-solid fa-plus mr-1"></i> Add Service</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl font-bold"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): 
        $services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();
    ?>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Icon Class</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if (empty($services)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">No services defined yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($services as $s): ?>
                            <tr class="hover:bg-slate-900/50">
                                <td class="px-6 py-4 font-bold text-white"><?= sanitize_output($s['title']) ?></td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400"><?= sanitize_output($s['icon']) ?></td>
                                <td class="px-6 py-4 text-xs text-slate-500"><?= $s['sort_order'] ?></td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="manage-services.php?action=edit&id=<?= $s['id'] ?>" class="text-emerald-500 font-bold">Edit</a>
                                    <a href="manage-services.php?action=delete&id=<?= $s['id'] ?>" onclick="return confirm('Delete service?');" class="text-red-400">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php elseif (in_array($action, ['add', 'edit'])): 
        $service = ['title' => '', 'icon' => 'fa-solid fa-cube', 'description' => '', 'benefits_json' => '[]', 'expected_results' => '', 'why_needed' => '', 'problems_solved' => '', 'sort_order' => 0];
        if ($action === 'edit' && $serviceId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch() ?: $service;
        }
        $benefits_list = implode("\n", json_decode($service['benefits_json'], true) ?: []);
    ?>
        <form action="manage-services.php?action=<?= $action ?>&id=<?= $serviceId ?>" method="POST" class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Service Title</label>
                    <input type="text" name="title" required value="<?= sanitize_output($service['title']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">FontAwesome Icon Class</label>
                    <input type="text" name="icon" required value="<?= sanitize_output($service['icon']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Service Description</label>
                <textarea name="description" required rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none"><?= sanitize_output($service['description']) ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-2">Benefits / Bullet Points (One per line)</label>
                <textarea name="benefits" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none"><?= sanitize_output($benefits_list) ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Why Needed</label>
                    <input type="text" name="why_needed" value="<?= sanitize_output($service['why_needed']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Expected Results</label>
                    <input type="text" name="expected_results" value="<?= sanitize_output($service['expected_results']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="<?= $service['sort_order'] ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="manage-services.php" class="px-5 py-2.5 bg-slate-800 text-white text-xs font-bold rounded-lg">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-lg">Save Service</button>
            </div>
        </form>
    <?php endif; ?>
</main>
</body>
</html>