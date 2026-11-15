<?php
/**
 * Administrator – All Tasks Console View
 * 1:1 match to Google Stitch screen a214d140db754ab482611acb4d8e79ff
 * RTIH TaskFlow
 */
$pageTitle = 'TaskFlow – Administrator All Tasks';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/toast.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$user = auth_user();
?>
<div class="ml-64 min-h-screen flex flex-col justify-between bg-background">
    <div class="flex-1">
        
        <!-- Header -->
        <header class="h-16 border-b border-outline-variant/30 bg-surface/80 backdrop-blur-md sticky top-0 z-20 flex items-center justify-between px-8">
            <div class="flex items-center gap-2 text-xs font-medium text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>/</span>
                <span class="text-primary font-semibold">Administrator</span>
                <span>/</span>
                <span class="text-on-surface">All System Tasks</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-primary/10 text-primary text-[11px] font-bold uppercase tracking-wider">
                    Admin Oversight Mode
                </span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-8 max-w-7xl mx-auto space-y-6">

            <!-- Title & Description -->
            <div>
                <h1 class="font-heading text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
                    System-Wide Tasks
                </h1>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Manage and inspect tasks across all registered accounts in the organization.
                </p>
            </div>

            <!-- Global Summary Metrics (Clickable Filters) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="<?= BASE_URL ?>/public/index.php?page=admin-tasks<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                   class="group p-5 rounded-xl bg-surface-container-lowest border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between <?= empty($status) ? 'border-primary ring-2 ring-primary/20 shadow-sm' : 'border-outline-variant/30 hover:border-primary/50' ?>"
                   title="View all system tasks">
                    <div class="flex items-center justify-between text-xs font-semibold text-on-surface-variant uppercase tracking-wider">
                        <span>Total Records</span>
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">database</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-on-surface"><?= $metrics['total'] ?></span>
                        <span class="text-[11px] text-on-surface-variant block mt-0.5">Across all users</span>
                    </div>
                </a>

                <a href="<?= BASE_URL ?>/public/index.php?page=admin-tasks&status=In+Progress<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                   class="group p-5 rounded-xl bg-surface-container-lowest border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between <?= $status === 'In Progress' ? 'border-sky-500 ring-2 ring-sky-500/20 shadow-sm' : 'border-outline-variant/30 hover:border-sky-400' ?>"
                   title="Filter tasks In Progress">
                    <div class="flex items-center justify-between text-xs font-semibold text-sky-800 uppercase tracking-wider">
                        <span>In Progress</span>
                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-700 group-hover:bg-sky-700 group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[20px]">pending</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-sky-900"><?= $metrics['in_progress'] ?></span>
                        <span class="text-[11px] text-sky-700 block mt-0.5">Active team workload</span>
                    </div>
                </a>

                <a href="<?= BASE_URL ?>/public/index.php?page=admin-tasks&status=Completed<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                   class="group p-5 rounded-xl bg-surface-container-lowest border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between <?= $status === 'Completed' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-sm' : 'border-outline-variant/30 hover:border-emerald-400' ?>"
                   title="Filter Completed tasks">
                    <div class="flex items-center justify-between text-xs font-semibold text-emerald-800 uppercase tracking-wider">
                        <span>Completed</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 group-hover:bg-emerald-700 group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[20px]">task_alt</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-emerald-900"><?= $metrics['completed'] ?></span>
                        <span class="text-[11px] text-emerald-700 block mt-0.5">Resolved tasks</span>
                    </div>
                </a>

                <a href="<?= BASE_URL ?>/public/index.php?page=admin-tasks&status=Pending<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                   class="group p-5 rounded-xl bg-surface-container-lowest border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between <?= $status === 'Pending' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-sm' : 'border-outline-variant/30 hover:border-amber-400' ?>"
                   title="Filter Pending tasks">
                    <div class="flex items-center justify-between text-xs font-semibold text-amber-800 uppercase tracking-wider">
                        <span>Pending</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 group-hover:bg-amber-700 group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[20px]">schedule</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-heading text-2xl sm:text-3xl font-bold text-amber-900"><?= $metrics['pending'] ?></span>
                        <span class="text-[11px] text-amber-700 block mt-0.5">Queue backlog</span>
                    </div>
                </a>
            </div>

            <!-- Filter & Search Controls -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
                <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="w-full flex-1 flex flex-col sm:flex-row items-center gap-3">
                    <input type="hidden" name="page" value="admin-tasks">

                    <!-- Search Input with Live Dropdown -->
                    <div class="relative flex-1 w-full" id="searchContainer">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" id="searchInput" name="search" value="<?= e($search) ?>"
                               autocomplete="off"
                               oninput="handleLiveSearch(this.value, 'tasks', 'all')"
                               onfocus="if (this.value.trim().length >= 1) handleLiveSearch(this.value, 'tasks', 'all')"
                               class="w-full h-10 pl-9 pr-8 bg-surface-container-low text-on-surface text-xs rounded-lg border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-all placeholder:text-outline"
                               placeholder="Search all tasks, descriptions, or user names...">
                        <button type="button" id="clearSearchBtn" onclick="clearLiveSearch('tasks')" 
                                class="absolute right-2.5 top-2.5 text-outline hover:text-on-surface <?= empty($search) ? 'hidden' : '' ?>" title="Clear search">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                        </button>

                        <!-- Live Parallel Matching Results Dropdown -->
                        <div id="liveSearchDropdown" 
                             class="absolute left-0 right-0 top-full mt-1.5 bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant/40 z-40 overflow-hidden hidden transition-all duration-150">
                        </div>
                    </div>

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

                    <button type="submit" class="px-4 h-10 bg-primary text-on-primary text-xs font-semibold rounded-lg hover:bg-primary/90 transition-colors shrink-0">
                        Filter
                    </button>

                    <?php if (!empty($search) || !empty($status) || !empty($priority)): ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=admin-tasks" 
                       class="px-3 h-10 flex items-center justify-center text-xs text-error hover:bg-error-container/30 rounded-lg transition-colors shrink-0" title="Reset filters">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- All Tasks Table -->
            <div class="rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm overflow-hidden">
                <?php if (empty($tasks)): ?>
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <span class="material-symbols-outlined text-[32px] text-on-surface-variant mb-2">inbox</span>
                    <h3 class="font-heading font-semibold text-base text-on-surface">No tasks found</h3>
                    <p class="text-xs text-on-surface-variant mt-1">No system tasks match the selected criteria.</p>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container/60 border-b border-outline-variant/30 text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">
                                <th class="py-3 px-6">Task</th>
                                <th class="py-3 px-4">Owner</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Priority</th>
                                <th class="py-3 px-4">Due Date</th>
                                <th class="py-3 px-4">Created On</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20 text-xs text-on-surface">
                            <?php foreach ($tasks as $task): 
                                $isCompleted = ($task['status'] === 'Completed');
                            ?>
                            <tr class="transition-colors <?= $isCompleted ? 'bg-emerald-50/25 hover:bg-emerald-50/40 border-l-4 border-l-emerald-500' : 'hover:bg-surface-container-low/50' ?>">
                                <!-- Task (Clickable to view full details popup) -->
                                <td class="py-4 px-6 max-w-xs cursor-pointer group/task"
                                    onclick='openTaskDetailsModal(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)'
                                    title="Click to view full task details">
                                    <div class="flex items-center gap-2">
                                        <?php if ($isCompleted): ?>
                                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                        </span>
                                        <?php endif; ?>
                                        <span class="font-semibold text-on-surface text-sm block leading-snug group-hover/task:text-primary transition-colors flex items-center gap-1">
                                            <?= e($task['title']) ?>
                                            <span class="material-symbols-outlined text-[14px] text-outline opacity-0 group-hover/task:opacity-100 transition-opacity">info</span>
                                        </span>
                                    </div>
                                    <?php if (!empty($task['description'])): ?>
                                    <span class="text-xs text-on-surface-variant mt-0.5 line-clamp-1 block <?= $isCompleted ? 'pl-7' : '' ?>">
                                        <?= e($task['description']) ?>
                                    </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Owner -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0">
                                            <?= strtoupper(substr($task['owner_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-on-surface block leading-tight"><?= e($task['owner_name']) ?></span>
                                            <span class="text-[10px] text-on-surface-variant block"><?= e($task['owner_email']) ?></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 whitespace-nowrap">
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
                                </td>

                                <!-- Priority -->
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

                                <!-- Due Date -->
                                <td class="py-4 px-4 whitespace-nowrap text-on-surface-variant">
                                    <?= !empty($task['due_date']) ? date('M j, Y', strtotime($task['due_date'])) : '—' ?>
                                </td>

                                <!-- Created On -->
                                <td class="py-4 px-4 whitespace-nowrap text-on-surface-variant text-[11px]">
                                    <?= date('M j, Y H:i', strtotime($task['created_at'])) ?>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <form action="<?= BASE_URL ?>/public/actions/admin-role.php?action=delete-task" method="POST" class="m-0 inline"
                                          onsubmit="return confirm('Admin Confirmation: Permanently delete this task across the system?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                        <button type="submit" title="Admin Remove Task"
                                                class="w-7 h-7 rounded-lg text-on-surface-variant hover:text-error hover:bg-error-container/40 inline-flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
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
                        <span class="font-semibold text-on-surface"><?= $totalMatching ?></span> records
                    </p>

                    <?php if ($totalPages > 1): ?>
                    <div class="inline-flex items-center gap-1">
                        <?php 
                        $queryParams = $_GET;
                        $buildAdminPageUrl = function($p) use ($queryParams) {
                            $queryParams['p'] = $p;
                            return BASE_URL . '/public/index.php?' . http_build_query($queryParams);
                        };
                        ?>
                        <?php if ($page > 1): ?>
                        <a href="<?= $buildAdminPageUrl($page - 1) ?>" 
                           class="px-2.5 py-1.5 rounded-lg border border-outline-variant/40 text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                            <span>Prev</span>
                        </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= $buildAdminPageUrl($i) ?>" 
                           class="w-8 h-8 rounded-lg text-xs font-semibold flex items-center justify-center transition-colors <?= $i === $page ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' ?>">
                            <?= $i ?>
                        </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <a href="<?= $buildAdminPageUrl($page + 1) ?>" 
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

    <footer class="p-6 text-center text-xs text-on-surface-variant border-t border-outline-variant/20">
        <span>TaskFlow – Task tracking application</span>
    </footer>
</div>

<?php 
require_once __DIR__ . '/../tasks/components/modal-task-details.php';
require_once __DIR__ . '/../layouts/footer.php'; 
?>
