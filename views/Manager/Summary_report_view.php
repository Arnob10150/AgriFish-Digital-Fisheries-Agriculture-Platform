<!DOCTYPE html>
<html>
    <head>
        <title>Summary Report</title>
        <link rel="stylesheed" href="../CSS/Summary_report_style.css">
    </head>
    <body>
        <div class="summary-container">
            <h1>Manager Summary Report</h1>
            <div class="card">
                <h2> Purchase Order</h2>
                <p>Total Orders:<b><?php echo $orders['Total_orders']; ?></b></p>
                <p>Approved:<b><?php echo $orders['Approved_orders']; ?></b></p>
                <p>Rejected_orders:<b><?php echo $orders['Rejected_orders']; ?></b></p>
                <p>Pending_orders:<b><?php echo $orders['Pending_orders']; ?></b></p>
            </div>

            <div class="card">
                <h2>Parts Inventory</h2>
                <p>Total Parts:<b><?php echo $parts['Total_parts']; ?></b></p>
                <p>Parts Below Reorder Level:
                    <b style="color: red;"><?php echo $parts['Parts_below_reorder']; ?></b>
                </p>
            </div>
            <div class="card">
                <h2>Customer Returns</h2>
                <p>Total Returns:<b><?php echo $returns['Total_returns']; ?></b></p>
                <p>Approved:<b><?php echo $returns['Approve_returns']; ?></b></p>
                <p>Rejected:<b><?php echo $returns['Rejected_returns']; ?></b></p>
                <p>Pending:<b><?php echo $returns['Pending_returns']; ?></b></p>
            </div>
            <div class="card">
                <h2>Parts per Category</h2>
                <table>
                    <tr>
                        <th>Category Name</th>
                        <th>Total Parts</th>
                        <th>Total Stock</th>
                    </tr>
                    <?php while ($cat = $categoryPartsResult->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $cat['Category_name']; ?></td>
                            <td><?php echo $cat['Total_parts']; ?></td>
                            <td><?php echo $cat['Total_stock']; ?></td>
                        </tr>
                        <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>    