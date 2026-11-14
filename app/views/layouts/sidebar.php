<?php
/**
 * Navigation Sidebar Layout
 * RTIH TaskFlow
 */
$currentPage = $_GET['page'] ?? 'dashboard';
$user = auth_user();
$userName = $user['name'] ?? 'User';
$userEmail = $user['email'] ?? '';
$userRole = $user['role'] ?? 'user';
$userInitial = strtoupper(substr($userName, 0, 1));
?>
<!-- Sidebar Navigation -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-surface-container-lowest border-r border-outline-variant/40 z-30 flex flex-col justify-between py-6 px-4 shadow-[0_1px_8px_rgba(0,0,0,0.03)]">
    <div class="flex flex-col gap-6">
        <!-- Logo Branding -->
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[22px]">task_alt</span>
            </div>
            <div>
                <span class="font-heading font-bold text-xl text-on-surface tracking-tight block leading-tight">TaskFlow</span>
                <span class="text-[11px] font-medium text-on-surface-variant tracking-wider uppercase block">Plan. Track. Progress.</span>
            </div>
        </div>

        <!-- Main Navigation Links -->
        <nav class="flex flex-col gap-1.5">
            <span class="px-3 text-[11px] font-semibold text-on-surface-variant/80 tracking-wider uppercase mb-1">Workspace</span>
            
            <a href="<?= BASE_URL ?>/public/index.php?page=dashboard" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all <?= in_array($currentPage, ['dashboard', '']) ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' ?>">
                <span class="material-symbols-outlined text-[20px]">grid_view</span>
                <span>Dashboard</span>
            </a>

            <a href="<?= BASE_URL ?>/public/index.php?page=dashboard" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all <?= $currentPage === 'my-tasks' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' ?>">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span>My Tasks</span>
            </a>

            <?php if (is_admin()): ?>
            <div class="pt-4 mt-2 border-t border-outline-variant/30">
                <span class="px-3 text-[11px] font-semibold text-primary tracking-wider uppercase mb-1 block">Administrator</span>
                
                <a href="<?= BASE_URL ?>/public/index.php?page=admin-tasks" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all <?= $currentPage === 'admin-tasks' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' ?>">
                    <span class="material-symbols-outlined text-[20px]">checklist</span>
                    <span>All Tasks</span>
                </a>

                <a href="<?= BASE_URL ?>/public/index.php?page=admin-users" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all <?= $currentPage === 'admin-users' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' ?>">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span>Users</span>
                </a>
            </div>
            <?php endif; ?>
        </nav>
    </div>

    <!-- User Profile & Sign Out -->
    <div class="pt-4 border-t border-outline-variant/30 flex flex-col gap-2">
        <div class="flex items-center justify-between p-2 rounded-xl bg-surface-container-low">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0">
                    <?= e($userInitial) ?>
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-on-surface truncate leading-tight"><?= e($userName) ?></p>
                    <span class="text-[10px] uppercase font-semibold tracking-wide <?= $userRole === 'admin' ? 'text-primary' : 'text-secondary' ?>">
                        <?= e($userRole === 'admin' ? 'Administrator' : 'Standard User') ?>
                    </span>
                </div>
            </div>

            <!-- Sign Out Form -->
            <form action="<?= BASE_URL ?>/public/actions/auth.php?action=logout" method="POST" class="m-0">
                <?= csrf_field() ?>
                <button type="submit" title="Sign out" class="w-7 h-7 rounded-lg text-on-surface-variant hover:text-error hover:bg-error-container/40 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
