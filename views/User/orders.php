<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../home.php");
    exit;
}

// For demo purposes, show cart items as "orders"
$orders = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Buyer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="customer.php" class="nav-item">🏠 Marketplace</a>
            <a href="cart.php" class="nav-item">🛒 My Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
            <a href="orders.php" class="nav-item active">📦 My Orders</a>
            <a href="#" class="nav-item">💬 Messages</a>
            <a href="wishlist.php" class="nav-item">❤️ Wishlist</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">My Orders</h1>
                <p class="dashboard-subtitle">Track your fish and seafood purchases</p>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <div class="data-table">
                <div class="table-content" style="text-align: center; padding: 3rem;">
                    <h3>No orders yet</h3>
                    <p>You haven't placed any orders. Start shopping in our marketplace!</p>
                    <a href="customer.php" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Browse Products</a>
                </div>
            </div>
        <?php else: ?>
            <div class="data-table">
                <div class="table-header">
                    <h2 class="table-title">Your Orders (<?php echo count($orders); ?>)</h2>
                </div>
                <div class="table-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order); ?></td>
                                <td><span class="status-badge">Processing</span></td>
                                <td><?php echo date('M j, Y'); ?></td>
                                <td>
                                    <button class="btn-outline">Track Order</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>