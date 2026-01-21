<?php

class NoticeController {
    private $noticeModel;

    public function __construct() {
        require_once '../../models/Notice.php';
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

        if (empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Title and content are required'];
        }

        $data = [
            'title' => $title,
            'content' => $content,
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
        $category = trim($_POST['category'] ?? 'all');

        if (!$id || empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Invalid parameters'];
        }

        $data = [
            'title' => $title,
            'content' => $content,
            'category' => $category
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

    public function getAll() {
        return $this->noticeModel->getAll();
    }

    public function getById($id) {
        return $this->noticeModel->getById($id);
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    session_start();
    header('Content-Type: application/json');
    $controller = new NoticeController();

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