<?php
/**
 * Task Model
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/database.php';

class Task {
    /**
     * Get paginated tasks for a specific user with optional search and filters
     */
    public static function findByUser(
        int $userId,
        ?string $status = null,
        ?string $priority = null,
        ?string $search = null,
        int $limit = 10,
        int $offset = 0
    ): array {
        $sql = "SELECT * FROM tasks WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if (!empty($status) && in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        if (!empty($priority) && in_array($priority, ['Low', 'Medium', 'High'])) {
            $sql .= " AND priority = :priority";
            $params['priority'] = $priority;
        }

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search_title OR description LIKE :search_desc)";
            $params['search_title'] = '%' . trim($search) . '%';
            $params['search_desc'] = '%' . trim($search) . '%';
        }

        // Completed tasks sink to the bottom, active tasks first
        $sql .= " ORDER BY (status = 'Completed') ASC, created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = db()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Count total tasks for a specific user matching active filters (for pagination)
     */
    public static function countByUser(
        int $userId,
        ?string $status = null,
        ?string $priority = null,
        ?string $search = null
    ): int {
        $sql = "SELECT COUNT(*) FROM tasks WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if (!empty($status) && in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        if (!empty($priority) && in_array($priority, ['Low', 'Medium', 'High'])) {
            $sql .= " AND priority = :priority";
            $params['priority'] = $priority;
        }

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search_title OR description LIKE :search_desc)";
            $params['search_title'] = '%' . trim($search) . '%';
            $params['search_desc'] = '%' . trim($search) . '%';
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get summary metric counters for user dashboard tiles
     */
    public static function getMetricsByUser(int $userId): array {
        $stmt = db()->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed
            FROM tasks
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch() ?: [];

        return [
            'total'       => (int) ($row['total'] ?? 0),
            'pending'     => (int) ($row['pending'] ?? 0),
            'in_progress' => (int) ($row['in_progress'] ?? 0),
            'completed'   => (int) ($row['completed'] ?? 0)
        ];
    }

    /**
     * Find a task strictly ensuring user ownership
     */
    public static function findByIdAndUser(int $id, int $userId): ?array {
        $stmt = db()->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $task = $stmt->fetch();
        return $task ?: null;
    }

    /**
     * Create a new task
     */
    public static function create(
        int $userId,
        string $title,
        ?string $description,
        string $status = 'Pending',
        string $priority = 'Medium',
        ?string $dueDate = null
    ): int {
        $stmt = db()->prepare("
            INSERT INTO tasks (user_id, title, description, status, priority, due_date)
            VALUES (:user_id, :title, :description, :status, :priority, :due_date)
        ");
        $stmt->execute([
            'user_id'     => $userId,
            'title'       => trim($title),
            'description' => $description !== null ? trim($description) : null,
            'status'      => in_array($status, ['Pending', 'In Progress', 'Completed']) ? $status : 'Pending',
            'priority'    => in_array($priority, ['Low', 'Medium', 'High']) ? $priority : 'Medium',
            'due_date'    => !empty($dueDate) ? $dueDate : null
        ]);

        return (int) db()->lastInsertId();
    }

    /**
     * Update an existing task strictly scoped to owner
     */
    public static function update(
        int $id,
        int $userId,
        string $title,
        ?string $description,
        string $status,
        string $priority,
        ?string $dueDate
    ): bool {
        $stmt = db()->prepare("
            UPDATE tasks
            SET title = :title,
                description = :description,
                status = :status,
                priority = :priority,
                due_date = :due_date
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([
            'id'          => $id,
            'user_id'     => $userId,
            'title'       => trim($title),
            'description' => $description !== null ? trim($description) : null,
            'status'      => in_array($status, ['Pending', 'In Progress', 'Completed']) ? $status : 'Pending',
            'priority'    => in_array($priority, ['Low', 'Medium', 'High']) ? $priority : 'Medium',
            'due_date'    => !empty($dueDate) ? $dueDate : null
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Fast status toggle strictly scoped to owner
     */
    public static function updateStatus(int $id, int $userId, string $status): bool {
        if (!in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            return false;
        }
        $stmt = db()->prepare("UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            'status'  => $status,
            'id'      => $id,
            'user_id' => $userId
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete task strictly enforcing ownership
     */
    public static function delete(int $id, int $userId): bool {
        $stmt = db()->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    // ----------------------------------------------------
    // Administrative Operations (Cross-User Access)
    // ----------------------------------------------------

    /**
     * Retrieve all tasks system-wide with user details (Admin view)
     */
    public static function getAllWithOwners(
        ?string $status = null,
        ?string $priority = null,
        ?string $search = null,
        int $limit = 10,
        int $offset = 0
    ): array {
        $sql = "SELECT t.*, u.name AS owner_name, u.email AS owner_email, u.role AS owner_role
                FROM tasks t
                JOIN users u ON t.user_id = u.id";
        $params = [];
        $conditions = [];

        if (!empty($status) && in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            $conditions[] = "t.status = :status";
            $params['status'] = $status;
        }

        if (!empty($priority) && in_array($priority, ['Low', 'Medium', 'High'])) {
            $conditions[] = "t.priority = :priority";
            $params['priority'] = $priority;
        }

        if (!empty($search)) {
            $conditions[] = "(t.title LIKE :s_title OR t.description LIKE :s_desc OR u.name LIKE :s_name OR u.email LIKE :s_email)";
            $params['s_title'] = '%' . trim($search) . '%';
            $params['s_desc']  = '%' . trim($search) . '%';
            $params['s_name']  = '%' . trim($search) . '%';
            $params['s_email'] = '%' . trim($search) . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Completed tasks sink to the bottom, active tasks first
        $sql .= " ORDER BY (t.status = 'Completed') ASC, t.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = db()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Count all tasks across all users matching filters (Admin pagination)
     */
    public static function countAll(
        ?string $status = null,
        ?string $priority = null,
        ?string $search = null
    ): int {
        $sql = "SELECT COUNT(*) FROM tasks t JOIN users u ON t.user_id = u.id";
        $params = [];
        $conditions = [];

        if (!empty($status) && in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            $conditions[] = "t.status = :status";
            $params['status'] = $status;
        }

        if (!empty($priority) && in_array($priority, ['Low', 'Medium', 'High'])) {
            $conditions[] = "t.priority = :priority";
            $params['priority'] = $priority;
        }

        if (!empty($search)) {
            $conditions[] = "(t.title LIKE :s_title OR t.description LIKE :s_desc OR u.name LIKE :s_name OR u.email LIKE :s_email)";
            $params['s_title'] = '%' . trim($search) . '%';
            $params['s_desc']  = '%' . trim($search) . '%';
            $params['s_name']  = '%' . trim($search) . '%';
            $params['s_email'] = '%' . trim($search) . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Global system metric counters for Admin dashboard
     */
    public static function getGlobalMetrics(): array {
        $row = db()->query("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed
            FROM tasks
        ")->fetch() ?: [];

        return [
            'total'       => (int) ($row['total'] ?? 0),
            'pending'     => (int) ($row['pending'] ?? 0),
            'in_progress' => (int) ($row['in_progress'] ?? 0),
            'completed'   => (int) ($row['completed'] ?? 0)
        ];
    }

    /**
     * Admin delete of any task
     */
    public static function adminDelete(int $id): bool {
        $stmt = db()->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
