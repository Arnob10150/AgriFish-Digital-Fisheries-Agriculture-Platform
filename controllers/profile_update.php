<?php
session_start();
include("../includes/db_connect.php"); 
if(!isset($_SESSION['user_id'])){ header("Location: ../views/login.php"); exit; }
if(isset($_POST['update'])){
    $name = trim($_POST['name']);

    $_SESSION['user_name'] = $name;
    header("Location: ../views/profile.php");
    exit;
}
?>