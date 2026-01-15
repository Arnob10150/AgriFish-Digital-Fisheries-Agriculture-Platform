<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipment Details</title>
  <style>
    table {
      border-collapse: collapse;
      width: 80%;
      margin: 20px auto;
    }
    table, th, td {
      border: 1px solid #333;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    th {
      background: #0b7cff;
      color: white;
    }
    button {
        padding: 10px 10px 10px 10px;
        text-align: center;
        margin: 15px;
        background: lightgreen;
    }
  </style>
</head>
<body>

<form action="" method="POST" style="text-align:center; margin-top:20px;">
  <button type="submit" name="show_shipments">Show Details</button>
</form>

<div name="shipment">
<?php 
include "../../models/db_connect.php";

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['show_shipments'])) {

  
    $sql = "SELECT *  FROM shipment";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Place Order</th><th>To Be Received</th><th>Supplier</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['place_oder']."</td>";
            echo "<td>".$row['to_be_recived']."</td>";
            echo "<td>".$row['supplier']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>No shipment records found.</p>";
    }
}
?>
</div>

</body>
</html>
