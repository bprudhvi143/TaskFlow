<?php
/**
 * Administrator – User Management Console View
 * 1:1 match to Google Stitch screen bb2c187ad5e645ad994858747ca28e23
 * RTIH TaskFlow
 */
$pageTitle = 'TaskFlow – Administrator User Management';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/toast.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$currentUser = auth_user();
$isMasterAdmin = is_master_admin($currentUser);
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
                <span class="text-on-surface">User Management</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-primary/10 text-primary text-[11px] font-bold uppercase tracking-wider">
                    Total Users: <?= count($users) ?>
                </span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-8 max-w-7xl mx-auto space-y-6">

            <!-- Title & Subtitle -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="font-heading text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
                            User Directory & Access Control
                        </h1>
                        <?php if ($isMasterAdmin): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-800 to-teal-900 text-white shadow-xs">Master Authority</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                        View registered users, monitor task activity, and configure administrative role permissions.
                        <?php if ($isMasterAdmin): ?>
                        <span class="inline-flex items-center gap-1 font-semibold text-primary ml-1">
                            <span class="material-symbols-outlined text-[14px]">verified_user</span>
                            Master Admin Mode (You can promote users to admin or demote admins to user)
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-on-surface-variant font-medium ml-1">
                            <span class="material-symbols-outlined text-[14px]">lock</span>
                            Role changes restricted exclusively to Master Admin (Boddeda Prudhvi)
                        </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Search & Role Filter Toolbar -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
                <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="w-full flex-1 flex flex-col sm:flex-row items-center gap-3">
                    <input type="hidden" name="page" value="admin-users">

                    <!-- Search Input with Live Dropdown -->
                    <div class="relative flex-1 w-full" id="searchContainer">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" id="searchInput" name="search" value="<?= e($search) ?>"
                               autocomplete="off"
                               oninput="handleLiveSearch(this.value, 'users')"
                               onfocus="if (this.value.trim().length >= 1) handleLiveSearch(this.value, 'users')"
                               class="w-full h-10 pl-9 pr-8 bg-surface-container-low text-on-surface text-xs rounded-lg border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-all placeholder:text-outline"
                               placeholder="Search users by name or email address...">
                        <button type="button" id="clearSearchBtn" onclick="clearLiveSearch('users')" 
                                class="absolute right-2.5 top-2.5 text-outline hover:text-on-surface <?= empty($search) ? 'hidden' : '' ?>" title="Clear search">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                        </button>

                        <!-- Live Parallel Matching Results Dropdown -->
                        <div id="liveSearchDropdown" 
                             class="absolute left-0 right-0 top-full mt-1.5 bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant/40 z-40 overflow-hidden hidden transition-all duration-150">
                        </div>
                    </div>

                    <div class="relative w-full sm:w-48">
                        <select name="role" onchange="this.form.submit()"
                                class="w-full h-10 pl-3 pr-8 bg-surface-container-low text-on-surface text-xs rounded-lg border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none appearance-none cursor-pointer transition-all">
                            <option value="">All Roles</option>
                            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrator</option>
                            <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Standard User</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-2.5 pointer-events-none text-on-surface-variant text-[18px]">expand_more</span>
                    </div>

                    <button type="submit" class="px-4 h-10 bg-primary text-on-primary text-xs font-semibold rounded-lg hover:bg-primary/90 transition-colors shrink-0">
                        Filter
                    </button>

                    <?php if (!empty($search) || !empty($role)): ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=admin-users" 
                       class="px-3 h-10 flex items-center justify-center text-xs text-error hover:bg-error-container/30 rounded-lg transition-colors shrink-0" title="Reset filter">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Users Table -->
            <div class="rounded-xl bg-surface-container-lowest border border-outline-variant/30 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container/60 border-b border-outline-variant/30 text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">
                                <th class="py-3 px-6">User Identity</th>
                                <th class="py-3 px-4">Role</th>
                                <th class="py-3 px-4">Active Workload</th>
                                <th class="py-3 px-4">Joined Date</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20 text-xs text-on-surface">
                            <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <!-- Identity (Clickable to view tasks) -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex items-center gap-3 cursor-pointer group"
                                         onclick='openUserTasksModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)'
                                         title="Click to view <?= e($u['name']) ?>'s task details">
                                        <div class="w-9 h-9 rounded-xl bg-surface-container text-primary font-bold text-sm flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-on-primary transition-colors">
                                            <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-on-surface text-sm block leading-tight group-hover:text-primary transition-colors">
                                                <?= e($u['name']) ?>
                                                <?php if (is_master_admin($u)): ?>
                                                <span class="ml-1 text-[10px] bg-gradient-to-r from-emerald-800 to-teal-900 text-white px-2 py-0.5 rounded font-bold tracking-wide shadow-xs">MASTER</span>
                                                <?php elseif ((int)$u['id'] === (int)$currentUser['id']): ?>
                                                <span class="ml-1 text-[10px] bg-primary/10 text-primary px-1.5 py-0.5 rounded font-normal">(You)</span>
                                                <?php endif; ?>
                                            </span>
                                            <span class="text-xs text-on-surface-variant block mt-0.5"><?= e($u['email']) ?></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role Badge -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <?php if (is_master_admin($u)): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-gradient-to-r from-emerald-800 to-teal-900 text-white shadow-xs">
                                        <span class="material-symbols-outlined text-[14px]">shield_with_heart</span>
                                        Master Admin
                                    </span>
                                    <?php elseif ($u['role'] === 'admin'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-primary text-on-primary">
                                        <span class="material-symbols-outlined text-[14px]">shield_person</span>
                                        Administrator
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant border border-outline-variant/40">
                                        <span class="material-symbols-outlined text-[14px]">person</span>
                                        Standard User
                                    </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Workload Metrics (Clickable to view tasks) -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <button type="button" 
                                            onclick='openUserTasksModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)'
                                            class="flex items-center gap-2 hover:opacity-80 transition-opacity text-left"
                                            title="Click to inspect task list">
                                        <span class="font-semibold text-on-surface text-xs bg-surface-container px-2 py-0.5 rounded"><?= $u['total_tasks'] ?> total</span>
                                        <span class="text-[11px] text-sky-800 bg-sky-50 px-2 py-0.5 rounded font-medium">
                                            <?= $u['in_progress_tasks'] ?? 0 ?> in progress
                                        </span>
                                        <span class="text-[11px] text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded font-medium">
                                            <?= $u['completed_tasks'] ?? 0 ?> done
                                        </span>
                                    </button>
                                </td>

                                <!-- Joined Date -->
                                <td class="py-4 px-4 whitespace-nowrap text-on-surface-variant text-[11px]">
                                    <?= date('M j, Y', strtotime($u['created_at'])) ?>
                                </td>

                                <!-- Actions: View Tasks & Change Role (Only Master Admin can change roles) -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <button type="button" 
                                                onclick='openUserTasksModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)'
                                                class="px-2.5 py-1.5 rounded-lg bg-primary/10 hover:bg-primary text-primary hover:text-on-primary text-xs font-semibold inline-flex items-center gap-1 transition-all"
                                                title="View user's task details">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            <span>Tasks</span>
                                        </button>

                                        <?php if ($isMasterAdmin): ?>
                                            <?php if (is_master_admin($u)): ?>
                                            <span class="px-2.5 py-1.5 rounded-lg bg-surface-container-low text-on-surface-variant/50 text-xs font-medium inline-flex items-center gap-1 cursor-default" title="Master Administrator account is permanent and cannot be modified">
                                                <span class="material-symbols-outlined text-[15px]">lock</span>
                                                <span>Protected</span>
                                            </span>
                                            <?php else: ?>
                                            <button type="button" 
                                                    onclick='openRoleModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)'
                                                    class="px-2.5 py-1.5 rounded-lg border border-primary/30 bg-primary/5 hover:bg-primary text-primary hover:text-on-primary text-xs font-semibold inline-flex items-center gap-1 transition-all shadow-xs"
                                                    title="<?= $u['role'] === 'admin' ? 'Change Admin to User' : 'Change User to Admin' ?>">
                                                <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                                                <span>Change Role</span>
                                            </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="px-2 py-1 rounded text-[11px] text-on-surface-variant/60 font-medium inline-flex items-center gap-1" title="Only Master Admin (Boddeda Prudhvi) can change user roles">
                                                <span class="material-symbols-outlined text-[14px]">lock</span>
                                                <span>Master Only</span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <footer class="p-6 text-center text-xs text-on-surface-variant border-t border-outline-variant/20">
        <span>TaskFlow – Task tracking application</span>
    </footer>
</div>

<?php if ($isMasterAdmin): ?>
<!-- Change User Role Modal (Exclusive Master Administrator Control) -->
<div id="roleModalBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-inverse-surface/40 backdrop-blur-sm transition-all duration-200 hidden opacity-0"
     role="dialog" aria-modal="true">
    
    <div class="w-full max-w-md bg-surface-container-lowest rounded-xl shadow-2xl p-6 relative overflow-hidden transition-all transform scale-95" id="roleModalCard">
        
        <div class="flex items-start justify-between pb-3 border-b border-outline-variant/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-emerald-800 to-teal-900 text-white flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
                </div>
                <div>
                    <span class="text-[11px] text-emerald-800 uppercase font-bold tracking-wider flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">shield_with_heart</span>
                        Master Admin Authorization
                    </span>
                    <h3 class="font-heading text-lg font-bold text-on-surface">Change User Role</h3>
                </div>
            </div>
            <button type="button" onclick="closeRoleModal()" class="text-outline hover:text-on-surface p-1">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="<?= BASE_URL ?>/public/actions/admin-role.php?action=update-role" method="POST" class="mt-4 flex flex-col gap-4">
            <?= csrf_field() ?>
            <input type="hidden" name="user_id" id="role_user_id" value="">

            <p class="text-xs text-on-surface-variant leading-relaxed">
                As Master Administrator, you have exclusive privilege to change a user to administrator or demote an administrator to standard user.
            </p>

            <!-- Target User Banner -->
            <div class="p-3 rounded-lg bg-surface-container-low flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center" id="roleUserInitial">
                    U
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-on-surface truncate" id="roleUserName">User Name</p>
                    <p class="text-[11px] text-on-surface-variant truncate" id="roleUserEmail">user@email.com</p>
                </div>
            </div>

            <!-- Role Select -->
            <div class="flex flex-col gap-1.5">
                <label for="new_role" class="text-xs font-semibold text-on-surface uppercase tracking-wider">Assign Role</label>
                <div class="relative flex items-center">
                    <select name="new_role" id="new_role"
                            class="w-full pl-3.5 pr-10 py-2.5 bg-surface-container-low text-on-surface text-sm rounded-lg border border-outline-variant/30 focus:border-primary focus:bg-surface-container-lowest outline-none appearance-none cursor-pointer">
                        <option value="user">Standard User (Restricted to personal workspace)</option>
                        <option value="admin">Administrator (Global task and user directory oversight)</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-outline-variant/30">
                <button type="button" onclick="closeRoleModal()"
                        class="px-4 py-2.5 rounded-lg text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary/90 text-on-primary text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Update Role</span>
                </button>
            </div>
        </form>

    </div>
</div>
<?php endif; ?>

<!-- User Tasks Inspection Modal -->
<div id="userTasksModalBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-inverse-surface/40 backdrop-blur-sm transition-all duration-200 hidden opacity-0"
     role="dialog" aria-modal="true">
    
    <div class="w-full max-w-2xl bg-surface-container-lowest rounded-2xl shadow-2xl p-6 relative overflow-hidden transition-all transform scale-95 flex flex-col max-h-[85vh]" id="userTasksModalCard">
        
        <!-- Modal Header -->
        <div class="flex items-start justify-between pb-4 border-b border-outline-variant/30">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary font-bold text-base flex items-center justify-center shrink-0" id="userTaskModalInitial">
                    U
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-heading text-lg font-bold text-on-surface" id="userTaskModalName">User Tasks</h3>
                        <span id="userTaskModalRoleBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-surface-container text-on-surface-variant">User</span>
                    </div>
                    <p class="text-xs text-on-surface-variant" id="userTaskModalEmail">user@email.com</p>
                </div>
            </div>
            <button type="button" onclick="closeUserTasksModal()" class="text-outline hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Summary Metric Row -->
        <div class="py-3 px-1 flex flex-wrap items-center gap-2 border-b border-outline-variant/20 text-xs">
            <span class="font-semibold text-on-surface-variant uppercase text-[10px] tracking-wider mr-1">Workload:</span>
            <span id="userTaskSummaryTotal" class="px-2.5 py-1 rounded-md bg-surface-container text-on-surface font-semibold text-xs">0 Total</span>
            <span id="userTaskSummaryInProgress" class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-800 font-medium text-xs">0 In Progress</span>
            <span id="userTaskSummaryCompleted" class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 font-medium text-xs">0 Done</span>
            <span id="userTaskSummaryPending" class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 font-medium text-xs">0 Pending</span>
        </div>

        <!-- Task List Content Area -->
        <div class="flex-1 overflow-y-auto py-3 space-y-2.5 pr-1" id="userTasksModalList">
            <!-- Dynamically populated via openUserTasksModal() -->
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between pt-3 border-t border-outline-variant/30 mt-2">
            <a id="userTasksAllConsoleLink" href="#"
               class="px-3.5 py-2 rounded-lg bg-surface-container-high hover:bg-primary hover:text-on-primary text-xs font-semibold inline-flex items-center gap-1.5 transition-colors">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Open in All Tasks Console</span>
            </a>

            <button type="button" onclick="closeUserTasksModal()"
                    class="px-4 py-2 rounded-lg text-xs font-semibold text-on-surface bg-surface-container hover:bg-surface-container-high transition-colors">
                Close
            </button>
        </div>

    </div>
</div>

<script>
function openRoleModal(userData) {
    document.getElementById('role_user_id').value = userData.id;
    document.getElementById('roleUserName').textContent = userData.name;
    document.getElementById('roleUserEmail').textContent = userData.email;
    document.getElementById('roleUserInitial').textContent = userData.name.charAt(0).toUpperCase();
    document.getElementById('new_role').value = userData.role;

    const backdrop = document.getElementById('roleModalBackdrop');
    const card = document.getElementById('roleModalCard');
    backdrop.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }, 10);
}

