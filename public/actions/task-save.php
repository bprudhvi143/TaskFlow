<?php
/**
 * Task Creation & Update Action Handler
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/controllers/TaskController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['action_type'] ?? 'create';

    if ($actionType === 'update') {
        TaskController::update();
    } else {
        TaskController::create();
    }
} else {
    redirect(BASE_URL . '/public/index.php?page=dashboard');
}
