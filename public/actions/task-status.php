<?php
/**
 * Task Status Toggle Action Handler
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/controllers/TaskController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    TaskController::updateStatus();
} else {
    redirect(BASE_URL . '/public/index.php?page=dashboard');
}
