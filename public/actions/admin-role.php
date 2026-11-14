<?php
/**
 * Administrator Actions Handler
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'update-role':
            AdminController::updateUserRole();
            break;
        case 'delete-task':
            AdminController::deleteTask();
            break;
        default:
            redirect(BASE_URL . '/public/index.php?page=admin-tasks');
            break;
    }
} else {
    redirect(BASE_URL . '/public/index.php?page=admin-tasks');
}
