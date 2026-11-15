<?php
/**
 * Application Bootstrap & Security Configuration
 * RTIH TaskFlow
 */

// 1. Simple .env Loader
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            // Remove wrapping quotes if present
            if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
                $val = substr($val, 1, -1);
            }
            if (!isset($_SERVER[$key]) && !isset($_ENV[$key])) {
                putenv("$key=$val");
                $_ENV[$key] = $val;
                $_SERVER[$key] = $val;
            }
        }
    }
}

// 2. Global Constants
if (!defined('APP_NAME')) {
    define('APP_NAME', getenv('APP_NAME') ?: 'TaskFlow');
}
if (!defined('BASE_URL')) {
    // Dynamically calculate BASE_URL based on script name if not explicitly set
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    // If in public/ or actions/, point to web root of task-tracker
    $cleanPath = rtrim(str_replace(['/public', '/actions'], '', $scriptDir), '/');
    define('BASE_URL', getenv('APP_URL') ?: (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . $cleanPath : '/task-tracker'));
}

// 3. Secure Session Initialization
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

// 4. Security & Utility Helper Functions

/**
 * Escape output string to prevent XSS (Cross-Site Scripting)
 */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate or retrieve current CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output HTML hidden field containing CSRF token
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Validate submitted CSRF token
 */
function validate_csrf(): bool {
    $submitted = $_POST['csrf_token'] ?? '';
    if (empty($submitted) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $submitted);
}

/**
 * Set flash message for one-time display (success, error, warning)
 */
function set_flash(string $type, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type] = $message;
}

/**
 * Retrieve and clear flash message
 */
function get_flash(string $type): ?string {
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

/**
 * Check if flash message exists
 */
function has_flash(string $type): bool {
    return !empty($_SESSION['flash'][$type]);
}

/**
 * Redirect to a given URL and exit
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Get authenticated user data from session
 */
function auth_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Master Administrator Configuration
 * Boddeda Prudhvi holds exclusive authority to change roles (user <-> admin)
 */
if (!defined('MASTER_ADMIN_EMAIL')) {
    define('MASTER_ADMIN_EMAIL', 'prudhviboddeda5@gmail.com');
}

/**
 * Check if authenticated user has Admin role
 */
function is_admin(): bool {
    $user = auth_user();
    return $user && ($user['role'] ?? '') === 'admin';
}

/**
 * Check if a given user array or the currently authenticated user is the Master Administrator
 */
function is_master_admin(?array $user = null): bool {
    if ($user === null) {
        $user = auth_user();
    }
    if (!$user) {
        return false;
    }
    return strtolower(trim($user['email'] ?? '')) === strtolower(MASTER_ADMIN_EMAIL);
}

/**
 * Guard route: require Master Admin privileges
 */
function require_master_admin(): void {
    require_admin();
    if (!is_master_admin()) {
        set_flash('error', 'Action prohibited: Only the Master Administrator (Boddeda Prudhvi) has permission to change user access roles.');
        redirect(BASE_URL . '/public/index.php?page=admin-users');
    }
}

/**
 * Guard route: require authenticated user
 */
function require_auth(): void {
    if (!auth_user()) {
        set_flash('error', 'Please sign in to access your task dashboard.');
        redirect(BASE_URL . '/public/index.php?page=login');
    }
}

/**
 * Guard route: require admin privileges
 */
function require_admin(): void {
    require_auth();
    if (!is_admin()) {
        set_flash('error', 'Unauthorized: Administrative access required.');
        redirect(BASE_URL . '/public/index.php?page=dashboard');
    }
}
