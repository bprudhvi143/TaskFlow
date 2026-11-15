<?php
/**
 * Application Front Controller / Router
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/TaskController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

$page = $_GET['page'] ?? '';

switch ($page) {
    case 'login':
    case 'register':
        AuthController::showLoginRegister();
        break;

    case 'dashboard':
    case 'my-tasks':
        TaskController::index();
        break;

    case 'admin-tasks':
        AdminController::allTasks();
        break;

    case 'admin-users':
        AdminController::users();
        break;

    default:
        if (auth_user()) {
            redirect(BASE_URL . '/public/index.php?page=dashboard');
        } else {
            redirect(BASE_URL . '/public/index.php?page=login');
        }
        break;
}
