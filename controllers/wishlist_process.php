<?php
session_start();
include("../includes/db_connect.php");
if(!isset($_SESSION['user_id'])){ header("Location: ../views/login.php"); exit; }
if(isset($_POST['add'])){
    $uid = intval($_SESSION['user_id']);
    $pid = intval($_POST['product_id']);
    $chk = $conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND product_id=?");
    $chk->bind_param("ii",$uid,$pid);
    $chk->execute();
    $r = $chk->get_result();
    if($r->num_rows == 0){
        $ins = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?,?)");
        $ins->bind_param("ii",$uid,$pid);
        $ins->execute();
    }
    header("Location: ../views/wishlist.php");
    exit;
}
?>