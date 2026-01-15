<?php
include '../DB/Db_connection.php';
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Part_id'])){
    $Part_id =$_POST['Part_id'];
    $Reorder_level = $_POST['Reorder_level'];

    $sql = "UPDATE parts SET Reorder_level='$Reorder_level' Where Part_id='$Part_id'";

    if($conn->query($sql) === TRUE) {
        $message = "Reorder Level Update Successfully!";
    }
    else{
        $message = "Error" . $conn->error;
    }
}
$sql = "SELECT * FROM parts";
$result = $conn->query($sql);
?>