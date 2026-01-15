<?php
ob_start();
session_start();
include("../includes/db_connect.php"); 
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if($email == '' || $password == ''){ $_SESSION['error'] = 'All fields required'; header("Location: ../views/User/login.php"); exit; }
    // Dummy login: check hardcoded credentials
    if($email === 'buyer@buyer.com' && $password === 'buyer'){
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Buyer User';
        $_SESSION['role'] = 'buyer';
        header("Location: /DFAP/views/User/customer_dashboard.php");
        exit;
    } elseif($email === 'admin@admin.com' && $password === 'admin'){
        $_SESSION['user_id'] = 2;
        $_SESSION['user_name'] = 'Admin User';
        $_SESSION['role'] = 'admin';
        header("Location: /DFAP/views/Manager/Dashboard.php");
        exit;
    } elseif($email === 'ngo@ngo.com' && $password === 'ngo'){
        $_SESSION['user_id'] = 3;
        $_SESSION['user_name'] = 'NGO User';
        $_SESSION['role'] = 'government ngo';
        header("Location: /DFAP/views/Manager/Dashboard.php");
        exit;
    } elseif($email === 'fisherman@fisherman.com' && $password === 'fisherman'){
        $_SESSION['user_id'] = 4;
        $_SESSION['user_name'] = 'Fisherman User';
        $_SESSION['role'] = 'fisherman';
        header("Location: /DFAP/views/Manager/staffDashVw.php");
        exit;
    } elseif($email === 'farmer@farmer.com' && $password === 'farmer'){
        $_SESSION['user_id'] = 5;
        $_SESSION['user_name'] = 'Fish Farmer User';
        $_SESSION['role'] = 'fish farmer';
        header("Location: /DFAP/views/Manager/staffDashVw.php");
        exit;
    } else {
        $_SESSION['error'] = 'Invalid credentials';
        header("Location: /DFAP/views/User/login.php");
        exit;
    }
}
?>