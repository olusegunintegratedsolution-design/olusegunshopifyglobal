<?php
require_once __DIR__ . '/../config/db.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['add', 'edit'])) {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die("CSRF protection failure.");
    }

    $title = trim($_POST['title'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = $_POST['status'] === 'published' ? 'published' : 'draft';
    $seo_title = trim($_POST['seo_title'] ?? '');
    $seo_desc = trim($_POST['seo_description'] ?? '');
    
    // Auto Slugify Helper
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    } else {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
    }

    // Process Featured Image Placement
    $imagePath = $_POST['existing_image'] ?? 'uploads/blog-default.jpg';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['featured_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            $newName = 'blog_' . time() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], __DIR__ . '/../uploads/' . $newName)) {
                $imagePath = 'uploads/' . $newName;
            }
        }
    }

    if (!empty($title) && $category_id > 0) {
        if ($action === 'add') {
            $stmt = $pdo->prepare("INSERT INTO blogs (category_id, title, slug, summary, content, featured_image, status, seo_title, seo_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $title, $slug, $summary, $content, $imagePath, $status, $seo_title, $seo_desc]);
            $message = "Blog post created successfully.";
        } else {
            $stmt = $pdo->prepare("UPDATE blogs SET category_id = ?, title = ?, slug = ?, summary = ?, content = ?, featured_image = ?, status = ?, seo_title = ?, seo_description = ? WHERE id = ?");
            $stmt->execute([$category_id, $title, $slug, $summary, $content, $imagePath, $status, $seo_title, $seo_desc, $postId]);
            $message = "Blog post updated successfully.";
        }
        $action = 'list';
    }
}

// Handle Delete Post
if ($action === 'delete' && $postId > 0) {
    $pdo->prepare("DELETE FROM blogs WHERE id = ?")->execute([$postId]);
    $message = "Blog post deleted.";
    $action = 'list';
}

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>

<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Insights CMS</h1>
            <p class="text-xs text-slate-400 mt-1">Publish, edit, and organize article resources</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="manage-blogs.php?action=add" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition-colors">
                <i class="fa-solid fa-plus mr-1"></i> Add Post
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl text-center font-bold">
            <?= sanitize_output($message) ?>
        </div>
    <?php endif; ?>

    <!-- Action: List All Blog Posts -->
    <?php if ($action === 'list'): 
        $posts = $pdo->query("SELECT b.*, c.name as cat_name FROM blogs b JOIN blog_categories c ON b.category_id = c.id ORDER BY b.created_at DESC")->fetchAll();
    ?>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Published Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-xs">No posts found. Get started by publishing your first article.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $p): ?>
                                <tr class="hover:bg-slate-900/50">
                                    <td class="px-6 py-4 font-bold text-white"><?= sanitize_output($p['title']) ?></td>
                                    <td class="px-6 py-4 text-xs text-slate-400"><?= sanitize_output($p['cat_name']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded <?= $p['status'] === 'published' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' ?>">
                                            <?= $p['status'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500"><?= date('Y-m-d', strtotime($p['created_at'])) ?></td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="manage-blogs.php?action=edit&id=<?= $p['id'] ?>" class="text-emerald-500 hover:text-emerald-400 text-xs font-bold">Edit</a>
                                        <a href="manage-blogs.php?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Delete this post?');" class="text-red-400 hover:text-red-500 text-xs">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Action: Add / Edit Blog Post -->
    <?php elseif (in_array($action, ['add', 'edit'])): 
        $post = ['title' => '', 'category_id' => '', 'slug' => '', 'summary' => '', 'content' => '', 'featured_image' => 'uploads/blog-default.jpg', 'status' => 'draft', 'seo_title' => '', 'seo_description' => ''];
        if ($action === 'edit' && $postId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
            $stmt->execute([$postId]);
            $post = $stmt->fetch() ?: $post;
        }
        $categories = $pdo->query("SELECT * FROM blog_categories")->fetchAll();
    ?>
        <form action="manage-blogs.php?action=<?= $action ?>&id=<?= $postId ?>" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="existing_image" value="<?= sanitize_output($post['featured_image']) ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Article Title</label>
                        <input type="text" name="title" required value="<?= sanitize_output($post['title']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Custom URL Slug (Leave blank to generate automatically)</label>
                        <input type="text" name="slug" value="<?= sanitize_output($post['slug']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">SEO Summary</label>
                        <textarea name="summary" required rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500"><?= sanitize_output($post['summary']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Article Content (HTML supported)</label>
                        <textarea name="content" required rows="10" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-4 text-sm text-white focus:outline-none focus:border-emerald-500 font-mono"><?= sanitize_output($post['content']) ?></textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Category</label>
                        <select name="category_id" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= sanitize_output($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Status</label>
                        <select name="status" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none">
                            <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft Mode</option>
                            <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">Featured Image</label>
                        <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-center">
                            <img src="../<?= sanitize_output($post['featured_image']) ?>" class="h-28 mx-auto object-cover rounded-lg mb-3">
                            <input type="file" name="featured_image" class="text-xs text-slate-400 w-full">
                        </div>
                    </div>
                    <div class="border-t border-slate-800 pt-4 space-y-4">
                        <span class="text-xs font-bold text-white block uppercase tracking-wider">SEO Meta Overrides</span>
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1">SEO Title Override</label>
                            <input type="text" name="seo_title" value="<?= sanitize_output($post['seo_title']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1">SEO Description Override</label>
                            <textarea name="seo_description" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-3 text-xs text-white focus:outline-none"><?= sanitize_output($post['seo_description']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-6 flex justify-end space-x-4">
                <a href="manage-blogs.php" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg">Save Post</button>
            </div>
        </form>
    <?php endif; ?>
</main>
</body>
</html>