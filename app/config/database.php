<?php
/**
 * Database Connection Factory using PDO
 * RTIH TaskFlow
 */

require_once __DIR__ . '/app.php';

class Database {
    private static ?PDO $instance = null;

    /**
     * Get or create PDO database instance
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host   = env('DB_HOST', '127.0.0.1');
            $port   = env('DB_PORT', '3306');
            $dbname = env('DB_NAME', 'task_flow');
            $user   = env('DB_USER', 'root');
            $pass   = env('DB_PASS', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            if (defined('PDO::MYSQL_ATTR_FOUND_ROWS')) {
                $options[PDO::MYSQL_ATTR_FOUND_ROWS] = true;
            }

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Log technical error internally without leaking sensitive details to UI
                error_log("Database Connection Error: " . $e->getMessage());
                die(
                    '<div style="font-family:sans-serif; max-width:600px; margin:50px auto; padding:24px; border:1px solid #ffdad6; background:#fff8f7; border-radius:12px; color:#ba1a1a;">' .
                    '<h2 style="margin-top:0;">Database Connection Notice</h2>' .
                    '<p style="color:#1a1b1e;">Could not connect to MySQL database <strong>' . htmlspecialchars($dbname) . '</strong>.</p>' .
                    '<p style="color:#474554; font-size:14px;">Please verify that your MySQL server is running (e.g. in XAMPP) and the database has been imported via <code>database/database.sql</code>.</p>' .
                    '<p style="color:#787585; font-size:12px;">Configuration: Host ' . htmlspecialchars($host) . ':' . htmlspecialchars($port) . ' | User: ' . htmlspecialchars($user) . '</p>' .
                    '</div>'
                );
            }
        }

        return self::$instance;
    }
}

/**
 * Convenient procedural shorthand for database connection
 */
function db(): PDO {
    return Database::getConnection();
}
