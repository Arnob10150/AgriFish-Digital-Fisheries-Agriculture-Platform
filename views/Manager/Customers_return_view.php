<!DOCTYPE html>
<html>
    <head>
        <title>Authorize Customer Returns</title>
        <link rel="stylesheet" href="../CSS/Customers_return_style.css">
    </head>
    <body>
        <div class="returns-container">
            <h1>Authorize Customer Returns</h1>
            <?php if (!empty($message)) { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>
            <?php if ($result->num_rows > 0) { ?>
                <table>
                    <tr>
                        <th>Return ID</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Part</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['Return_id']; ?></td>
                            <td><?php echo $row['Order_id']; ?></td>
                            <td><?php echo $row['Customer_name']; ?></td>
                            <td><?php echo $row['Part_name']; ?></td>
                            <td><?php echo $row['Quantity']; ?></td>
                            <td><?php echo $row['Reason']; ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="Return_id" value="<?php echo $row['Return_id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn approve-btn">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn reject-btn">Reject</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                </table>
            <?php } else { ?>
                <p class="no-data">No Pending Customer Return Request</p> 
            <?php } ?>   
        </div>
    </body>
</html>