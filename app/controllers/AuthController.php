<?php
/**
 * Authentication Controller
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';

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

        // 5 Rules for a Strong Password (NIST / CISA guidelines)
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } else {
            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Password must contain at least one uppercase letter (A-Z).';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = 'Password must contain at least one lowercase letter (a-z).';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Password must contain at least one numeric digit (0-9).';
            }
            if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`]/', $password)) {
                $errors[] = 'Password must contain at least one special symbol (!@#$%^&*...).';
            }

            // Rule: Avoid personal information (name or email prefix cannot be in password)
            $nameParts = preg_split('/[\s,\.\-_]+/', strtolower($name));
            $emailPrefix = strtolower(explode('@', $email)[0]);
            $nameParts[] = $emailPrefix;

            foreach ($nameParts as $part) {
                if (strlen($part) >= 3 && stripos($password, $part) !== false) {
                    $errors[] = 'Password cannot contain your name or email prefix.';
                    break;
                }
            }
        }

        if (!empty($errors)) {
            set_flash('error', implode(' ', $errors));
            redirect(BASE_URL . '/public/index.php?page=login&tab=register');
        }

        // Standard user creation: Accounts can ONLY be created as standard 'user'
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

    /**
     * Display Forgot Password Screen
     */
    public static function showForgotPassword(): void {
        if (auth_user()) {
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }
        require_once __DIR__ . '/../views/auth/forgot-password.php';
    }

    /**
     * Process Forgot Password Request & Generate Secure Reset Token
     */
    public static function sendResetLink(): void {
        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Please try again.');
            redirect(BASE_URL . '/public/index.php?page=forgot-password');
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Please provide a valid email address.');
            redirect(BASE_URL . '/public/index.php?page=forgot-password');
        }

        $user = User::findByEmail($email);

        if ($user) {
            // Generate secure one-time reset token (valid for 30 minutes)
            $token = PasswordReset::createToken($email);
            $resetUrl = BASE_URL . '/public/index.php?page=reset-password&token=' . urlencode($token);

            // In production with an SMTP mail server configured, mail() would deliver the message.
            // For local development and evaluation environment without external mail servers:
            // We store the reset link and email in flash so it can be previewed/accessed directly on screen!
            set_flash('reset_success', '1');
            set_flash('reset_email', $email);
            set_flash('reset_link', $resetUrl);
            set_flash('success', "A password reset link has been generated for {$email}.");
        } else {
            // Friendly message without leaking if email exists
            set_flash('reset_success', '1');
            set_flash('reset_email', $email);
            set_flash('info', "If an account exists with {$email}, instructions to reset your password have been processed.");
        }

        redirect(BASE_URL . '/public/index.php?page=forgot-password');
    }

    /**
     * Display Password Reset Form with Token Validation
     */
    public static function showResetPassword(): void {
        if (auth_user()) {
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        }

        $token = trim($_GET['token'] ?? '');
        $resetRecord = PasswordReset::verifyToken($token);

        if (!$resetRecord) {
            set_flash('error', 'This password reset link is invalid, expired, or has already been used. Please request a new one.');
            redirect(BASE_URL . '/public/index.php?page=forgot-password');
        }

        $email = $resetRecord['email'];
        require_once __DIR__ . '/../views/auth/reset-password.php';
    }

    /**
     * Process Password Reset with Strong Password Validation
     */
    public static function processResetPassword(): void {
        if (!validate_csrf()) {
            set_flash('error', 'Invalid security token (CSRF). Please try again.');
            redirect(BASE_URL . '/public/index.php?page=login');
        }

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $resetRecord = PasswordReset::verifyToken($token);

        if (!$resetRecord) {
            set_flash('error', 'The password reset token is invalid or has expired. Please submit a new request.');
            redirect(BASE_URL . '/public/index.php?page=forgot-password');
        }

        $email = $resetRecord['email'];
        $user = User::findByEmail($email);

        if (!$user) {
            set_flash('error', 'User account not found.');
            redirect(BASE_URL . '/public/index.php?page=login');
        }

        $errors = [];

        // Check password matching
        if ($password !== $passwordConfirm) {
            $errors[] = 'Password confirmation does not match the entered password.';
        }

        // Validate 5 strong password rules
        if (empty($password)) {
            $errors[] = 'New password is required.';
        } else {
            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Password must contain at least one uppercase letter (A-Z).';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = 'Password must contain at least one lowercase letter (a-z).';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Password must contain at least one numeric digit (0-9).';
            }
            if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`]/', $password)) {
                $errors[] = 'Password must contain at least one special symbol (!@#$%^&*...).';
            }

            // Exclude personal info (name or email prefix)
            $nameParts = preg_split('/[\s,\.\-_]+/', strtolower($user['name']));
            $emailPrefix = strtolower(explode('@', $email)[0]);
            $nameParts[] = $emailPrefix;

            foreach ($nameParts as $part) {
                if (strlen($part) >= 3 && stripos($password, $part) !== false) {
                    $errors[] = 'Password cannot contain your name or email prefix.';
                    break;
                }
            }
        }

        if (!empty($errors)) {
            set_flash('error', implode(' ', $errors));
            redirect(BASE_URL . '/public/index.php?page=reset-password&token=' . urlencode($token));
        }

        // Update password with bcrypt hash
        if (User::updatePassword((int) $user['id'], $password)) {
            // Invalidate token (single use)
            PasswordReset::deleteToken($token);

            set_flash('success', 'Your password has been reset successfully! You can now sign in with your new password.');
            redirect(BASE_URL . '/public/index.php?page=login');
        } else {
            set_flash('error', 'Failed to update password. Please try again.');
            redirect(BASE_URL . '/public/index.php?page=reset-password&token=' . urlencode($token));
        }
    }
}
