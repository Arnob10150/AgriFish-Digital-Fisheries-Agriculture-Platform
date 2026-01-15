<?php
session_start();
include("../includes/db_connect.php");
if(!isset($_SESSION['user_id'])){ header("Location: ../views/login.php"); exit; }
if(isset($_POST['order'])){
    $uid = intval($_SESSION['user_id']);
    $pid = intval($_POST['product_id']);
    $qty = 1;
    $ins = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?,?,?)");
    $ins->bind_param("iii",$uid,$pid,$qty);
    $ins->execute();
    header("Location: ../views/orders.php");
    exit;
}
?>