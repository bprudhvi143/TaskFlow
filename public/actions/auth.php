<?php
/**
 * Authentication Action Handler
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'login':
            AuthController::login();
            break;
        case 'register':
            AuthController::register();
            break;
        case 'logout':
            AuthController::logout();
            break;
        case 'forgot-password':
            AuthController::sendResetLink();
            break;
        case 'reset-password':
            AuthController::processResetPassword();
            break;
        default:
            redirect(BASE_URL . '/public/index.php?page=login');
            break;
    }
} else {
    redirect(BASE_URL . '/public/index.php?page=login');
}
