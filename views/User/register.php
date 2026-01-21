<?php
session_start();
require_once '../../models/User.php';
$error = '';
if(isset($_SESSION['reg_error'])){ $error = $_SESSION['reg_error']; unset($_SESSION['reg_error']); }
$field_errors = $_SESSION['field_errors'] ?? []; unset($_SESSION['field_errors']);
$name_Err;
$username_Err;
$phone_Err;
$nid_Err;
$email_Err;
$password_Err;
$confirm_password_Err;
$address_Err;
$role_Err;
$hasErr=false;
if(isset($_POST['register'])){
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(empty($_POST['name']))
        {
            $name_Err="Name is required";
            $hasErr=true;
        }
        else
        {
            if(!preg_match("/^[a-zA-Z-' ]*$/",$_POST['name']))
            {
                $name_Err="Only letters and white space allowed";
                $hasErr=true;
            }
        }
    
        if(empty($_POST['email']))
        {
            $email_Err="Email is required";
            $hasErr=true;
        }
        else{
            if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
            {
                $email_Err="Invalid email format";
                $hasErr=true;
            }
        }
        if(empty($_POST['username']))
        {
            $username_Err="Username is required";
            $hasErr=true;
        }
        else
        {
            if(!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $_POST['username']))
            {
                $username_Err="Invalid username";
                $hasErr=true;
            }
        }
        if(empty($_POST['phone']))
        {
            $phone_Err="Phone is required";
            $hasErr=true;
        }
        else
        {
            if(!preg_match('/^\+8801[3-9]\d{8}$/', $_POST['phone']))
            {
                $phone_Err="Invalid phone";
                $hasErr=true;
            }
        }
        if(empty($_POST['nid']))
        {
            $nid_Err="NID is required";
            $hasErr=true;
        }
        else
        {
            if(!preg_match('/^\d{10,17}$/', $_POST['nid']))
            {
                $nid_Err="Invalid NID";
                $hasErr=true;
            }
        }
        if(empty($_POST['password']))
        {
            $password_Err="Password is required";
            $hasErr=true;
        }
        else
        {
            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $_POST['password']))
            {
                $password_Err="Invalid password";
                $hasErr=true;
            }
        }
        if(empty($_POST['confirm_password']))
        {
            $confirm_password_Err="Confirm password is required";
            $hasErr=true;
        }
        else
        {
            if($_POST['password'] !== $_POST['confirm_password'])
            {
                $confirm_password_Err="Passwords do not match";
                $hasErr=true;
            }
        }
        if(empty($_POST['address']))
        {
            $address_Err="Address is required";
            $hasErr=true;
        }
        if(empty($_POST['role']))
        {
            $role_Err="Role is required";
            $hasErr=true;
        }
        if(!$hasErr)
        {
            $name = trim($_POST['name']);
            $username = trim($_POST['username']);
            $phone = trim($_POST['phone']);
            $nid = trim($_POST['nid']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            $address = trim($_POST['address']);
            $role = trim($_POST['role']);
            $userModel = new User();
            if($userModel->findByUsername($username)){
                $username_Err="Username already exists";
                $hasErr=true;
            }
            if($userModel->findByEmail($email)){
                $email_Err="Email already exists";
                $hasErr=true;
            }
            if($userModel->findByMobile($phone)){
                $phone_Err="Phone number already exists";
                $hasErr=true;
            }
            if($userModel->findByNid($nid)){
                $nid_Err="NID already exists";
                $hasErr=true;
            }
            if(!$hasErr)
            {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $data = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password,
                    'mobile_number' => $phone,
                    'nid' => $nid,
                    'full_name' => $name,
                    'role' => $role,
                    'location' => $address
                ];
                if($userModel->create($data)){
                    try {
                        $logStmt = $userModel->db->prepare("INSERT INTO system_logs (level, message, context) VALUES (?, ?, ?)");
                        $logStmt->execute(['info', 'New user registration pending approval', json_encode(['user_id' => $userModel->db->lastInsertId(), 'full_name' => $name, 'email' => $email])]);
                    } catch (Exception $e) {
                    }
                    $_SESSION['reg_success']='Registration successful! Your account is pending admin approval.';
                    header("Location: login.php");
                    exit;
                } else {
                    $_SESSION['reg_error']='Registration failed, please try again';
                    header("Location: register.php");
                    exit;
                }
            }
        }
        if($hasErr)
        {
            $field_errors = array_filter(compact('name_Err', 'username_Err', 'phone_Err', 'nid_Err', 'email_Err', 'password_Err', 'confirm_password_Err', 'address_Err', 'role_Err'));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DFAP</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
<div class="auth-section" style="min-height: 100vh;">
    <div class="auth-container">
        <div class="auth-header">
            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Join the Digital Fisheries & Agriculture Platform</p>
        </div>

        <div class="auth-card">
            <div class="auth-form">
                <?php if($error != ''){ echo '<div class="error">'.htmlspecialchars($error).'</div>'; } ?>
                <form id="registerForm" method="post" action="" enctype="multipart/form-data" novalidate>
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" placeholder="Your full name" class="form-input">
                            <span class="input-icon">👤</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['name_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-wrapper">
                            <input type="text" id="username" name="username" placeholder="Username (3-20 chars, alphanumeric)" class="form-input">
                            <span class="input-icon">👤</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['username_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-wrapper">
                            <input type="text" id="phone" name="phone" placeholder="+8801xxxxxxxxx" class="form-input">
                            <span class="input-icon">📞</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['phone_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="nid" class="form-label">NID</label>
                        <div class="input-wrapper">
                            <input type="text" id="nid" name="nid" placeholder="National ID (10-17 digits)" class="form-input">
                            <span class="input-icon">🆔</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['nid_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="your@email.com" class="form-input">
                            <span class="input-icon">📧</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['email_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Password (min 8 chars, mixed)" class="form-input">
                            <span class="input-icon">🔒</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['password_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-input">
                            <span class="input-icon">🔒</span>
                        </div>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['confirm_password_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" placeholder="Your address" class="form-input" rows="3"></textarea>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['address_Err'] ?? ''; ?></span>
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-input">
                            <option value="">Select Role</option>
                            <option value="customer">Buyer</option>
                            <option value="farmer">Fish Farmer</option>
                            <option value="fisherman">Fisherman</option>
                            <option value="government_ngo">Government NGO</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <span style="color:red;"><?php echo $field_errors['role_Err'] ?? ''; ?></span>
                    <button type="submit" name="register" class="auth-button">
                        Create Account <span class="arrow-icon">→</span>
                    </button>
                </form>
                <p class="auth-link">Already have account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
