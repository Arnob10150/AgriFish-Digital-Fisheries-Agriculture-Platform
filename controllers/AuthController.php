<?php


require_once '../models/User.php';


class AuthController {
    private $userModel;


    public function __construct() {
        $this->userModel = new User();

    }


    public function showLoginForm() {
        
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            return;
        }

        $error = $_SESSION['auth_error'] ?? '';
        $success = $_SESSION['auth_success'] ?? '';
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        include '../views/login.php';
    }


    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['auth_error'] = 'Please enter both email and password.';
            $this->redirect('/');
            return;
        }

  
        $demoUsers = [
            'customer@dfap.com' => ['password' => 'customer123', 'role' => 'customer', 'name' => 'Customer User'],
            'fisherman@dfap.com' => ['password' => 'fisherman123', 'role' => 'fisherman', 'name' => 'Fisherman User'],
            'farmer@dfap.com' => ['password' => 'farmer123', 'role' => 'farmer', 'name' => 'Fish Farmer User'],
            'admin@dfap.com' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
        ];

        if (isset($demoUsers[$email]) && $demoUsers[$email]['password'] === $password) {
            $user = $demoUsers[$email];
            $this->createSessionFromDemo($user);
            $this->redirectToDashboard();
        } else {
            $_SESSION['auth_error'] = 'Invalid email or password.';
            $this->redirect('/');
        }
    }



    public function showRoleSelection() {
        if (!isset($_SESSION['mobile_number'])) {
            $this->redirect('/');
            return;
        }

        $error = $_SESSION['auth_error'] ?? '';
        unset($_SESSION['auth_error']);

        include '../views/role-selection.php';
    }

 
    public function saveRoleAndProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['mobile_number'])) {
            $this->redirect('/');
            return;
        }

        $mobile = $_SESSION['mobile_number'];
        $role = $_POST['role'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $location = $_POST['location'] ?? '';
        $language = $_POST['language'] ?? 'bengali';

       
        if (empty($role) || empty($fullName)) {
            $_SESSION['auth_error'] = 'Please select a role and enter your full name.';
            $this->redirect('/role-selection');
            return;
        }

       
        $validRoles = ['customer', 'fisherman', 'farmer', 'admin'];
        if (!in_array($role, $validRoles)) {
            $_SESSION['auth_error'] = 'Invalid role selected.';
            $this->redirect('/role-selection');
            return;
        }

        
        $userData = [
            'mobile_number' => $mobile,
            'full_name' => $fullName,
            'role' => $role,
            'location' => $location,
            'language_preference' => $language,
            'is_verified' => true
        ];

        if ($this->userModel->create($userData)) {
            
            $user = $this->userModel->findByMobile($mobile);
            if ($user) {
                $this->createSession($user);
                $this->redirectToDashboard();
            } else {
                $_SESSION['auth_error'] = 'Registration failed. Please try again.';
                $this->redirect('/role-selection');
            }
        } else {
            $_SESSION['auth_error'] = 'Registration failed. Please try again.';
            $this->redirect('/role-selection');
        }
    }


    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        $this->redirect('/');
    }


    public function checkAccountLock() {
        if (!isset($_SESSION['mobile_number'])) {
            return false;
        }

        return $this->userModel->isAccountLocked($_SESSION['mobile_number']);
    }

    private function createSession($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['mobile_number'] = $user['mobile_number'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['location'] = $user['location'];
        $_SESSION['language'] = $user['language_preference'];
        $_SESSION['login_time'] = time();
    }


    private function createSessionFromDemo($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = rand(1000, 9999); // Demo ID
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['location'] = 'Dhaka';
        $_SESSION['language'] = 'english';
        $_SESSION['login_time'] = time();
    }


    private function redirectToDashboard() {
        if (!isset($_SESSION['role'])) {
            $this->redirect('/');
            return;
        }

        $role = $_SESSION['role'];
        $dashboardRoutes = [
            'customer' => '/dashboard/customer',
            'fisherman' => '/dashboard/fisherman',
            'farmer' => '/dashboard/farmer',
            'admin' => '/dashboard/admin'
        ];

        $route = $dashboardRoutes[$role] ?? '/dashboard/customer';
        $this->redirect($route);
    }


    private function redirect($path) {
        header("Location: $path");
        exit;
    }
}
?>