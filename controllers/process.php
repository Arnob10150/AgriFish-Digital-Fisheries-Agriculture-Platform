<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Process Customer Return</title>
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
        background: palevioletred;
    }
  </style>
</head>
<body>

<form action="" method="POST" style="text-align:center; margin-top:20px;">
  <button type="submit" name="process">Process Return</button>
</form>

<div name="process">
<?php 
include "../models/db_connect.php";

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['process'])) {

    $sql = "SELECT *  FROM process";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Part ID</th><th>To Be Received</th><th>Quantity</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['part_id']."</td>";
            echo "<td>".$row['location']."</td>";
            echo "<td>".$row['quantity']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>No customer return records found.</p>";
    }
}
?>
</div>

</body>
</html>
