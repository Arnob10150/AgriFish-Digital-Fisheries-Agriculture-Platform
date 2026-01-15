<?php
include '../PHP/Approve_orders.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Order Approve</title>
        <link rel="stylesheet" href="../CSS/Approve_order_style.css">
    </head>
    <body>
        <div class="order_container">
            <h1>Please Approve or Rejected Order</h1>
            <?php if ($result->num_rows > 0){ ?>
                <table border="1" cellpadding="10" cellspacing="0" style="margin:auto; width:00;">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Part</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $row['Order_id']; ?></td>
                            <td><?php echo $row['Customer_name']; ?></td>
                            <td><?php echo $row['Part_name']; ?></td>
                            <td><?php echo $row['Quantity']; ?></td>
                        
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="Order_id" value="<?php echo $row['Order_id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn approve-btn">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn reject-btn">Reject</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
           <?php }  else {?>
            <p style="text-align: center; color: green;">No Pending Orders.</p>
            <?php } ?>
        </div>
    </body>
</html>