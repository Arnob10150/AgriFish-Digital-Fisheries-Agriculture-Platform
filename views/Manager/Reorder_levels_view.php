<?php include '../PHP/Reorder_levels.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Parts Reorder Level</title>
        <link rel="stylesheet" href="../CSS/Reorder_level_style.css">
    </head>
    <body>
        <div class="reorder-container">
            <h1>Manage Reorde Levels</h1>
            <?php if (isset($message)){ ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>
            <table>
                <tr>
                    <th>Part ID</th>
                    <th>Part Name</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Action</th>
                </tr>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <form method="post">
                            <td>
                                <?php echo $row['Part_id']; ?>
                                <input type="hidden" name="Part_id" value="<?php echo $row['Part_id']; ?>">
                            </td>
                            <td><?php echo $row['Part_name'];?></td>
                            <td><?php echo $row['Current_stock'];?></td>
                            <td>
                                <input type="number" name="Reorder_level" value="<?php echo $row['Reorder_level']; ?>" min="0">
                            </td>
                            <td>
                                <button type="submit" class="update-bin">Update</button>
                            </td>
                        </form>
                    </tr>
                    <?php } ?>
            </table>
        </div>
    </body>
</html>