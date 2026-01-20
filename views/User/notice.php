<?php
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location:../home.php");
    exit;
}

// Load notices
try {
    require_once __DIR__ . '/../../controllers/NoticeController.php';
    $noticeController = new NoticeController();
    $notices = $noticeController->getAll();
} catch (Exception $e) {
    $notices = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/DFAP/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle"><?php echo ucfirst($_SESSION['role']); ?> Portal</div>
        </div>
        <nav class="sidebar-nav">
            <?php if($_SESSION['role'] == 'customer'): ?>
                <a href="customer.php" class="nav-item">🏠 Marketplace</a>
                <a href="cart.php" class="nav-item">🛒 My Cart</a>
                <a href="orders.php" class="nav-item">📦 My Orders</a>
                <a href="notice.php" class="nav-item active">📢 Notices</a>
                <a href="wishlist.php" class="nav-item">❤️ Wishlist</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif($_SESSION['role'] == 'farmer'): ?>
                <a href="farmer.php" class="nav-item">🏠 Farm Overview</a>
                <a href="upload-product.php" class="nav-item">📦 My Products</a>
                <a href="sales.php" class="nav-item">💰 Sales</a>
                <a href="notice.php" class="nav-item active">📢 Notices</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif($_SESSION['role'] == 'fisherman'): ?>
                <a href="fisherman.php" class="nav-item">🏠 Dashboard</a>
                <a href="sales.php" class="nav-item">💰 Sales</a>
                <a href="upload-product.php" class="nav-item">📦 My Products</a>
                <a href="notice.php" class="nav-item active">📢 Notices</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Important Notices</h1>
                <p class="dashboard-subtitle">Updates and announcements from DFAP</p>
            </div>
        </div>

        <?php if (empty($notices)): ?>
            <div class="data-table">
                <div class="table-content" style="text-align: center; padding: 3rem;">
                    <h3>No notices at the moment</h3>
                    <p>Check back later for updates.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="notices-container">
                <?php foreach ($notices as $notice): ?>
                <div class="notice-card" style="margin-bottom: 1rem;">
                    <div class="notice-header">
                        <h3 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h3>
                        <span class="notice-date"><?php echo date('M j, Y', strtotime($notice['created_at'])); ?></span>
                    </div>
                    <div class="notice-content">
                        <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
                    </div>
                    <div class="notice-footer">
                        <span class="notice-author">By: <?php echo htmlspecialchars($notice['creator_name']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>