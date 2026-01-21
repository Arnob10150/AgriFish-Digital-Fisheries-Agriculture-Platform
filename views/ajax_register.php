<?php
session_start();
require_once '../models/User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$full_name = $_POST['full_name'] ?? '';
$reg_email = $_POST['reg_email'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$nid = $_POST['nid'] ?? '';
$reg_password = $_POST['reg_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? '';

$name_Err = '';
$email_Err = '';
$phone_Err = '';
$nid_Err = '';
$password_Err = '';
$confirm_password_Err = '';
$role_Err = '';
$hasErr = false;

if (empty($full_name)) {
    $name_Err = "Name is required";
    $hasErr = true;
} else {
    if (!preg_match("/^[a-zA-Z-' ]*$/", $full_name)) {
        $name_Err = "Only letters and white space allowed";
        $hasErr = true;
    }
}

if (empty($reg_email)) {
    $email_Err = "Email is required";
    $hasErr = true;
} else {
    if (!filter_var($reg_email, FILTER_VALIDATE_EMAIL)) {
        $email_Err = "Invalid email format";
        $hasErr = true;
    }
}

if (empty($phone_number)) {
    $phone_Err = "Phone is required";
    $hasErr = true;
} else {
    if (!preg_match('/^\+8801[3-9]\d{8}$/', $phone_number)) {
        $phone_Err = "Invalid phone";
        $hasErr = true;
    }
}

if (empty($nid)) {
    $nid_Err = "NID is required";
    $hasErr = true;
} else {
    if (!preg_match('/^\d{10,17}$/', $nid)) {
        $nid_Err = "Invalid NID";
        $hasErr = true;
    }
}

if (empty($reg_password)) {
    $password_Err = "Password is required";
    $hasErr = true;
} else {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $reg_password)) {
        $password_Err = "Invalid password";
        $hasErr = true;
    }
}

if (empty($confirm_password)) {
    $confirm_password_Err = "Confirm password is required";
    $hasErr = true;
} else {
    if ($reg_password !== $confirm_password) {
        $confirm_password_Err = "Passwords do not match";
        $hasErr = true;
    }
}

if (empty($role)) {
    $role_Err = "Role is required";
    $hasErr = true;
}

if (!$hasErr) {
    $userModel = new User();
    if ($userModel->findByUsername($full_name)) {
        $name_Err = "Username already exists";
        $hasErr = true;
    }
    if ($userModel->findByEmail($reg_email)) {
        $email_Err = "Email already exists";
        $hasErr = true;
    }
    if ($userModel->findByMobile($phone_number)) {
        $phone_Err = "Phone number already exists";
        $hasErr = true;
    }
    if ($userModel->findByNid($nid)) {
        $nid_Err = "NID already exists";
        $hasErr = true;
    }
    if (!$hasErr) {
        $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);
        $data = [
            'username' => $full_name,
            'email' => $reg_email,
            'password' => $hashed_password,
            'mobile_number' => $phone_number,
            'nid' => $nid,
            'full_name' => $full_name,
            'role' => $role
        ];
        if ($userModel->create($data)) {
            $_SESSION['signup_complete'] = true;
            $_SESSION['signup_message'] = 'Registration submitted successfully! Your account is pending admin verification. You will be notified once approved.';
            echo json_encode(['success' => true, 'message' => 'Registration submitted successfully! Your account is pending admin verification. You will be notified once approved.']);
            exit;
        } else {
            echo json_encode(['errors' => ['general' => 'Registration failed, please try again']]);
            exit;
        }
    }
}

if ($hasErr) {
    $errors = array_filter(compact('name_Err', 'email_Err', 'phone_Err', 'nid_Err', 'password_Err', 'confirm_password_Err', 'role_Err'));
    echo json_encode(['errors' => $errors]);
} else {
    echo json_encode(['success' => true]);
}
?>