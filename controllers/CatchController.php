<?php

class CatchController {
    private $catchModel;

    public function __construct() {
        require_once '../../models/FishCatch.php';
        $this->catchModel = new FishCatch();
    }

    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fisherman') {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }

        $catch_date = trim($_POST['catch_date'] ?? '');
        $fish_type = trim($_POST['fish_type'] ?? '');
        $weight_kg = floatval($_POST['weight_kg'] ?? 0);
        $price_per_kg = floatval($_POST['price_per_kg'] ?? 0);

        if (empty($catch_date) || empty($fish_type) || $weight_kg <= 0 || $price_per_kg <= 0) {
            return ['success' => false, 'message' => 'All fields are required and must be valid'];
        }

        $data = [
            'user_id' => $_SESSION['user_id'],
            'catch_date' => $catch_date,
            'fish_type' => $fish_type,
            'weight_kg' => $weight_kg,
            'price_per_kg' => $price_per_kg
        ];

        $result = $this->catchModel->create($data);
        return [
            'success' => $result,
            'message' => $result ? 'Catch added successfully' : 'Failed to add catch'
        ];
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fisherman') {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }

        $id = intval($_POST['catch_id'] ?? 0);
        $catch_date = trim($_POST['catch_date'] ?? '');
        $fish_type = trim($_POST['fish_type'] ?? '');
        $weight_kg = floatval($_POST['weight_kg'] ?? 0);
        $price_per_kg = floatval($_POST['price_per_kg'] ?? 0);

        if (!$id || empty($catch_date) || empty($fish_type) || $weight_kg <= 0 || $price_per_kg <= 0) {
            return ['success' => false, 'message' => 'Invalid parameters'];
        }

        $data = [
            'catch_date' => $catch_date,
            'fish_type' => $fish_type,
            'weight_kg' => $weight_kg,
            'price_per_kg' => $price_per_kg
        ];

        $result = $this->catchModel->update($id, $data);
        return [
            'success' => $result,
            'message' => $result ? 'Catch updated successfully' : 'Failed to update catch'
        ];
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fisherman') {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }

        $id = intval($_POST['catch_id'] ?? 0);

        if (!$id) {
            return ['success' => false, 'message' => 'Invalid catch ID'];
        }

        $result = $this->catchModel->delete($id);
        return [
            'success' => $result,
            'message' => $result ? 'Catch deleted successfully' : 'Failed to delete catch'
        ];
    }

    public function getAllByUser() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fisherman') {
            return [];
        }
        return $this->catchModel->getAllByUser($_SESSION['user_id']);
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    session_start();
    header('Content-Type: application/json');
    $controller = new CatchController();

    switch ($_GET['action']) {
        case 'create':
            echo json_encode($controller->create());
            break;
        case 'update':
            echo json_encode($controller->update());
            break;
        case 'delete':
            echo json_encode($controller->delete());
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit;
}
?>