<?php
require_once("../models/User.php");
session_start();
if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);
    $role = trim($_POST['role']);
    if($name == '' || $email == '' || $password == '' || $role == ''){ $_SESSION['reg_error']='All fields required'; header("Location: ../views/login.php?tab=register"); exit; }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $_SESSION['reg_error']='Invalid email'; header("Location: ../views/login.php?tab=register"); exit; }

    $user = new User();

    // Check if name already exists
    if($user->findByName($name)){
        $_SESSION['reg_error']='This name is already taken. Please choose a different name.';
        header("Location: ../views/login.php?tab=register");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare user data
    $userData = [
        'email' => $email,
        'password' => $hashedPassword,
        'full_name' => $name,
        'role' => $role,
        'location' => $address
    ];

    // Create the user
    if($user->create($userData)){
        $_SESSION['reg_success']='Registration successful! Please login.';
        header("Location: ../views/login.php");
        exit;
    } else {
        $_SESSION['reg_error']='Registration failed. Please try again.';
        header("Location: ../views/login.php?tab=register");
        exit;
    }
}
?>