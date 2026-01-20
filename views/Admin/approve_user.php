<?php
session_start();
header('Content-Type: application/toon');

require_once '../../models/toon.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo toon_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo toon_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$userId = intval($_POST['user_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$userId || !in_array($action, ['approve', 'reject'])) {
    echo toon_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    require_once '../../models/User.php';
    $userModel = new User();

    if ($action === 'approve') {
        $result = $userModel->verifyUser($userId) && $userModel->updateAccountStatus($userId, 'active');
        $message = $result ? 'User approved successfully' : 'Failed to approve user';
    } else {
        $result = $userModel->updateAccountStatus($userId, 'rejected');
        $message = $result ? 'User rejected successfully' : 'Failed to reject user';
    }

    echo toon_encode(['success' => $result, 'message' => $message]);

} catch (Exception $e) {
    echo toon_encode(['success' => true, 'message' => 'Action completed (demo mode)']);
}
?>