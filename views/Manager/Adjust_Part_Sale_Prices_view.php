<!DOCTYPE html>
<html>
    <title>Adjust Part Sale Price</title>
    <link rel="stylesheet" href="../CSS/Adjust_Part_Sale_Price_style.css">
</html>
<body>
    <div class="price-container">
        <h1>Adjust Part Sale Price</h1>
        <?php if (empty($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <table>
            <tr>
                <th>Part ID</th>
                <th>Part Name</th>
                <th>Current Stock</th>
                <th>Reorder Level</th>
                <th>Sale Price</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <form method="post">
                        <td>
                            <?php echo $row['Part_id']; ?>
                            <input type="hidden" name="Part_id" value="<?php echo $row['Part_id']; ?>">
                        </td>
                        <td><?php echo $row['Part_name']; ?></td>
                        <td><?php echo $row['Current_stock']; ?></td>
                        <td><?php echo $row['Reorder_level']; ?></td>
                        <td>
                            <input type="number" step="0.01" name="Sale_price" value="<?php echo $row['Sale_price']; ?>" min="0">
                        </td>
                        <td>
                            <button type="submit" class="update-btn">Update</button>
                        </td>
                    </form>
                </tr>
                <?php } ?>
        </table>
    </div>
</body>