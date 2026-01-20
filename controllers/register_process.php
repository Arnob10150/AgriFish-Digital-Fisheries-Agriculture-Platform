<?php
session_start();
if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);
    $role = trim($_POST['role']);
    if($name == '' || $email == '' || $password == '' || $role == ''){ $_SESSION['reg_error']='All fields required'; header("Location: ../views/login.php?tab=register"); exit; }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $_SESSION['reg_error']='Invalid email'; header("Location: ../views/login.php?tab=register"); exit; }

    // Dummy registration: always succeed
    $_SESSION['reg_success']='Registration successful! Please login with your credentials.';
    header("Location: ../views/login.php");
    exit;
}
?>