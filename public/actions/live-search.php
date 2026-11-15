<?php
/**
 * Live Instant Search API Handler
 * Returns real-time matching tasks or users for parallel dropdown display
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/models/Task.php';
require_once __DIR__ . '/../../app/models/User.php';

header('Content-Type: application/json; charset=UTF-8');

if (!auth_user()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$currentUser = auth_user();
$type  = trim($_GET['type'] ?? 'tasks');
$query = trim($_GET['q'] ?? '');
$scope = trim($_GET['scope'] ?? 'personal');

if ($query === '') {
    echo json_encode(['type' => $type, 'count' => 0, 'results' => []]);
    exit;
}

if ($type === 'users') {
    if (!is_admin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: Administrator privileges required.']);
        exit;
    }

    $users = User::getAllWithTaskCount(null, $query);
    foreach ($users as &$u) {
        $u['tasks'] = Task::findByUser((int) $u['id'], 50, 0);
    }
    unset($u);

    echo json_encode([
        'type'    => 'users',
        'query'   => $query,
        'count'   => count($users),
        'results' => array_slice($users, 0, 8)
    ]);
    exit;
}

// Default: Tasks search
if ($scope === 'all' && is_admin()) {
    $tasks = Task::getAllWithOwners(null, null, $query, 8, 0);
} else {
    $tasks = Task::findByUser((int) $currentUser['id'], null, null, $query, 8, 0);
}

echo json_encode([
    'type'    => 'tasks',
    'query'   => $query,
    'count'   => count($tasks),
    'results' => $tasks
]);
exit;
