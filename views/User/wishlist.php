<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: ../home.php");
    exit;
}

// Get wishlist from session
$wishlist = $_SESSION['wishlist'] ?? [];

// Load products to get details
try {
    require_once '../../models/Product.php';
    $productModel = new Product();
    $allProducts = $productModel->getAllActive();
    if (empty($allProducts)) {
        $allProducts = $productModel->getDemoProducts();
    }
} catch (Exception $e) {
    $allProducts = [];
}

// Create a lookup array for products
$productLookup = [];
foreach ($allProducts as $product) {
    $productLookup[$product['name']] = $product;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/AgriFish-Digital-Fisheries-Agriculture-Platform-main/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle">Buyer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="customer.php" class="nav-item">🏠 Marketplace</a>
            <a href="cart.php" class="nav-item">🛒 My Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
            <a href="orders.php" class="nav-item">📦 My Orders</a>
            <a href="notice.php" class="nav-item">📢 Notices</a>
            <a href="wishlist.php" class="nav-item active">❤️ Wishlist</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">My Wishlist</h1>
                <p class="dashboard-subtitle">Products you've saved for later</p>
            </div>
        </div>

        <?php if (empty($wishlist)): ?>
            <div class="data-table">
                <div class="table-content" style="text-align: center; padding: 3rem;">
                    <h3>Your wishlist is empty</h3>
                    <p>Save products you like for easy access later!</p>
                    <a href="customer.php" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Browse Products</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Product Grid -->
            <div class="product-grid">
                <?php foreach ($wishlist as $productName):
                    $product = $productLookup[$productName] ?? null;
                    if ($product):
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php $image = $product['image'] ?? '🐟'; ?>
                        <?php if (filter_var($image, FILTER_VALIDATE_URL) || strpos($image, '/') === 0): ?>
                            <img src="<?php echo htmlspecialchars($image); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; font-size: 3rem; text-align: center; line-height: 1;"><?php echo htmlspecialchars($image); ?></div>
                        <?php else: ?>
                            <div style="font-size: 3rem; text-align: center; line-height: 1;"><?php echo htmlspecialchars($image); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <div class="product-header">
                            <div>
                                <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-seller"><?php echo htmlspecialchars($product['category']); ?> • DFAP</p>
                            </div>
                            <div class="product-rating">★ 4.8</div>
                        </div>
                        <div class="product-footer">
                            <div>
                                <p class="product-price">৳<?php echo number_format($product['price'], 0); ?></p>
                                <p class="product-unit">per <?php echo htmlspecialchars($product['unit'] ?? 'kg'); ?></p>
                            </div>
                            <div class="product-actions">
                                <form method="post" action="customer.php" style="display:inline;">
                                    <input type="hidden" name="action" value="add_cart">
                                    <input type="hidden" name="product" value="<?php echo htmlspecialchars($product['name']); ?>">
                                    <button type="submit" class="add-cart-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
