<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Fetch Aggregate Metric Counts
$visitorsCount = $pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
$messagesCount = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$blogsCount = $pdo->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
$servicesCount = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$testimonialsCount = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();

// Fetch 5 Recent Messages
$recentMsgs = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Fetch Recent Blogs
$recentBlogs = $pdo->query("SELECT b.*, c.name as category_name FROM blogs b JOIN blog_categories c ON b.category_id = c.id ORDER BY b.created_at DESC LIMIT 4")->fetchAll();
?>

<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Dashboard Overview</h1>
            <p class="text-xs text-slate-400 mt-1">Real-time performance indicators and operational metrics</p>
        </div>
        <a href="../index.php" target="_blank" class="px-4 py-2 bg-slate-900 border border-slate-800 text-xs font-bold rounded-lg hover:text-emerald-400 transition-colors">
            <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> View Public Site
        </a>
    </div>

    <!-- Analytics Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Visitors -->
        <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl flex items-center space-x-4">
            <div class="p-3.5 bg-blue-500/10 text-blue-400 rounded-xl">
                <i class="fa-solid fa-chart-line text-lg w-5 text-center"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Total Visitors</span>
                <span class="text-2xl font-black text-white"><?= $visitorsCount ?></span>
            </div>
        </div>
        <!-- Inbox -->
        <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl flex items-center space-x-4">
            <div class="p-3.5 bg-emerald-500/10 text-emerald-400 rounded-xl">
                <i class="fa-solid fa-envelope-open text-lg w-5 text-center"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Total Inquiries</span>
                <span class="text-2xl font-black text-white"><?= $messagesCount ?></span>
            </div>
        </div>
        <!-- Blogs -->
        <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl flex items-center space-x-4">
            <div class="p-3.5 bg-purple-500/10 text-purple-400 rounded-xl">
                <i class="fa-solid fa-marker text-lg w-5 text-center"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Blog Posts</span>
                <span class="text-2xl font-black text-white"><?= $blogsCount ?></span>
            </div>
        </div>
        <!-- Services -->
        <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl flex items-center space-x-4">
            <div class="p-3.5 bg-amber-500/10 text-amber-400 rounded-xl">
                <i class="fa-solid fa-cubes text-lg w-5 text-center"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Active Services</span>
                <span class="text-2xl font-black text-white"><?= $servicesCount ?></span>
            </div>
        </div>
        <!-- Reviews -->
        <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl flex items-center space-x-4">
            <div class="p-3.5 bg-rose-500/10 text-rose-400 rounded-xl">
                <i class="fa-solid fa-star text-lg w-5 text-center"></i>
            </div>
            <div>
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Reviews</span>
                <span class="text-2xl font-black text-white"><?= $testimonialsCount ?></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Inquiries Panel -->
        <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white">Recent Customer Inquiries</h3>
                <a href="contact-messages.php" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">View Inbox</a>
            </div>

            <div class="space-y-4">
                <?php if (empty($recentMsgs)): ?>
                    <p class="text-xs text-slate-500 py-4 text-center">No messages received yet.</p>
                <?php else: ?>
                    <?php foreach ($recentMsgs as $msg): ?>
                        <div class="p-4 bg-slate-950/60 rounded-xl border border-slate-800 flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-white"><?= sanitize_output($msg['full_name']) ?></span>
                                    <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide <?= $msg['message_type'] === 'contact' ? 'bg-blue-500/10 text-blue-400' : 'bg-emerald-500/10 text-emerald-400' ?>">
                                        <?= sanitize_output($msg['message_type']) ?>
                                    </span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-1"><?= sanitize_output($msg['business_name'] ?? 'Private Brand') ?> • <?= sanitize_output($msg['email']) ?></span>
                            </div>
                            <span class="text-[10px] text-slate-500"><?= date('M d, g:i a', strtotime($msg['created_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Blog Posts Panel -->
        <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white">Recent Blog Content</h3>
                <a href="manage-blogs.php" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 font-medium">Manage Blogs</a>
            </div>

            <div class="space-y-4">
                <?php if (empty($recentBlogs)): ?>
                    <p class="text-xs text-slate-500 py-4 text-center">No posts created yet.</p>
                <?php else: ?>
                    <?php foreach ($recentBlogs as $b): ?>
                        <div class="flex items-center space-x-4 p-3 bg-slate-950/40 rounded-xl border border-slate-800">
                            <img src="../<?= sanitize_output($b['featured_image']) ?>" alt="" class="w-12 h-12 object-cover rounded-lg">
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold text-white block truncate"><?= sanitize_output($b['title']) ?></span>
                                <span class="text-[10px] text-emerald-400 block font-semibold mt-0.5"><?= sanitize_output($b['category_name']) ?></span>
                            </div>
                            <span class="text-[10px] text-slate-500 capitalize"><?= $b['status'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>