<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class NoticeController {
    private $noticeModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Notice.php';
        require_once __DIR__ . '/../models/toon.php';
        $this->noticeModel = new Notice();
    }

    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized - user_id: ' . ($_SESSION['user_id'] ?? 'not set') . ', role: ' . ($_SESSION['role'] ?? 'not set')];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'all');

        if (empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Title and content are required'];
        }

        // Validate category
        $validCategories = ['all', 'customer', 'fisherman', 'farmer', 'admin', 'government_ngo'];
        if (!in_array($category, $validCategories)) {
            $category = 'all';
        }

        $data = [
            'title' => $title,
            'content' => $content,
            'category' => $category,
            'created_by' => $_SESSION['user_id']
        ];

        $result = $this->noticeModel->create($data);
        return [
            'success' => $result,
            'message' => $result ? 'Notice created successfully' : 'Failed to create notice - check database connection and table exists'
        ];
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }

        $id = intval($_POST['notice_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (!$id || empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Invalid parameters'];
        }

        $data = [
            'title' => $title,
            'content' => $content
        ];

        $result = $this->noticeModel->update($id, $data);
        return [
            'success' => $result,
            'message' => $result ? 'Notice updated successfully' : 'Failed to update notice'
        ];
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }

        $id = intval($_POST['notice_id'] ?? 0);

        if (!$id) {
            return ['success' => false, 'message' => 'Invalid notice ID'];
        }

        $result = $this->noticeModel->delete($id);
        return [
            'success' => $result,
            'message' => $result ? 'Notice deleted successfully' : 'Failed to delete notice'
        ];
    }

    public function getAll($userRole = null) {
        // If no user role provided, try to get from session
        if (!$userRole && isset($_SESSION['role'])) {
            $userRole = $_SESSION['role'];
        }
        return $this->noticeModel->getAll($userRole);
    }

    public function getById($id) {
        return $this->noticeModel->getById($id);
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $controller = new NoticeController();

    switch ($_GET['action']) {
        case 'create':
            echo toon_encode($controller->create());
            break;
        case 'update':
            echo toon_encode($controller->update());
            break;
        case 'delete':
            echo toon_encode($controller->delete());
            break;
        case 'test':
            echo toon_encode(['success' => true, 'message' => 'Controller is working', 'session' => ['user_id' => $_SESSION['user_id'] ?? 'not set', 'role' => $_SESSION['role'] ?? 'not set']]);
            break;
        default:
            echo toon_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit;
}
?>