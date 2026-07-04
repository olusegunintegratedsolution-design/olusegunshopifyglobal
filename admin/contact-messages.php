<?php
require_once __DIR__ . '/../config/db.php';
check_auth();

$message = '';
$action = $_GET['action'] ?? 'list';
$msgId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'delete' && $msgId > 0) {
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$msgId]);
    $message = "Inquiry log deleted successfully.";
    $action = 'list';
}

if ($action === 'read' && $msgId > 0) {
    $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$msgId]);
    $message = "Message marked as read.";
    $action = 'list';
}

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>
<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white">Inquiries Inbox</h1>
        <p class="text-xs text-slate-400 mt-1">View form submissions, SEO audits, and conversion reports</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl font-bold"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): 
        $inbox = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
    ?>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Sender</th>
                        <th class="px-6 py-4">Inquiry Type</th>
                        <th class="px-6 py-4">Target Website/Platform</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if (empty($inbox)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 font-bold">No inquiry records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($inbox as $m): ?>
                            <tr class="hover:bg-slate-900/50 <?= !$m['is_read'] ? 'bg-slate-900/20 font-bold' : '' ?>">
                                <td class="px-6 py-4">
                                    <span class="text-white block"><?= sanitize_output($m['full_name']) ?></span>
                                    <span class="text-xs text-slate-500 block"><?= sanitize_output($m['email']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs capitalize px-2 py-0.5 rounded font-semibold <?= $m['message_type'] === 'contact' ? 'bg-blue-500/10 text-blue-400' : 'bg-emerald-500/10 text-emerald-400' ?>">
                                        <?= str_replace('_', ' ', $m['message_type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    <?= sanitize_output($m['target_url'] ?? $m['business_name'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="contact-messages.php?action=view&id=<?= $m['id'] ?>" class="text-emerald-500">View</a>
                                    <a href="contact-messages.php?action=delete&id=<?= $m['id'] ?>" onclick="return confirm('Delete message?');" class="text-red-400">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($action === 'view' && $msgId > 0): 
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$msgId]);
        $m = $stmt->fetch();
        if (!$m) die("Inquiry log not found.");
        
        // Mark as read automatically when viewed
        if (!$m['is_read']) {
            $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$msgId]);
        }
    ?>
        <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white"><?= sanitize_output($m['full_name']) ?></h3>
                    <p class="text-xs text-slate-400"><?= sanitize_output($m['email']) ?> • Received <?= $m['created_at'] ?></p>
                </div>
                <span class="text-xs capitalize px-3 py-1 rounded font-bold bg-brand-500/10 text-brand-500"><?= sanitize_output($m['message_type']) ?></span>
            </div>

            <!-- Inquiry Metadata Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-400">
                <?php if ($m['whatsapp_number']): ?><div><strong>WhatsApp Contact:</strong> <?= sanitize_output($m['whatsapp_number']) ?></div><?php endif; ?>
                <?php if ($m['business_name']): ?><div><strong>Business Name:</strong> <?= sanitize_output($m['business_name']) ?></div><?php endif; ?>
                <?php if ($m['target_url']): ?><div><strong>Target URL:</strong> <a href="<?= $m['target_url'] ?>" target="_blank" class="text-brand-500"><?= sanitize_output($m['target_url']) ?></a></div><?php endif; ?>
                <?php if ($m['service_needed']): ?><div><strong>Service Requested:</strong> <?= sanitize_output($m['service_needed']) ?></div><?php endif; ?>
                <?php if ($m['budget_range']): ?><div><strong>Budget Range:</strong> <?= sanitize_output($m['budget_range']) ?></div><?php endif; ?>
                <?php if ($m['project_deadline']): ?><div><strong>Timeline:</strong> <?= sanitize_output($m['project_deadline']) ?></div><?php endif; ?>
            </div>

            <div class="border-t border-slate-800 pt-6 space-y-4">
                <?php if ($m['project_description']): ?>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-2">Project Brief Details</h4>
                        <div class="p-4 bg-slate-950 rounded-lg text-slate-300 text-xs leading-relaxed"><?= nl2br(sanitize_output($m['project_description'])) ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($m['main_challenge']): ?>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-2">Operational Challenges Description</h4>
                        <div class="p-4 bg-slate-950 rounded-lg text-slate-300 text-xs leading-relaxed"><?= nl2br(sanitize_output($m['main_challenge'])) ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800">
                <a href="contact-messages.php" class="px-5 py-2.5 bg-slate-800 text-white text-xs font-bold rounded-lg">Return to Inbox</a>
            </div>
        </div>
    <?php endif; ?>
</main>
</body>
</html>