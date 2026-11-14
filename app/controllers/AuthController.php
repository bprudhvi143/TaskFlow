<?php
/**
 * Authentication Controller
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    /**
     * Display Login & Registration screen
     */
    public static function showLoginRegister(): void {
        if (auth_user()) {
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }
        $tab = $_GET['tab'] ?? 'signin';
        require_once __DIR__ . '/../views/auth/login-register.php';
    }

    /**
     * Process User Sign In
     */
    public static function login(): void {
        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Please try again.');
            redirect(BASE_URL . '/public/index.php?page=login&tab=signin');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Server-side validation
        $errors = [];
        if (empty($email)) {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please provide a valid email address.';
        }

        if (empty($password)) {
            $errors[] = 'Password is required.';
        }

        if (!empty($errors)) {
            set_flash('error', implode(' ', $errors));
            redirect(BASE_URL . '/public/index.php?page=login&tab=signin');
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            set_flash('error', 'Invalid email address or password.');
            redirect(BASE_URL . '/public/index.php?page=login&tab=signin');
        }

        // Authentication Successful: Regenerate session to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => (int) $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        set_flash('success', "Welcome back, {$user['name']}!");
        redirect(BASE_URL . '/public/index.php?page=dashboard');
    }

    /**
     * Process New User Registration
     */
    public static function register(): void {
        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Please try again.');
            redirect(BASE_URL . '/public/index.php?page=login&tab=register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Full name is required.';
        } elseif (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
            $errors[] = 'Full name must be between 2 and 100 characters.';
        }

        if (empty($email)) {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        } elseif (User::findByEmail($email)) {
            $errors[] = 'An account with this email address already exists.';
        }

        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        if (!empty($errors)) {
            set_flash('error', implode(' ', $errors));
            redirect(BASE_URL . '/public/index.php?page=login&tab=register');
        }

        // Create new standard user
        $userId = User::create($name, $email, $password, 'user');

        if ($userId > 0) {
            // Auto login after registration
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'    => $userId,
                'name'  => $name,
                'email' => $email,
                'role'  => 'user'
            ];

            set_flash('success', 'Account created successfully! Welcome to TaskFlow.');
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        } else {
            set_flash('error', 'Failed to register account. Please try again.');
            redirect(BASE_URL . '/public/index.php?page=login&tab=register');
        }
    }

    /**
     * Terminate Session / Logout
     */
    public static function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_start();
        set_flash('success', 'You have been signed out safely.');
        redirect(BASE_URL . '/public/index.php?page=login');
    }
}
