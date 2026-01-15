<?php 
include '../DB/Db_connection.php';
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Order_id']) && isset($_POST['action'])){
    $Order_id = $_POST['Order_id'];
    $action = $_POST['action'];

    if($action=="approve"){
        $sql = "UPDATE purchase_orders SET Status='Approved' WHERE Order_id='$Order_id'";
    }
    else{
        $sql = "UPDATE purchase_orders SET Status='Rejected' WHERE Order_id='$Order_id'";
    }
    $conn->query($sql);
}
$sql = "SELECT * FROM purchase_orders WHERE Status='Pending'";
$result = $conn->query($sql);
?>