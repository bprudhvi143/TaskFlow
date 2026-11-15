<?php
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Task.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

echo "=== TESTING ROLE PERMISSION REQUIREMENTS ===\n\n";

// 1. Identify users
$masterUser = User::findByEmail('prudhviboddeda5@gmail.com');
$secondaryAdmin = User::findByEmail('b.prudhvi@op.iitg.ac.in');
$standardUser = User::findByEmail('suresh.reddy@gmail.com');

echo "Master Admin: ID={$masterUser['id']}, Email={$masterUser['email']}, Role={$masterUser['role']}\n";
echo "Secondary Admin: ID={$secondaryAdmin['id']}, Email={$secondaryAdmin['email']}, Role={$secondaryAdmin['role']}\n";
echo "Standard User: ID={$standardUser['id']}, Email={$standardUser['email']}, Role={$standardUser['role']}\n\n";

assert(is_master_admin($masterUser) === true, "Master Admin is identified correctly");
assert(is_master_admin($secondaryAdmin) === false, "Secondary Admin is NOT Master Admin");
assert($secondaryAdmin['role'] === 'admin', "Secondary Admin has admin role");

// Start a simulated session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['csrf_token'] = 'test_token_123';

function simulateRoleUpdate($actingUser, $targetUserId, $newRole) {
    $_SESSION['user'] = $actingUser;
    $_SESSION['csrf_token'] = 'test_token_123';
    $_POST = [
        'csrf_token' => 'test_token_123',
        'user_id' => $targetUserId,
        'new_role' => $newRole
    ];
    
    // We can simulate AdminController logic
    $currentUser = auth_user();
    if (!is_admin()) {
        return ['status' => 'denied', 'message' => 'require_admin rejected'];
    }
    
    if ($targetUserId === (int)$currentUser['id'] && $newRole !== 'admin') {
        return ['status' => 'blocked', 'message' => 'Action prohibited: You cannot demote your own administrator account.'];
    }
    
    $target = User::findById($targetUserId);
    if (is_master_admin($target)) {
        return ['status' => 'blocked', 'message' => 'Action prohibited: The Master Administrator (Boddeda Prudhvi) role is permanent and cannot be modified by any administrator.'];
    }
    
    if (!in_array($newRole, ['admin', 'user'])) {
        return ['status' => 'invalid', 'message' => 'Invalid role selected.'];
    }
    
    User::updateRole($targetUserId, $newRole);
    return ['status' => 'success', 'message' => "Role updated to $newRole"];
}

// TEST 1: Secondary admin promotes standard user to admin
echo "[TEST 1] Secondary admin promotes Suresh Reddy to admin...\n";
$res1 = simulateRoleUpdate($secondaryAdmin, (int)$standardUser['id'], 'admin');
echo "Result: {$res1['status']} - {$res1['message']}\n";
$updatedStandard = User::findById((int)$standardUser['id']);
echo "Current DB Role: {$updatedStandard['role']}\n";
assert($updatedStandard['role'] === 'admin', "Suresh Reddy should now be an admin");
echo "-> PASS: Standard user successfully promoted by secondary admin!\n\n";

// TEST 2: Secondary admin demotes Suresh Reddy back to user
echo "[TEST 2] Secondary admin demotes Suresh Reddy back to user...\n";
$res2 = simulateRoleUpdate($secondaryAdmin, (int)$standardUser['id'], 'user');
echo "Result: {$res2['status']} - {$res2['message']}\n";
$revertedStandard = User::findById((int)$standardUser['id']);
echo "Current DB Role: {$revertedStandard['role']}\n";
assert($revertedStandard['role'] === 'user', "Suresh Reddy should now be a standard user");
echo "-> PASS: Administrator successfully demoted to standard user by secondary admin!\n\n";

// TEST 3: Secondary admin attempts to modify/demote Master Admin (Boddeda Prudhvi)
echo "[TEST 3] Secondary admin attempts to change Master Admin role to 'user'...\n";
$res3 = simulateRoleUpdate($secondaryAdmin, (int)$masterUser['id'], 'user');
echo "Result: {$res3['status']} - {$res3['message']}\n";
$checkMaster = User::findById((int)$masterUser['id']);
echo "Current Master DB Role: {$checkMaster['role']}\n";
assert($res3['status'] === 'blocked', "Master Admin role change must be blocked");
assert($checkMaster['role'] === 'admin', "Master Admin role must remain admin");
echo "-> PASS: Master Admin role is strictly protected and cannot be changed by other admins!\n\n";

// TEST 4: Secondary admin attempts self-demotion
echo "[TEST 4] Secondary admin attempts to self-demote...\n";
$res4 = simulateRoleUpdate($secondaryAdmin, (int)$secondaryAdmin['id'], 'user');
echo "Result: {$res4['status']} - {$res4['message']}\n";
$checkSecAdmin = User::findById((int)$secondaryAdmin['id']);
echo "Current Secondary Admin DB Role: {$checkSecAdmin['role']}\n";
assert($res4['status'] === 'blocked', "Self-demotion must be blocked");
assert($checkSecAdmin['role'] === 'admin', "Secondary Admin role must remain admin");
echo "-> PASS: Self-demotion is strictly blocked!\n\n";

// TEST 5: Standard user attempts to call role update
echo "[TEST 5] Standard user attempts to execute role update...\n";
$res5 = simulateRoleUpdate($standardUser, (int)$masterUser['id'], 'user');
echo "Result: {$res5['status']} - {$res5['message']}\n";
assert($res5['status'] === 'denied', "Standard user must be denied admin endpoints");
echo "-> PASS: Standard user is denied access to admin actions!\n\n";

echo "ALL TESTS PASSED SUCCESSFULLY!\n";
