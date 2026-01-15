<?php

require_once '../models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }


    public function getProfile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        return $user;
    }


    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }

        $userId = $_SESSION['user_id'];
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'location' => $_POST['location'] ?? '',
            'language_preference' => $_POST['language'] ?? 'bengali'
        ];

  
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'storage/uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                $data['profile_picture'] = $fileName;
            }
        }

        if ($this->userModel->updateProfile($userId, $data)) {
  
            $_SESSION['user_name'] = $data['full_name'];
            $_SESSION['location'] = $data['location'];
            $_SESSION['language'] = $data['language_preference'];

            $_SESSION['success_message'] = 'Profile updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update profile. Please try again.';
        }

        $this->redirect('/profile');
    }


    public function changeRole() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('/');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/admin');
            return;
        }

        $targetUserId = $_POST['user_id'] ?? 0;
        $newRole = $_POST['role'] ?? '';

        $validRoles = ['customer', 'fisherman', 'farmer', 'admin'];

        if (!$targetUserId || !in_array($newRole, $validRoles)) {
            $_SESSION['error_message'] = 'Invalid request.';
            $this->redirect('/dashboard/admin');
            return;
        }

        if ($this->userModel->updateRole($targetUserId, $newRole)) {
            $_SESSION['success_message'] = 'User role updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update user role.';
        }

        $this->redirect('/dashboard/admin');
    }


    public function verifyUser() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('/');
            return;
        }

        $userId = $_POST['user_id'] ?? 0;

        if (!$userId) {
            $_SESSION['error_message'] = 'Invalid user ID.';
            $this->redirect('/dashboard/admin');
            return;
        }

        if ($this->userModel->verifyUser($userId)) {
            $_SESSION['success_message'] = 'User verified successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to verify user.';
        }

        $this->redirect('/dashboard/admin');
    }

    public function updateAccountStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('/');
            return;
        }

        $userId = $_POST['user_id'] ?? 0;
        $status = $_POST['status'] ?? '';

        $validStatuses = ['active', 'suspended', 'locked'];

        if (!$userId || !in_array($status, $validStatuses)) {
            $_SESSION['error_message'] = 'Invalid request.';
            $this->redirect('/dashboard/admin');
            return;
        }

        if ($this->userModel->updateAccountStatus($userId, $status)) {
            $_SESSION['success_message'] = 'Account status updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update account status.';
        }

        $this->redirect('/dashboard/admin');
    }


    public function getAllUsers() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            return [];
        }

        return $this->userModel->getAllUsers();
    }


    public function getUsersByRole($role) {
        return $this->userModel->getUsersByRole($role);
    }

 
    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }


    public function hasRole($role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }


    public function getCurrentUserRole() {
        return $_SESSION['role'] ?? null;
    }


    private function redirect($path) {
        header("Location: $path");
        exit;
    }
}
?>