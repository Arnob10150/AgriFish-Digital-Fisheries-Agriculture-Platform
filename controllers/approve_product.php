<?php
session_start();
header('Content-Type: application/toon');

require_once '../models/toon.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo toon_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo toon_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$productId = intval($_POST['product_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$productId || !in_array($action, ['approve', 'reject'])) {
    echo toon_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    require_once '../models/Product.php';
    $productModel = new Product();

    if ($action === 'approve') {
        $result = $productModel->approveProduct($productId);
        $message = $result ? 'Product approved successfully' : 'Failed to approve product (ID: ' . $productId . ')';
    } else {
        $result = $productModel->rejectProduct($productId);
        $message = $result ? 'Product rejected successfully' : 'Failed to reject product (ID: ' . $productId . ')';
    }

    echo toon_encode(['success' => $result, 'message' => $message]);

} catch (Exception $e) {
    echo toon_encode(['success' => false, 'message' => 'Action failed: ' . $e->getMessage()]);
}
?>