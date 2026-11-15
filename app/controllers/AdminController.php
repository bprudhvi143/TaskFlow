<?php
/**
 * Administrator Controller
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Task.php';

class AdminController {
    /**
     * Admin Overview: All System Tasks across all users
     */
    public static function allTasks(): void {
        require_admin();

        $search   = trim($_GET['search'] ?? '');
        $status   = trim($_GET['status'] ?? '');
        $priority = trim($_GET['priority'] ?? '');
        $page     = max(1, (int) ($_GET['p'] ?? 1));
        $limit    = 8;
        $offset   = ($page - 1) * $limit;

        // Global metrics across entire database
        $metrics = Task::getGlobalMetrics();

        $totalMatching = Task::countAll($status, $priority, $search);
        $totalPages    = max(1, (int) ceil($totalMatching / $limit));
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $tasks = Task::getAllWithOwners($status, $priority, $search, $limit, $offset);

        require_once __DIR__ . '/../views/admin/tasks.php';
    }

    /**
     * Admin User Management Console
     */
    public static function users(): void {
        require_admin();

        $search = trim($_GET['search'] ?? '');
        $role   = trim($_GET['role'] ?? '');

        $users = User::getAllWithTaskCount($role, $search);
        foreach ($users as &$u) {
            $u['tasks'] = Task::findByUser((int)$u['id'], 100, 0);
        }
        unset($u);

        $totalUsers = User::countAll();

        require_once __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Update User Role (Exclusive Master Administrator Privilege)
     * Boddeda Prudhvi can change user <-> admin; other admins cannot.
     */
    public static function updateUserRole(): void {
        require_admin();

        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Action aborted.');
            redirect(BASE_URL . '/public/index.php?page=admin-users');
        }

        // Strict Master Admin check: only Boddeda Prudhvi can change roles
        if (!is_master_admin()) {
            set_flash('error', 'Action prohibited: Only the Master Administrator (Boddeda Prudhvi) has permission to change user access roles.');
            redirect(BASE_URL . '/public/index.php?page=admin-users');
        }

        $userId  = (int) ($_POST['user_id'] ?? 0);
        $newRole = trim($_POST['new_role'] ?? '');
        $currentUser = auth_user();

        // Prevent self-lockout: Master Admin cannot demote their own account
        if ($userId === (int) $currentUser['id']) {
            set_flash('error', 'Action prohibited: You cannot modify your own Master Administrator account role.');
            redirect(BASE_URL . '/public/index.php?page=admin-users');
        }

        $targetUser = User::findById($userId);
        if (!$targetUser) {
            set_flash('error', 'User not found in system.');
            redirect(BASE_URL . '/public/index.php?page=admin-users');
        }

        // Prevent modifying Master Admin account
        if (is_master_admin($targetUser)) {
            set_flash('error', 'Action prohibited: The Master Administrator account role cannot be changed.');
            redirect(BASE_URL . '/public/index.php?page=admin-users');
        }

        if (!in_array($newRole, ['admin', 'user'])) {
            set_flash('error', 'Invalid role selected.');
            redirect(BASE_URL . '/public/index.php?page=admin-users');
        }

        if (User::updateRole($userId, $newRole)) {
            $formattedRole = ($newRole === 'admin') ? 'Administrator' : 'Standard User';
            set_flash('success', "Access role for '{$targetUser['name']}' has been successfully changed to {$formattedRole}.");
        } else {
            set_flash('error', 'Failed to update user role in database.');
        }

        redirect(BASE_URL . '/public/index.php?page=admin-users');
    }

    /**
     * Admin Force-Delete Task
     */
    public static function deleteTask(): void {
        require_admin();

        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Action aborted.');
            redirect(BASE_URL . '/public/index.php?page=admin-tasks');
        }

        $taskId = (int) ($_POST['task_id'] ?? 0);

        if (Task::adminDelete($taskId)) {
            set_flash('success', 'Task has been removed from the system.');
        } else {
            set_flash('error', 'Failed to delete task.');
        }

        redirect(BASE_URL . '/public/index.php?page=admin-tasks');
    }
}
