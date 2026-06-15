<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");
?>
<aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between">
    <div class="p-6">
        <div class="flex items-center space-x-3 mb-8">
            <div class="w-8 h-8 bg-emerald-500 text-white flex items-center justify-center rounded font-extrabold text-sm">
                O
            </div>
            <div>
                <span class="font-bold text-sm block">OLUSEGUN CMS</span>
                <span class="text-[10px] text-slate-400 block -mt-1 font-semibold tracking-wide">ADMINISTRATOR</span>
            </div>
        </div>

        <nav class="space-y-1.5">
            <a href="dashboard.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'dashboard.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-chart-pie w-4"></i> <span>Dashboard</span>
            </a>
            <a href="manage-pages.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'manage-pages.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-file-invoice w-4"></i> <span>Manage Pages</span>
            </a>
            <a href="manage-services.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'manage-services.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-cubes w-4"></i> <span>Manage Services</span>
            </a>
            <a href="manage-blogs.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'manage-blogs.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-pencil w-4"></i> <span>Manage Blogs</span>
            </a>
            <a href="manage-testimonials.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'manage-testimonials.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-comments w-4"></i> <span>Testimonials</span>
            </a>
            <a href="contact-messages.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'contact-messages.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-envelope-open-text w-4"></i> <span>Inbox Logs</span>
            </a>
            <a href="media.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'media.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-photo-film w-4"></i> <span>Media Library</span>
            </a>
            <a href="settings.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-semibold <?= $currentPage === 'settings.php' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> transition-all">
                <i class="fa-solid fa-sliders w-4"></i> <span>Settings & SEO</span>
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-slate-800">
        <a href="logout.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold text-red-400 hover:bg-red-500/10 transition-all">
            <i class="fa-solid fa-power-off w-4"></i> <span>Sign Out</span>
        </a>
    </div>
</aside>