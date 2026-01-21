<?php
session_start();
if(!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ['fisherman', 'farmer'])) {
    header("Location:../home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/AgriFish-Digital-Fisheries-Agriculture-Platform-main/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle"><?php echo ucfirst($_SESSION['role']); ?> Portal</div>
        </div>
        <nav class="sidebar-nav">
            <?php if ($_SESSION['role'] == 'farmer'): ?>
                <a href="farmer.php" class="nav-item">🏠 Farm Overview</a>
                <a href="upload-product.php" class="nav-item">📦 My Products</a>
                <a href="sales.php" class="nav-item active">💰 Sales</a>
                <a href="notice.php" class="nav-item">📢 Notices</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif ($_SESSION['role'] == 'fisherman'): ?>
                <a href="fisherman.php" class="nav-item">🏠 Dashboard</a>
                <a href="sales.php" class="nav-item active">💰 Sales</a>
                <a href="upload-product.php" class="nav-item">📦 My Products</a>
                <a href="notice.php" class="nav-item">📢 Notices</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Sales History</h1>
                <p class="dashboard-subtitle">Track your earnings and orders</p>
            </div>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h2 class="table-title">Recent Sales</h2>
            </div>
            <div class="table-content">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Demo Data -->
                        <tr>
                            <td>#1001</td>
                            <td>Rui Fish</td>
                            <td>5 kg</td>
                            <td>৳1,800</td>
                            <td><?php echo date('M d, Y'); ?></td>
                            <td><span class="status-badge approved">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