function closeRoleModal() {
    const backdrop = document.getElementById('roleModalBackdrop');
    const card = document.getElementById('roleModalCard');
    backdrop.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 200);
}

function openUserTasksModal(userData) {
    document.getElementById('userTaskModalName').textContent = userData.name;
    document.getElementById('userTaskModalEmail').textContent = userData.email;
    document.getElementById('userTaskModalInitial').textContent = userData.name.charAt(0).toUpperCase();
    
    // Role badge
    const roleBadge = document.getElementById('userTaskModalRoleBadge');
    if (userData.email && userData.email.toLowerCase() === 'prudhviboddeda5@gmail.com') {
        roleBadge.textContent = 'Master Admin';
        roleBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-800 to-teal-900 text-white';
    } else if (userData.role === 'admin') {
        roleBadge.textContent = 'Administrator';
        roleBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary text-on-primary';
    } else {
        roleBadge.textContent = 'Standard User';
        roleBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-surface-container text-on-surface-variant border border-outline-variant/40';
    }

    const tasks = userData.tasks || [];
    const inProgressCount = tasks.filter(t => t.status === 'In Progress').length;
    const completedCount = tasks.filter(t => t.status === 'Completed').length;
    const pendingCount = tasks.filter(t => t.status === 'Pending').length;

    document.getElementById('userTaskSummaryTotal').textContent = `${tasks.length} Total`;
    document.getElementById('userTaskSummaryInProgress').textContent = `${inProgressCount} In Progress`;
    document.getElementById('userTaskSummaryCompleted').textContent = `${completedCount} Done`;
    document.getElementById('userTaskSummaryPending').textContent = `${pendingCount} Pending`;

    // Console link
    document.getElementById('userTasksAllConsoleLink').href = '<?= BASE_URL ?>/public/index.php?page=admin-tasks&search=' + encodeURIComponent(userData.name);

    // Render task items
    const listEl = document.getElementById('userTasksModalList');
    listEl.innerHTML = '';

    if (tasks.length === 0) {
        listEl.innerHTML = `
            <div class="py-12 text-center flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-[36px] text-on-surface-variant/50 mb-2">inbox</span>
                <p class="font-heading font-semibold text-sm text-on-surface">No tasks recorded</p>
                <p class="text-xs text-on-surface-variant mt-1">This user currently has no personal tasks assigned or created.</p>
            </div>
        `;
    } else {
        tasks.forEach(t => {
            const isCompleted = (t.status === 'Completed');
            let statusBadge = '';
            if (t.status === 'Completed') {
                statusBadge = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#dcfce7] text-[#166534]"><span class="w-1.5 h-1.5 rounded-full bg-[#16a34a]"></span>Completed</span>';
            } else if (t.status === 'In Progress') {
                statusBadge = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#e0f2fe] text-[#075985]"><span class="w-1.5 h-1.5 rounded-full bg-[#0284c7]"></span>In Progress</span>';
            } else {
                statusBadge = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#fef3c7] text-[#92400e]"><span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Pending</span>';
            }

            let priorityBadge = '';
            if (t.priority === 'High') {
                priorityBadge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">High</span>';
            } else if (t.priority === 'Medium') {
                priorityBadge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-orange-100 text-orange-800">Medium</span>';
            } else {
                priorityBadge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-800">Low</span>';
            }

            const dueDate = t.due_date ? `<span class="inline-flex items-center gap-1 text-[11px] text-on-surface-variant"><span class="material-symbols-outlined text-[13px]">event</span>${t.due_date}</span>` : '<span class="text-[11px] text-outline">No date</span>';

            const card = document.createElement('div');
            card.className = `p-3.5 rounded-xl border transition-all ${isCompleted ? 'bg-emerald-50/20 border-emerald-300' : 'bg-surface-container-low border-outline-variant/30 hover:border-primary/40'}`;
            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            ${isCompleted ? '<span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[12px]">check</span></span>' : ''}
                            <h4 class="font-semibold text-xs sm:text-sm text-on-surface leading-snug">${escapeHtml(t.title)}</h4>
                        </div>
                        ${t.description ? `<p class="text-xs text-on-surface-variant mt-1 ${isCompleted ? 'pl-6' : ''} line-clamp-2">${escapeHtml(t.description)}</p>` : ''}
                    </div>
                    <div class="flex flex-col items-end gap-1.5 shrink-0">
                        ${statusBadge}
                        <div class="flex items-center gap-1.5">
                            ${priorityBadge}
                            ${dueDate}
                        </div>
                    </div>
                </div>
            `;
            listEl.appendChild(card);
        });
    }

    const backdrop = document.getElementById('userTasksModalBackdrop');
    const card = document.getElementById('userTasksModalCard');
    backdrop.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }, 10);
}

function closeUserTasksModal() {
    const backdrop = document.getElementById('userTasksModalBackdrop');
    const card = document.getElementById('userTasksModalCard');
    backdrop.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 200);
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRoleModal();
        closeUserTasksModal();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
