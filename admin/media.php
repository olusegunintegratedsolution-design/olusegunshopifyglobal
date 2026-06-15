<?php
require_once __DIR__ . '/../config/db.php';

$message = '';
$errMessage = '';
$uploadDir = __DIR__ . '/../uploads/';

// Ensure directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// 1. Handle File Upload Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die("CSRF Token failure.");
    }

    $file = $_FILES['media_file'];
    $originalName = basename($file['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];

    // Secure MIME check
    if (!in_array($extension, $allowedExtensions)) {
        $errMessage = "Format extension denied. Allowed formats: JPG, JPEG, PNG, SVG, WEBP.";
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $errMessage = "Invalid image file signature.";
        } elseif ($file['size'] > 5242880) { // Limit size to 5MB
            $errMessage = "File exceeds the 5MB size limit.";
        } else {
            // Rename to prevent conflicts or executable directory paths
            $safeName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "", pathinfo($originalName, PATHINFO_FILENAME));
            $finalName = $safeName . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $finalName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $dbPath = 'uploads/' . $finalName;
                $stmt = $pdo->prepare("INSERT INTO media_library (file_name, file_path, file_size, file_type) VALUES (?, ?, ?, ?)");
                $stmt->execute([$originalName, $dbPath, $file['size'], $mimeType]);
                $message = "File uploaded and saved to the media library.";
            } else {
                $errMessage = "Upload destination error.";
            }
        }
    }
}

// 2. Handle File Deletion
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("SELECT * FROM media_library WHERE id = ?");
    $stmt->execute([$deleteId]);
    $item = $stmt->fetch();

    if ($item) {
        $absolutePath = __DIR__ . '/../' . $item['file_path'];
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }
        $pdo->prepare("DELETE FROM media_library WHERE id = ?")->execute([$deleteId]);
        $message = "Asset removed successfully.";
    }
}

// Fetch Assets
$assets = $pdo->query("SELECT * FROM media_library ORDER BY uploaded_at DESC")->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';
?>

<main class="flex-1 overflow-y-auto p-8 bg-slate-950">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Media Library</h1>
            <p class="text-xs text-slate-400 mt-1">Manage, preview, and organize asset files</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl text-center font-bold">
            <?= sanitize_output($message) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($errMessage)): ?>
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-xl text-center font-bold">
            <?= sanitize_output($errMessage) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Upload Form Panel -->
        <div class="lg:col-span-4 bg-slate-900 border border-slate-800 p-6 rounded-2xl h-fit">
            <h3 class="text-md font-bold text-white mb-4">Upload New Asset</h3>
            <form action="media.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <div class="border-2 border-dashed border-slate-800 hover:border-emerald-500/30 rounded-xl p-6 text-center cursor-pointer transition-all relative">
                    <input type="file" name="media_file" required class="absolute inset-0 opacity-0 cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up text-slate-500 text-2xl mb-2"></i>
                    <p class="text-xs text-slate-400 font-semibold">Drag & Drop or click to browse</p>
                    <p class="text-[10px] text-slate-500 mt-1">Supports WEBP, SVG, PNG, JPG (Max 5MB)</p>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-lg transition-all">
                    Upload
                </button>
            </form>
        </div>

        <!-- Asset Grid Panel -->
        <div class="lg:col-span-8 bg-slate-900 border border-slate-800 p-6 rounded-2xl">
            <h3 class="text-md font-bold text-white mb-6">Uploaded Files</h3>

            <?php if (empty($assets)): ?>
                <p class="text-xs text-slate-500 py-12 text-center">No assets found in the library.</p>
            <?php else: ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php foreach ($assets as $asset): ?>
                        <div class="bg-slate-950 rounded-xl border border-slate-800 p-3 flex flex-col justify-between group relative overflow-hidden">
                            <div class="h-28 flex items-center justify-center bg-slate-900/60 rounded-lg overflow-hidden mb-3 relative">
                                <img src="../<?= sanitize_output($asset['file_path']) ?>" alt="" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-all">
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-slate-300 block truncate" title="<?= sanitize_output($asset['file_name']) ?>"><?= sanitize_output($asset['file_name']) ?></span>
                                <span class="text-[9px] text-slate-500 block mt-0.5"><?= round($asset['file_size'] / 1024, 1) ?> KB</span>
                            </div>
                            <div class="border-t border-slate-900 pt-2.5 mt-2.5 flex justify-between items-center">
                                <button onclick="navigator.clipboard.writeText('<?= sanitize_output($asset['file_path']) ?>'); alert('File path copied!');" class="text-[10px] text-emerald-500 hover:text-emerald-400 font-semibold">
                                    Copy Link
                                </button>
                                <a href="media.php?delete_id=<?= $asset['id'] ?>" onclick="return confirm('Delete this asset?');" class="text-red-400 hover:text-red-500 text-xs">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>