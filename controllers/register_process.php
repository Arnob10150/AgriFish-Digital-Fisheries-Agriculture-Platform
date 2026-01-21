<?php
session_start();
require_once '../models/User.php';
if(isset($_POST['register'])){
    $name_Err;
    $email_Err;
    $username_Err;
    $phone_Err;
    $nid_Err;
    $password_Err;
    $confirm_password_Err;
    $address_Err;
    $role_Err;
    $hasErr=false;
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
                    header("Location: ../views/User/login.php");
                } else {
                    $_SESSION['reg_error']='Registration failed, please try again';
                    header("Location: ../views/User/register.php");
                }
            }
        }
        if($hasErr)
        {
            $_SESSION['field_errors'] = array_filter(compact('name_Err', 'username_Err', 'phone_Err', 'nid_Err', 'email_Err', 'password_Err', 'confirm_password_Err', 'address_Err', 'role_Err'));
            header("Location: ../views/User/register.php");
            exit;
        }
    }
}
?>
