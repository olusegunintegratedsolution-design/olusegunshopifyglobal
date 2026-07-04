<?php
require_once __DIR__ . '/../config/db.php';
check_auth();

$message = '';
$error = '';

// Load existing values or set defaults
$heroStmt = $pdo->prepare("SELECT content_json FROM pages WHERE section_key = ?");
$heroStmt->execute(['home_hero']);
$heroData = json_decode($heroStmt->fetchColumn() ?: '', true) ?: [
    'tagline' => 'VERIFIED SHOPIFY PARTNER',
    'headline' => 'Scale Your Store Globally with Shopify Experts',
    'desc' => 'Olusegun Global Solutions Ltd designs, builds, and optimizes ultra-premium, high-converting storefronts.'
];

$aboutStmt = $pdo->prepare("SELECT content_json FROM pages WHERE section_key = ?");
$aboutStmt->execute(['about_us']);
$aboutData = json_decode($aboutStmt->fetchColumn() ?: '', true) ?: [
    'mission' => 'To build highly optimized, secure, and beautiful digital systems.',
    'vision' => 'To remain a top-tier global authority in Shopify Ecosystem Optimization.',
    'values' => 'Absolute transparency, speed, data-driven decisions.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die("CSRF protection failure.");
    }

    if (isset($_POST['update_hero'])) {
        $heroData = [
            'tagline' => trim($_POST['tagline'] ?? ''),
            'headline' => trim($_POST['headline'] ?? ''),
            'desc' => trim($_POST['desc'] ?? '')
        ];
        $stmt = $pdo->prepare("INSERT INTO pages (section_key, content_json) VALUES ('home_hero', ?) ON DUPLICATE KEY UPDATE content_json = ?");
        $stmt->execute([json_encode($heroData), json_encode($heroData)]);
        $message = "Homepage Hero Section updated.";
    }

    if (isset($_POST['update_about'])) {
        $aboutData = [
            'mission' => trim($_POST['mission'] ?? ''),
            'vision' => trim($_POST['vision'] ?? ''),
            'values' => trim($_POST['values'] ?? '')
        ];
        $stmt = $pdo->prepare("INSERT INTO pages (section_key, content_json) VALUES ('about_us', ?) ON DUPLICATE KEY UPDATE content_json = ?");
        $stmt->execute([json_encode($aboutData), json_encode($aboutData)]);
        $message = "About Us Section updated.";
    }
}

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>
<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white">Manage Pages</h1>
        <p class="text-xs text-slate-400 mt-1">Configure static textual page assets</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl font-bold"><?= $message ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Hero Section Form -->
        <form action="manage-pages.php" method="POST" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <h3 class="text-md font-bold text-white mb-2">Homepage Hero Settings</h3>

            <div>
                <label class="block text-xs text-slate-400 mb-1">Tagline Tag</label>
                <input type="text" name="tagline" value="<?= sanitize_output($heroData['tagline']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Main Headline</label>
                <input type="text" name="headline" value="<?= sanitize_output($heroData['headline']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Hero Description</label>
                <textarea name="desc" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-4 text-sm text-white focus:outline-none"><?= sanitize_output($heroData['desc']) ?></textarea>
            </div>
            <button type="submit" name="update_hero" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-lg">Update Hero</button>
        </form>

        <!-- About Us Section Form -->
        <form action="manage-pages.php" method="POST" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <h3 class="text-md font-bold text-white mb-2">About Us Section</h3>

            <div>
                <label class="block text-xs text-slate-400 mb-1">Company Mission</label>
                <textarea name="mission" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-3 text-sm text-white focus:outline-none"><?= sanitize_output($aboutData['mission']) ?></textarea>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Company Vision</label>
                <textarea name="vision" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-3 text-sm text-white focus:outline-none"><?= sanitize_output($aboutData['vision']) ?></textarea>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Core Values Statement</label>
                <textarea name="values" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-3 text-sm text-white focus:outline-none"><?= sanitize_output($aboutData['values']) ?></textarea>
            </div>
            <button type="submit" name="update_about" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-lg">Update About Content</button>
        </form>
    </div>
</main>
</body>
</html>