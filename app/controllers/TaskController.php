<?php
/**
 * Task Controller
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Task.php';

class TaskController {
    /**
     * User Dashboard View
     */
    public static function index(): void {
        require_auth();
        $user = auth_user();
        $userId = (int) $user['id'];

        $search   = trim($_GET['search'] ?? '');
        $status   = trim($_GET['status'] ?? '');
        $priority = trim($_GET['priority'] ?? '');
        $page     = max(1, (int) ($_GET['p'] ?? 1));
        $limit    = 6;
        $offset   = ($page - 1) * $limit;

        // Metric counters
        $metrics = Task::getMetricsByUser($userId);

        // Filtered tasks and pagination count
        $totalMatching = Task::countByUser($userId, $status, $priority, $search);
        $totalPages    = max(1, (int) ceil($totalMatching / $limit));
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $tasks = Task::findByUser($userId, $status, $priority, $search, $limit, $offset);

        require_once __DIR__ . '/../views/tasks/index.php';
    }

    /**
     * Create New Task
     */
    public static function create(): void {
        require_auth();
        $user = auth_user();

        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Submission rejected.');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $title       = trim($_POST['task_title'] ?? '');
        $description = trim($_POST['task_description'] ?? '');
        $status      = trim($_POST['status'] ?? 'Pending');
        $priority    = trim($_POST['priority'] ?? 'Medium');
        $dueDate     = trim($_POST['due_date'] ?? '');

        // Validation
        $errors = [];
        if (empty($title)) {
            $errors[] = 'Task title is required and cannot be empty.';
        } elseif (mb_strlen($title) > 150) {
            $errors[] = 'Task title exceeds maximum allowed length of 150 characters (' . mb_strlen($title) . '/150).';
        }

        if (!in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            $status = 'Pending';
        }

        if (!in_array($priority, ['Low', 'Medium', 'High'])) {
            $priority = 'Medium';
        }

        if (!empty($dueDate)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)) {
                $errors[] = 'Invalid due date format. Please select a valid calendar date.';
            } elseif ($dueDate < date('Y-m-d')) {
                $errors[] = 'Task deadline must be today or a future date. Past dates cannot be set as a deadline.';
            }
        }

        if (!empty($errors)) {
            set_flash('error', implode(' ', $errors));
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $taskId = Task::create(
            $user['id'],
            $title,
            $description ?: null,
            $status,
            $priority,
            $dueDate ?: null
        );

        if ($taskId > 0) {
            set_flash('success', 'Task created successfully. Your task has been added to the list.');
        } else {
            set_flash('error', 'Failed to create task. Please try again.');
        }

        redirect(BASE_URL . '/public/index.php?page=dashboard');
    }

    /**
     * Update Existing Task
     */
    public static function update(): void {
        require_auth();
        $user = auth_user();

        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Submission rejected.');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $taskId      = (int) ($_POST['task_id'] ?? 0);
        $title       = trim($_POST['task_title'] ?? '');
        $description = trim($_POST['task_description'] ?? '');
        $status      = trim($_POST['status'] ?? 'Pending');
        $priority    = trim($_POST['priority'] ?? 'Medium');
        $dueDate     = trim($_POST['due_date'] ?? '');

        // Strict Ownership Enforcement: Verify task belongs to current user
        $existing = Task::findByIdAndUser($taskId, $user['id']);
        if (!$existing) {
            http_response_code(403);
            set_flash('error', 'Unauthorized: You do not have permission to modify this task.');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        // Validation
        $errors = [];
        if (empty($title)) {
            $errors[] = 'Task title is required.';
        } elseif (mb_strlen($title) > 150) {
            $errors[] = 'Task title exceeds maximum allowed length of 150 characters.';
        }

        if (!in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            $status = $existing['status'];
        }

        if (!in_array($priority, ['Low', 'Medium', 'High'])) {
            $priority = $existing['priority'];
        }

        if (!empty($dueDate)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)) {
                $errors[] = 'Invalid due date format. Please select a valid calendar date.';
            } elseif ($dueDate !== $existing['due_date'] && $dueDate < date('Y-m-d')) {
                $errors[] = 'Task deadline must be today or a future date. Past dates cannot be set as a deadline.';
            }
        }

        if (!empty($errors)) {
            set_flash('error', implode(' ', $errors));
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $updated = Task::update(
            $taskId,
            $user['id'],
            $title,
            $description ?: null,
            $status,
            $priority,
            $dueDate ?: null
        );

        if ($updated) {
            set_flash('success', 'Task updated successfully.');
        } else {
            set_flash('error', 'No changes were saved or update failed.');
        }

        redirect(BASE_URL . '/public/index.php?page=dashboard');
    }

    /**
     * Inline Status Quick Toggle (e.g. Mark Complete)
     */
    public static function updateStatus(): void {
        require_auth();
        $user = auth_user();

        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF).');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $taskId = (int) ($_POST['task_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        // Strict ownership check
        $existing = Task::findByIdAndUser($taskId, $user['id']);
        if (!$existing) {
            http_response_code(403);
            set_flash('error', 'Unauthorized: Cannot modify task.');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        if (Task::updateStatus($taskId, $user['id'], $status)) {
            set_flash('success', "Task status updated to {$status}.");
        } else {
            set_flash('error', 'Failed to update task status.');
        }

        redirect(BASE_URL . '/public/index.php?page=dashboard');
    }

    /**
     * Delete Task with Confirmation
     */
    public static function delete(): void {
        require_auth();
        $user = auth_user();

        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF).');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $taskId = (int) ($_POST['task_id'] ?? 0);

        // Strict Ownership Enforcement: Verify task belongs to current user
        $existing = Task::findByIdAndUser($taskId, $user['id']);
        if (!$existing) {
            http_response_code(403);
            set_flash('error', 'Unauthorized: You do not have permission to delete this task.');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        if (Task::delete($taskId, $user['id'])) {
            set_flash('success', 'Task has been permanently deleted.');
        } else {
            set_flash('error', 'Failed to delete task.');
        }

        redirect(BASE_URL . '/public/index.php?page=dashboard');
    }
}
