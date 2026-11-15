<?php
/**
 * User Model
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/database.php';

class User {
    /**
     * Find a single user by their email address
     */
    public static function findByEmail(string $email): ?array {
        $stmt = db()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => strtolower(trim($email))]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find a single user by their ID
     */
    public static function findById(int $id): ?array {
        $stmt = db()->prepare("SELECT id, name, email, role, created_at, updated_at FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Create a new user with securely hashed password
     */
    public static function create(string $name, string $email, string $password, string $role = 'user'): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = db()->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        $stmt->execute([
            'name'     => trim($name),
            'email'    => strtolower(trim($email)),
            'password' => $hash,
            'role'     => in_array($role, ['admin', 'user']) ? $role : 'user'
        ]);
        return (int) db()->lastInsertId();
    }

    /**
     * Get all users with task counts for Administrator console
     */
    public static function getAllWithTaskCount(?string $role = null, ?string $search = null): array {
        $sql = "SELECT u.id, u.name, u.email, u.role, u.created_at,
                       COUNT(t.id) AS total_tasks,
                       SUM(CASE WHEN t.status = 'Completed' THEN 1 ELSE 0 END) AS completed_tasks,
                       SUM(CASE WHEN t.status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress_tasks
                FROM users u
                LEFT JOIN tasks t ON u.id = t.user_id";

        $conditions = [];
        $params = [];

        if (!empty($role) && in_array(strtolower($role), ['admin', 'user'])) {
            $conditions[] = "u.role = :role";
            $params['role'] = strtolower($role);
        }

        if (!empty($search)) {
            $conditions[] = "(u.name LIKE :search_name OR u.email LIKE :search_email)";
            $params['search_name'] = '%' . trim($search) . '%';
            $params['search_email'] = '%' . trim($search) . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " GROUP BY u.id, u.name, u.email, u.role, u.created_at ORDER BY u.created_at DESC";

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Update user role (Admin only)
     */
    public static function updateRole(int $id, string $newRole): bool {
        if (!in_array($newRole, ['admin', 'user'])) {
            return false;
        }
        $stmt = db()->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute(['role' => $newRole, 'id' => $id]);
    }

    /**
     * Update user password securely with bcrypt
     */
    public static function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = db()->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['password' => $hash, 'id' => $id]);
    }

    /**
     * Update user password by email
     */
    public static function updatePasswordByEmail(string $email, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = db()->prepare("UPDATE users SET password = :password WHERE email = :email");
        return $stmt->execute(['password' => $hash, 'email' => strtolower(trim($email))]);
    }

    /**
     * Total user count
     */
    public static function countAll(): int {
        return (int) db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
