<?php
/**
 * Standard User Dashboard
 * 1:1 match to Google Stitch screen 631278972444447f83eb7996b801634b
 * RTIH TaskFlow
 */
$pageTitle = 'TaskFlow – Workspace Dashboard';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/toast.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$user = auth_user();
?>
<!-- Main Dashboard Layout (Flanked by fixed sidebar) -->
<div class="ml-64 min-h-screen flex flex-col justify-between bg-background">
    <div class="flex-1">
        
        <!-- Top Workspace Header -->
        <header class="h-16 border-b border-outline-variant/30 bg-surface/80 backdrop-blur-md sticky top-0 z-20 flex items-center justify-between px-8">
            <div class="flex items-center gap-2 text-xs font-medium text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>/</span>
                <span class="text-on-surface font-semibold">Workspace</span>
                <span>/</span>
                <span>My Dashboard</span>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="openCreateModal()" 
                        class="px-4 py-2 bg-primary hover:bg-primary/90 text-on-primary rounded-lg text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Add Task</span>
                </button>
            </div>
        </header>

        <!-- Main Workspace Container -->
        <main class="p-8 max-w-7xl mx-auto space-y-6">

            <!-- Greeting Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="font-heading text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
                        Good day, <?= e($user['name']) ?>
                    </h1>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                        Here is an overview of what needs your attention today.
                    </p>
                </div>

                <div class="text-right hidden sm:block">
                    <span class="text-xs text-on-surface-variant bg-surface-container-high px-3 py-1.5 rounded-full font-medium">
                        <?= date('l, F j, Y') ?>
                    </span>
                </div>
            </div>

            <!-- 4 Summary Metric Cards (Clickable Filter Cards) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Total Tasks -->
                <a href="<?= BASE_URL ?>/public/index.php?page=dashboard" 
                   class="p-5 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:border-primary/60 hover:shadow-md transition-all cursor-pointer block <?= empty($status) ? 'ring-2 ring-primary/40' : '' ?>"
                   title="View all tasks">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Tasks</span>
                        <div class="w-8 h-8 rounded-lg bg-surface-container text-on-surface flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">dataset</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-on-surface"><?= $metrics['total'] ?></span>
                        <span class="text-[11px] text-on-surface-variant block mt-0.5">Assigned to you &bull; View all</span>
                    </div>
                </a>

                <!-- 2. In Progress -->
                <a href="<?= BASE_URL ?>/public/index.php?page=dashboard&status=In+Progress" 
                   class="p-5 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:border-sky-500 hover:shadow-md transition-all cursor-pointer block <?= $status === 'In Progress' ? 'ring-2 ring-sky-500 bg-sky-50/20' : '' ?>"
                   title="Click to view In Progress tasks">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-sky-800 uppercase tracking-wider">In Progress</span>
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">pending</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-sky-900"><?= $metrics['in_progress'] ?></span>
                        <span class="text-[11px] text-sky-700 block mt-0.5">Active execution &bull; Click to filter</span>
                    </div>
                </a>

                <!-- 3. Completed -->
                <a href="<?= BASE_URL ?>/public/index.php?page=dashboard&status=Completed" 
                   class="p-5 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:border-emerald-500 hover:shadow-md transition-all cursor-pointer block <?= $status === 'Completed' ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : '' ?>"
                   title="Click to view Completed tasks">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider">Completed</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">task_alt</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-emerald-900"><?= $metrics['completed'] ?></span>
                        <span class="text-[11px] text-emerald-700 block mt-0.5">Successfully closed &bull; Click to filter</span>
                    </div>
                </a>

                <!-- 4. Pending -->
                <a href="<?= BASE_URL ?>/public/index.php?page=dashboard&status=Pending" 
                   class="p-5 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:border-amber-500 hover:shadow-md transition-all cursor-pointer block <?= $status === 'Pending' ? 'ring-2 ring-amber-500 bg-amber-50/20' : '' ?>"
                   title="Click to view Pending tasks">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Pending</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">schedule</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-amber-900"><?= $metrics['pending'] ?></span>
                        <span class="text-[11px] text-amber-700 block mt-0.5">Awaiting start &bull; Click to filter</span>
                    </div>
                </a>

            </div>

            <!-- Search, Filter & Controls Toolbar -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
                
                <!-- Search Form (GET) -->
                <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="w-full flex-1 flex flex-col sm:flex-row items-center gap-3">
                    <input type="hidden" name="page" value="dashboard">

                    <!-- Search Input -->
                    <div class="relative flex-1 w-full">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" name="search" value="<?= e($search) ?>"
                               class="w-full h-10 pl-9 pr-4 bg-surface-container-low text-on-surface text-xs rounded-lg border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-all placeholder:text-outline"
                               placeholder="Search your tasks by title or keyword...">
                    </div>

                    <!-- Status Filter Dropdown -->
                    <div class="relative w-full sm:w-44">
                        <select name="status" onchange="this.form.submit()"
                                class="w-full h-10 pl-3 pr-8 bg-surface-container-low text-on-surface text-xs rounded-lg border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none appearance-none cursor-pointer transition-all">
                            <option value="">All Statuses</option>
                            <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="In Progress" <?= $status === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="Completed" <?= $status === 'Completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-2.5 pointer-events-none text-on-surface-variant text-[18px]">expand_more</span>
                    </div>

                    <!-- Priority Filter Dropdown -->
                    <div class="relative w-full sm:w-40">
                        <select name="priority" onchange="this.form.submit()"
                                class="w-full h-10 pl-3 pr-8 bg-surface-container-low text-on-surface text-xs rounded-lg border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none appearance-none cursor-pointer transition-all">
                            <option value="">All Priorities</option>
                            <option value="High" <?= $priority === 'High' ? 'selected' : '' ?>>High</option>
                            <option value="Medium" <?= $priority === 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="Low" <?= $priority === 'Low' ? 'selected' : '' ?>>Low</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-2.5 pointer-events-none text-on-surface-variant text-[18px]">expand_more</span>
                    </div>

                    <button type="submit" class="px-4 h-10 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-lg transition-colors shrink-0">
                        Filter
                    </button>

                    <?php if (!empty($search) || !empty($status) || !empty($priority)): ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=dashboard" 
                       class="px-3 h-10 flex items-center justify-center text-xs text-error hover:bg-error-container/30 rounded-lg transition-colors shrink-0" title="Reset all filters">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                    </a>
                    <?php endif; ?>
                </form>

            </div>

            <!-- Task List / Table Container -->
            <div class="rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm overflow-hidden">
                
                <?php if (empty($tasks)): ?>
                <!-- Empty State -->
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant mb-3">
                        <span class="material-symbols-outlined text-[26px]">task_alt</span>
                    </div>
                    <h3 class="font-heading font-semibold text-base text-on-surface">No tasks found</h3>
                    <p class="text-xs text-on-surface-variant mt-1 max-w-sm">
                        <?= (!empty($search) || !empty($status) || !empty($priority)) 
                            ? 'No tasks match your active search or filter criteria. Try resetting filters.' 
                            : 'You have no tasks saved yet. Get started by creating your first task!' ?>
                    </p>
                    <button type="button" onclick="openCreateModal()" 
                            class="mt-4 px-4 py-2 bg-primary text-on-primary rounded-lg text-xs font-semibold shadow-sm hover:bg-primary/90 transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>Create Task</span>
                    </button>
                </div>
                <?php else: ?>
                <!-- Tasks Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container/60 border-b border-outline-variant/30 text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">
                                <th class="py-3 px-6">Task Title & Details</th>
                                <th class="py-3 px-4">Priority</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Due Date</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20 text-xs text-on-surface">
                            <?php foreach ($tasks as $task): 
                                $isCompleted = ($task['status'] === 'Completed');
                            ?>
                            <tr class="transition-colors group <?= $isCompleted ? 'bg-emerald-50/30 border-l-4 border-l-emerald-500 hover:bg-emerald-50/50' : 'hover:bg-surface-container-low/50 border-l-4 border-l-transparent' ?>">
                                
                                <!-- Title & Description -->
                                <td class="py-4 px-6 max-w-md">
                                    <div class="flex flex-col">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-on-surface text-sm leading-snug flex items-center gap-1.5">
                                                <?php if ($isCompleted): ?>
                                                <span class="material-symbols-outlined text-emerald-600 text-[18px]" title="Completed task">check_circle</span>
                                                <?php endif; ?>
                                                <?= e($task['title']) ?>
                                            </span>

                                            <!-- Interactive Quick Status Popup Trigger (Feature 1) -->
                                            <?php if (!$isCompleted): ?>
                                            <button type="button" 
                                                    onclick="openStatusChangeModal(<?= $task['id'] ?>, '<?= e(addslashes($task['title'])) ?>', '<?= $task['status'] ?>')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 hover:bg-emerald-200 text-emerald-800 border border-emerald-300 shadow-sm transition-all cursor-pointer"
                                                    title="Click to quickly update status without opening edit details">
                                                <span class="material-symbols-outlined text-[13px]">done_all</span>
                                                <span>Mark as Completed</span>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" 
                                                    onclick="openStatusChangeModal(<?= $task['id'] ?>, '<?= e(addslashes($task['title'])) ?>', '<?= $task['status'] ?>')"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-surface-container hover:bg-surface-container-high text-on-surface-variant transition-colors cursor-pointer"
                                                    title="Change task status">
                                                <span class="material-symbols-outlined text-[12px]">swap_horiz</span>
                                                <span>Change status</span>
                                            </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($task['description'])): ?>
                                        <span class="text-xs text-on-surface-variant mt-1 line-clamp-1">
                                            <?= e($task['description']) ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="text-[10px] text-on-surface-variant/70 mt-1">
                                            Created <?= date('M j, Y', strtotime($task['created_at'])) ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Priority Badge -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <?php if ($task['priority'] === 'High'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-red-100 text-red-800">
                                        High
                                    </span>
                                    <?php elseif ($task['priority'] === 'Medium'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-orange-100 text-orange-800">
                                        Medium
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800">
                                        Low
                                    </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status Badge with Dot (Clickable to change status) -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <button type="button" 
                                            onclick="openStatusChangeModal(<?= $task['id'] ?>, '<?= e(addslashes($task['title'])) ?>', '<?= $task['status'] ?>')"
                                            class="cursor-pointer transition-transform hover:scale-105" title="Click to change status">
                                        <?php if ($task['status'] === 'Completed'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#dcfce7] text-[#166534]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#16a34a]"></span>
                                            Completed
                                        </span>
                                        <?php elseif ($task['status'] === 'In Progress'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#e0f2fe] text-[#075985]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#0284c7]"></span>
                                            In Progress
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#fef3c7] text-[#92400e]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>
                                            Pending
                                        </span>
                                        <?php endif; ?>
                                    </button>
                                </td>

                                <!-- Due Date -->
                                <td class="py-4 px-4 whitespace-nowrap text-on-surface-variant">
                                    <?php if (!empty($task['due_date'])): ?>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">event</span>
                                        <span><?= date('M j, Y', strtotime($task['due_date'])) ?></span>
                                    </div>
                                    <?php else: ?>
                                    <span class="text-outline">No deadline</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        
                                        <!-- Quick Status Toggle Button -->
                                        <?php if (!$isCompleted): ?>
                                        <form action="<?= BASE_URL ?>/public/actions/task-status.php" method="POST" class="m-0 inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" title="Mark as Completed"
                                                    class="w-7 h-7 rounded-lg text-emerald-700 hover:bg-emerald-50 flex items-center justify-center transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            </button>
                                        </form>
                                        <?php endif; ?>

                                        <!-- Edit Modal Trigger -->
                                        <button type="button" 
                                                onclick='openEditModal(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)'
                                                title="Edit task"
                                                class="w-7 h-7 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>

                                        <!-- Delete Modal Trigger -->
                                        <button type="button" 
                                                onclick="openDeleteModal(<?= $task['id'] ?>, '<?= e(addslashes($task['title'])) ?>')"
                                                title="Delete task"
                                                class="w-7 h-7 rounded-lg text-on-surface-variant hover:text-error hover:bg-error-container/40 flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 bg-surface-container-lowest border-t border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-on-surface-variant">
                        Showing <span class="font-semibold text-on-surface"><?= $offset + 1 ?></span> to 
                        <span class="font-semibold text-on-surface"><?= min($offset + count($tasks), $totalMatching) ?></span> of 
                        <span class="font-semibold text-on-surface"><?= $totalMatching ?></span> tasks
                    </p>

                    <?php if ($totalPages > 1): ?>
                    <div class="inline-flex items-center gap-1">
                        <?php 
                        $queryParams = $_GET;
                        $buildPageUrl = function($p) use ($queryParams) {
                            $queryParams['p'] = $p;
                            return BASE_URL . '/public/index.php?' . http_build_query($queryParams);
                        };
                        ?>
                        <!-- Prev -->
                        <?php if ($page > 1): ?>
                        <a href="<?= $buildPageUrl($page - 1) ?>" 
                           class="px-2.5 py-1.5 rounded-lg border border-outline-variant/40 text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                            <span>Prev</span>
                        </a>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= $buildPageUrl($i) ?>" 
                           class="w-8 h-8 rounded-lg text-xs font-semibold flex items-center justify-center transition-colors <?= $i === $page ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' ?>">
                            <?= $i ?>
                        </a>
                        <?php endfor; ?>

                        <!-- Next -->
                        <?php if ($page < $totalPages): ?>
                        <a href="<?= $buildPageUrl($page + 1) ?>" 
                           class="px-2.5 py-1.5 rounded-lg border border-outline-variant/40 text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors flex items-center gap-1">
                            <span>Next</span>
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>

        </main>
    </div>

    <!-- Workspace Footer -->
    <footer class="p-6 text-center text-xs text-on-surface-variant border-t border-outline-variant/20">
        <span>TaskFlow - Task tracking application</span>
    </footer>
</div>

<!-- Include Modal Dialogs -->
<?php 
require_once __DIR__ . '/components/modal-task.php';
require_once __DIR__ . '/components/modal-delete.php';
require_once __DIR__ . '/components/modal-status.php';
require_once __DIR__ . '/../layouts/footer.php'; 
?>
